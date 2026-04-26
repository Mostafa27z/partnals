<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create($request->all());

        return redirect()->route('roles.index')->with('success', __('messages.role_created_success'));
    }

    public function edit(Role $role)
    {
        if ($role->name === 'admin' || $role->name === 'موزع') {
            return redirect()->route('roles.index')->with('error', __('messages.role_edit_protected_error'));
        }
        return view('admin.roles.index', ['roles' => Role::all()]); // Since we use modals, we go back to index
    }

    public function update(Request $request, Role $role)
    {
        if ($role->name === 'admin' || $role->name === 'موزع') {
            return redirect()->route('roles.index')->with('error', __('messages.role_edit_protected_error'));
        }

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update($request->all());

        return redirect()->route('roles.index')->with('success', __('messages.role_updated_success'));
    }

    public function destroy(Role $role)
    {
        // Protected roles cannot be deleted
        if ($role->name === 'admin' || $role->name === 'موزع') {
            return redirect()->route('roles.index')->with('error', __('messages.role_protected_error'));
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', __('messages.role_deleted_success'));
    }
}
