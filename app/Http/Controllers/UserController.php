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
        return view('admin.users.index', compact('users'));
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

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', __('messages.cannot_delete_self') ?? 'You cannot delete yourself');
        }

        if ($user->role && $user->role->name === 'admin') {
            return redirect()->route('users.index')->with('error', __('messages.cannot_delete_admin') ?? 'Cannot delete admin user');
        }

        // The cascading delete of lines is handled in User model boot method
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
