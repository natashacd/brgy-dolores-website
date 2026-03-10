<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\User_Information;
use App\Models\User_Status;
use App\Models\Resident_Address;
use App\Models\Resident_Information;
use App\Models\Resident_Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['information', 'status', 'role'])->get();

        return response()->json($users);
    }

    public function roles()
    {
        $roles = Role::all();

        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'          => 'required|email|unique:users,email',
            'role_id'        => 'required|exists:roles,id',
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'status'         => 'required|boolean',
        ]);

        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make('adminadmin'),
            'role_id'  => $request->role_id,
        ]);

        User_Information::create([
            'user_id'        => $user->id,
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'last_name'      => $request->last_name,
            'address'        => $request->address,
            'contact_number' => $request->contact_number,
        ]);

        User_Status::create([
            'user_id' => $user->id,
            'status'  => $request->status,
        ]);

        return response()->json([
            'message' => 'User created successfully.',
            'user'    => $user->load(['information', 'status', 'role']),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email'          => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'first_name'     => 'required|string|max:255',
            'middle_name'    => 'nullable|string|max:255',
            'last_name'      => 'required|string|max:255',
            'address'        => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'status'         => 'required|boolean',
        ]);

        $user->update(['email' => $request->email]);

        $user->information()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name'     => $request->first_name,
                'middle_name'    => $request->middle_name,
                'last_name'      => $request->last_name,
                'address'        => $request->address,
                'contact_number' => $request->contact_number,
            ]
        );

        $user->status()->updateOrCreate(
            ['user_id' => $user->id],
            ['status' => $request->status]
        );

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
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
