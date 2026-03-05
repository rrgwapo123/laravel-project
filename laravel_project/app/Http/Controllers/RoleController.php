<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        $request->user()->can('view roles');

        return Role::with('permissions')->get();
    }

    /**
     * Store a newly created role.
     */
    public function store(Request $request)
    {
        $request->user()->can('create roles');

        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $role = Role::create(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $role->givePermissionTo($validated['permissions']);
        }

        return response()->json($role->load('permissions'), 201);
    }

    /**
     * Display the specified role.
     */
    public function show(Request $request, string $id)
    {
        $request->user()->can('view roles');

        return Role::with('permissions')->findOrFail($id);
    }

    /**
     * Update the specified role.
     */
    public function update(Request $request, string $id)
    {
        $request->user()->can('edit roles');

        $role = Role::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|unique:roles,name,' . $id,
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        if (isset($validated['name'])) {
            $role->update(['name' => $validated['name']]);
        }

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return response()->json($role->load('permissions'));
    }

    /**
     * Remove the specified role.
     */
    public function destroy(Request $request, string $id)
    {
        $request->user()->can('delete roles');

        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    /**
     * Get all permissions.
     */
    public function permissions(Request $request)
    {
        $request->user()->can('view roles');

        return Permission::all();
    }
}
