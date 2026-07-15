<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers
     */
    public function index()
    {
        $customers = Customer::orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('admin.customers.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'password' => 'required|string|min:6|confirmed',
            'is_subscribed' => 'boolean',
            'status' => 'boolean',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_subscribed' => $request->has('is_subscribed'),
            'status' => $request->has('status'),
        ]);

        return redirect()->route('admin.customers.index')
                        ->with('success', 'Customer created successfully!');
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer)
    {
        return view('admin.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('customers')->ignore($customer->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'is_subscribed' => 'boolean',
            'status' => 'boolean',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'is_subscribed' => $request->has('is_subscribed'),
            'status' => $request->has('status'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $customer->update($updateData);

        return redirect()->route('admin.customers.index')
                        ->with('success', 'Customer updated successfully!');
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('admin.customers.index')
                        ->with('success', 'Customer deleted successfully!');
    }

    /**
     * Update customer status
     */
    public function updateStatus(Request $request, Customer $customer)
    {
        $request->validate([
            'status' => 'required|boolean',
        ]);

        $customer->update(['status' => $request->status]);

        $statusText = $request->status ? 'activated' : 'deactivated';
        
        return redirect()->route('admin.customers.index')
                        ->with('success', "Customer {$statusText} successfully!");
    }

    /**
     * Bulk delete customers
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'customer_ids' => 'required|array|min:1',
            'customer_ids.*' => 'exists:customers,id',
        ]);

        Customer::whereIn('id', $request->customer_ids)->delete();

        return redirect()->route('admin.customers.index')
                        ->with('success', 'Selected customers deleted successfully!');
    }
}

