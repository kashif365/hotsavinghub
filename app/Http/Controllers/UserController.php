<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        
        // Log the view action
        ActivityLogService::log('view', 'Viewed users list', null, null, null, request());
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Log the view action
        ActivityLogService::log('view', 'Viewed user creation form', null, null, null, request());
        
        return view('admin.users.create');
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive,suspended',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone,
        ]);
        
        // Log the creation action
        ActivityLogService::logCreate($user, request());

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        // Log the view action
        ActivityLogService::logView($user, request());
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        // Log the view action
        ActivityLogService::logView($user, request());
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'status' => 'required|in:active,inactive,suspended',
            'phone' => 'nullable|string|max:20',
        ]);

        // Store old values for logging
        $oldValues = $user->toArray();

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => $request->status,
            'phone' => $request->phone,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);
        
        // Log the update action
        ActivityLogService::logUpdate($user, $oldValues, $user->fresh()->toArray(), request());

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        // Prevent deleting the current logged-in user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        // Log the deletion action before deleting
        ActivityLogService::logDelete($user, request());

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully!');
    }

    /**
     * Bulk delete users
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->ids;
        if (!$ids) {
            return back()->with('error', 'No users selected.');
        }

        // Prevent deleting the current logged-in user
        $userIds = array_diff($ids, [auth()->id()]);
        
        if (empty($userIds)) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $users = User::whereIn('id', $userIds)->get();
        
        // Log the bulk deletion action
        ActivityLogService::logBulkAction('delete', 'User', count($users), request());
        
        foreach ($users as $user) {
            $user->delete();
        }

        return back()->with('success', 'Selected users deleted successfully.');
    }

    /**
     * Update user status
     */
    public function updateStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        // Prevent changing status of current logged-in user
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own status!');
        }

        $user->update(['status' => $request->status]);

        return back()->with('success', 'User status updated successfully!');
    }

    /**
     * Reorder users
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'order'             => 'required|array',
            'order.*.id'        => 'required|integer|exists:users,id',
            'order.*.sort_order'=> 'required|integer|min:1',
        ]);

        foreach ($request->order as $item) {
            User::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['status' => 'success']);
    }
}
