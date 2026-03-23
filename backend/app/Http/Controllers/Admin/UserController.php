<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Audit_Logs;

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



        return DB::transaction(function () use ($request, $user) {
            $user->update([
                'email'   => $request->email,
                'role_id' => $request->role_id,
            ]);

            Audit_Logs::create([
                'name' => ($user->information?->first_name ?? '') . ' ' . ($user->information?->last_name ?? ''),
                'role' => $user->role?->role_name,
                'action' => 'Update',
                'description' => 'Updated user information.',
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'User updated successfully.',
                'user'    => $user->load(['information', 'status', 'role']),
            ]);
        });
    }

    public function appoint(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => ['required', 'exists:roles,id', function ($attr, $value, $fail) {
                $role = Role::find($value);
                if ($role && $role->role_name === 'Resident') {
                    $fail('Cannot appoint a user with the Resident role.');
                }
            }],
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::findOrFail($request->user_id);
            $user->update(['role_id' => $request->role_id]);

            Audit_Logs::create([
                'name' => ($user->information?->first_name ?? '') . ' ' . ($user->information?->last_name ?? ''),
                'role' => $user->role?->role_name,
                'action' => 'Appoint',
                'description' => 'Appointed user to a new role.',
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'User appointed successfully.',
                'user'    => $user->load(['information', 'status', 'role']),
            ]);
        });
    }

    public function resetPassword($id, Request $request)
    {
        $user = User::findOrFail($id);

        return DB::transaction(function () use ($user, $request) {
            $user->update(['password' => Hash::make('adminadmin')]);
            Audit_Logs::create([
                'name' => ($user->information?->first_name ?? '') . ' ' . ($user->information?->last_name ?? ''),
                'role' => $user->role?->role_name,
                'action' => 'Reset Password',
                'description' => 'Reset user password to default.',
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);
            return response()->json(['message' => 'Password reset to default successfully.']);
        });
    }

    public function destroy($id, Request $request)
    {
        $user = User::findOrFail($id);

        return DB::transaction(function () use ($user, $request) {
            $residentRole = Role::where('role_name', 'Resident')->first();

            if (!$residentRole) {
                return response()->json(['message' => 'Resident role not found.'], 404);
            }

            $user->update(['role_id' => $residentRole->id]);

            Audit_Logs::create([
                'name' => ($user->information?->first_name ?? '') . ' ' . ($user->information?->last_name ?? ''),
                'role' => $user->role?->role_name,
                'action' => 'Destroy',
                'description' => 'Changed user role to Resident.',
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            return response()->json(['message' => 'User role changed to Resident successfully.']);
        });
    }
}
