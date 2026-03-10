<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['information', 'status', 'role'])
            ->whereHas('role', function ($query) {
                $query->where('role_name', '!=', 'Resident');
            })
            ->get();

        return response()->json($users);
    }

    public function roles()
    {
        $roles = Role::where('role_name', '!=', 'Resident')->get();
        return response()->json($roles);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email'   => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'email'   => $request->email,
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'message' => 'User updated successfully.',
            'user'    => $user->load(['information', 'status', 'role']),
        ]);
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);
        $user->update(['password' => Hash::make('adminadmin')]);

        return response()->json(['message' => 'Password reset to default successfully.']);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Get "Resident" role
        $residentRole = Role::where('role_name', 'Resident')->first();
        
        if (!$residentRole) {
            return response()->json(['message' => 'Resident role not found.'], 404);
        }

        // Change user role to Resident instead of deleting
        $user->update(['role_id' => $residentRole->id]);

        return response()->json(['message' => 'User role changed to Resident successfully.']);
    }
}