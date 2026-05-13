<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'lines'])->paginate(10);
        $distributors = User::whereHas('role', function($q) {
            $q->where('name', 'موزع');
        })->select('id', 'name')->get();
        return view('admin.users.index', compact('users', 'distributors'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id'     => 'required|exists:roles,id',
            'base_salary' => 'nullable|numeric|min:0',
        ]);

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role_id'     => $request->role_id,
            'base_salary' => $request->base_salary ?? 0,
        ]);

        return redirect()->route('users.index')->with('success', __('messages.user_created_success') ?? 'User created successfully');
    }

    public function edit(User $user)
    {
        if ($user->role && $user->role->name === 'admin') {
            return redirect()->route('users.index')->with('error', __('messages.cannot_edit_admin') ?? 'Cannot edit admin user');
        }

        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role && $user->role->name === 'admin') {
            return redirect()->route('users.index')->with('error', __('messages.cannot_edit_admin') ?? 'Cannot edit admin user');
        }

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role_id'     => 'required|exists:roles,id',
            'base_salary' => 'nullable|numeric|min:0',
        ]);

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'role_id'     => $request->role_id,
            'base_salary' => $request->base_salary ?? 0,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', __('messages.user_updated_success') ?? 'User updated successfully');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', __('messages.cannot_delete_self') ?? 'You cannot delete yourself');
        }

        if ($user->role && $user->role->name === 'admin') {
            return redirect()->route('users.index')->with('error', __('messages.cannot_delete_admin') ?? 'Cannot delete admin user');
        }

        if ($user->role && $user->role->name === 'موزع') {
            $action = $request->input('line_action', 'delete');
            
            if ($action === 'reassign' && $request->filled('new_distributor_id')) {
                // Reassign all lines to the new distributor
                $newDistributorId = $request->input('new_distributor_id');
                // Temporarily disable the booted soft delete just in case, though we are changing the ID so they won't belong to this user anymore
                \App\Models\Line::where('distributor_id', $user->id)
                    ->update(['distributor_id' => $newDistributorId]);
            }
            // If action is 'delete', the User model's boot method will handle it via `$user->lines()->delete()`
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', __('messages.user_deleted_success') ?? 'User deleted successfully');
    }

    public function trashed()
    {
        $users = User::onlyTrashed()->with('role')->paginate(10);
        return view('admin.users.trashed', compact('users'));
    }

    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->route('users.trashed')->with('success', __('messages.user_restored_success') ?? 'User restored successfully');
    }

    public function forceDelete($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return redirect()->route('users.trashed')->with('success', __('messages.user_permanently_deleted') ?? 'User permanently deleted');
    }
}
