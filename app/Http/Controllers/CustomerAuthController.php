<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Models\Customer;
use App\Models\User;
use App\Notifications\CustomerRegistrationNotification;
use App\Notifications\NewCustomerAdminNotification;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('frontend.auth.login');
    }

    public function showRegisterForm()
    {
        return view('frontend.auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('customer')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_subscribed' => $request->has('subscribe'),
            'status' => true,
        ]);

        Auth::guard('customer')->login($customer);

        // Send welcome email to customer
        $customer->notify(new CustomerRegistrationNotification($customer));

        // Send notification to admin about new customer
        $adminUsers = User::where('role', 'admin')->get();
        if ($adminUsers->count() > 0) {
            Notification::send($adminUsers, new NewCustomerAdminNotification($customer));
        }

        return redirect('/')->with('success', 'Account created successfully! Welcome email sent to your inbox.');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function dashboard()
    {
        return view('frontend.customer.dashboard');
    }
}
