<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use App\Models\Audit_Logs;

class AccountController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load(['information', 'role', 'address']);

        return response()->json([
            'id'         => $user->id,
            'email'      => $user->email,
            'first_name' => $user->information?->first_name,
            'last_name'  => $user->information?->last_name,
            'phone'      => $user->information?->contact_number,
            'address'    => $user->address 
                ? $user->address->house_no . ', ' . $user->address->purok . ', ' . $user->address->sitio
                : null,
            'role'       => $user->role?->role_name,
            'is_active'  => $user->status?->status ?? true,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $user->id,
            'phone'      => 'nullable|string|max:20',
        ]);

        return DB::transaction(function () use ($request, $user) {
            // Update email
            $user->update([
                'email' => $request->email,
            ]);

            // Update information
            if ($user->information) {
                $user->information->update([
                    'first_name'     => $request->first_name,
                    'last_name'      => $request->last_name,
                    'contact_number' => $request->phone,
                ]);
            }

            // Log the action
            Audit_Logs::create([
                'name'        => $request->first_name . ' ' . $request->last_name,
                'role'        => $user->role?->role_name,
                'action'      => 'Update Profile',
                'description' => 'Updated account profile information.',
                'user_agent'  => $request->userAgent(),
                'ip_address'  => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Profile updated successfully.',
                'user'    => [
                    'id'         => $user->id,
                    'email'      => $user->email,
                    'first_name' => $user->information?->first_name,
                    'last_name'  => $user->information?->last_name,
                    'phone'      => $user->information?->contact_number,
                    'role'       => $user->role?->role_name,
                ]
            ]);
        });
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|different:current_password',
            'confirm_password' => 'required|string|same:new_password',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        return DB::transaction(function () use ($request, $user) {
            // Update password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            // Log the action
            Audit_Logs::create([
                'name'        => $user->information?->first_name . ' ' . $user->information?->last_name,
                'role'        => $user->role?->role_name,
                'action'      => 'Change Password',
                'description' => 'Changed account password.',
                'user_agent'  => $request->userAgent(),
                'ip_address'  => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Password changed successfully.',
            ]);
        });
    }
}