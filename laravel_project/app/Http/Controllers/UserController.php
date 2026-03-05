<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->user()->can('view users');

        return User::with('roles')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->user()->can('create users');

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $request->user()->can('view users');

        return User::with('roles')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->user()->can('edit users');

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $request->user()->can('delete users');

        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, string $id)
    {
        $request->user()->can('edit roles');

        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->assignRole($validated['role']);

        return response()->json([
            'message' => 'Role assigned successfully',
            'user' => $user->load('roles')
        ]);
    }

    /**
     * Remove role from user
     */
    public function removeRole(Request $request, string $id)
    {
        $request->user()->can('edit roles');

        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user = User::findOrFail($id);
        $user->removeRole($validated['role']);

        return response()->json([
            'message' => 'Role removed successfully',
            'user' => $user->load('roles')
        ]);
    }
}
