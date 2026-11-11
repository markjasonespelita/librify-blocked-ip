<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SpatieRolesController extends Controller
{
    
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('roles.spatie-roles', compact('roles', 'permissions'));
    }

    public function storeSpatieRole(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:spatie_roles,name']);
        Role::create(['name' => $request->name]);
        return back()->with('success', 'Role created.');
    }

    public function updateSpatieRole(Request $request, $roleId)
    {
        $request->validate(['name' => 'required|string|unique:spatie_roles,name,' . $roleId]);
        Role::where('id', $roleId)->update(['name' => $request->name]);
        return back()->with('success', 'Role updated.');
    }

    public function destroySpatieRole($roleId)
    {
        Role::where('id', $roleId)->delete();
        return back()->with('success', 'Role deleted.');
    }

    public function updatePermissionForSpatieRole(Request $request, $roleId)
    {
        $request->validate([
            'spatie_permissions' => 'array', // optional, can be empty or missing
            'spatie_permissions.*' => 'string|exists:spatie_permissions,name',
        ]);

        $role = Role::findOrFail($roleId);

        if ($request->has('spatie_permissions')) {
            // Sync the given permissions to the role
            $role->syncPermissions($request->spatie_permissions);
        } else {
            // Remove all permissions if none selected
            $role->syncPermissions([]);
        }

        return back()->with('success', 'Permissions updated successfully.');
    }

    public function addPermission(Request $request, Role $role)
    {
        $request->validate(['permission' => 'required|exists:permissions,name']);
        $role->givePermissionTo($request->permission);
        return back()->with('success', 'Permission added to role.');
    }

    public function updatePermission(Request $request, Role $role, Permission $permission)
    {
        // Spatie permissions don’t have editable names by default
        // This method can rename permission if you want:
        $request->validate(['name' => 'required|string|unique:permissions,name,' . $permission->id]);
        $permission->update(['name' => $request->name]);
        return back()->with('success', 'Permission updated.');
    }

    public function removePermission(Role $role, Permission $permission)
    {
        $role->revokePermissionTo($permission);
        return back()->with('success', 'Permission removed from role.');
    }
}
