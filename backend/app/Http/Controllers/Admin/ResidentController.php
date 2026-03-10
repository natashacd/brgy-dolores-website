<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Resident_Address;
use App\Models\Resident_Information;
use App\Models\Resident_Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = User::with(['information', 'status', 'role', 'address'])
            ->whereHas('role', function ($query) {
                $query->where('role_name', 'Resident');
            })
            ->get();

        return response()->json($residents);
    }

    public function roles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'                           => 'required|email|unique:users,email',
            'role_id'                         => 'required|exists:roles,id',
            'first_name'                      => 'required|string|max:255',
            'middle_name'                     => 'nullable|string|max:255',
            'last_name'                       => 'required|string|max:255',
            'suffix'                          => 'nullable|string|max:255',
            'contact_number'                  => 'required|string|max:20',
            'sex'                             => 'required|in:Male,Female',
            'birth_place'                     => 'required|string|max:255',
            'birth_date'                      => 'required|string|max:255',
            'civil_status'                    => 'required|in:Single,Married,Widowed,Annulled,Divorced,Legally Separated',
            'religion'                        => 'nullable|string|max:255',
            'pwd'                             => 'nullable|string|max:255',
            'nationality'                     => 'required|string|max:255',
            'occupation'                      => 'nullable|string|max:255',
            'educational_attainment'          => 'nullable|string|max:255',
            'emergency_contact_person'        => 'required|string|max:255',
            'emergency_contact_number'        => 'required|string|max:20',
            'emergency_contact_relationship'  => 'required|string|max:255',
            'house_no'                        => 'required|string|max:100',
            'purok'                           => 'nullable|string|max:100',
            'sitio'                           => 'required|string|max:255',
            'latitude'                        => 'required|string',
            'longitude'                       => 'required|string',
        ]);

        $user = User::create([
            'email'    => $request->email,
            'password' => Hash::make('adminadmin'),
            'role_id'  => $request->role_id,
        ]);

        Resident_Information::create([
            'user_id'                        => $user->id,
            'first_name'                     => $request->first_name,
            'middle_name'                    => $request->middle_name,
            'last_name'                      => $request->last_name,
            'suffix'                         => $request->suffix,
            'contact_number'                 => $request->contact_number,
            'sex'                            => $request->sex,
            'birth_place'                    => $request->birth_place,
            'birth_date'                     => $request->birth_date,
            'civil_status'                   => $request->civil_status,
            'religion'                       => $request->religion,
            'pwd'                            => $request->pwd,
            'nationality'                    => $request->nationality,
            'occupation'                     => $request->occupation,
            'educational_attainment'         => $request->educational_attainment,
            'emergency_contact_person'       => $request->emergency_contact_person,
            'emergency_contact_number'       => $request->emergency_contact_number,
            'emergency_contact_relationship' => $request->emergency_contact_relationship,
        ]);

        Resident_Address::create([
            'user_id'   => $user->id,
            'house_no'  => $request->house_no,
            'purok'     => $request->purok,
            'sitio'     => $request->sitio,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        Resident_Status::create([
            'user_id' => $user->id,
            'status'  => 1,
        ]);

        return response()->json([
            'message'  => 'Resident created successfully.',
            'resident' => $user->load(['information', 'status', 'role', 'address']),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email'                           => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'first_name'                      => 'required|string|max:255',
            'middle_name'                     => 'nullable|string|max:255',
            'last_name'                       => 'required|string|max:255',
            'suffix'                          => 'nullable|string|max:255',
            'contact_number'                  => 'required|string|max:20',
            'sex'                             => 'required|in:Male,Female',
            'birth_place'                     => 'required|string|max:255',
            'birth_date'                      => 'required|string|max:255',
            'civil_status'                    => 'required|in:Single,Married,Widowed,Annulled,Divorced,Legally Separated',
            'religion'                        => 'nullable|string|max:255',
            'pwd'                             => 'nullable|string|max:255',
            'nationality'                     => 'required|string|max:255',
            'occupation'                      => 'nullable|string|max:255',
            'educational_attainment'          => 'nullable|string|max:255',
            'emergency_contact_person'        => 'required|string|max:255',
            'emergency_contact_number'        => 'required|string|max:20',
            'emergency_contact_relationship'  => 'required|string|max:255',
            'house_no'                        => 'required|string|max:100',
            'purok'                           => 'nullable|string|max:100',
            'sitio'                           => 'required|string|max:255',
            'latitude'                        => 'required|string',
            'longitude'                       => 'required|string',
            'status'                          => 'required|boolean',
        ]);

        $user->update(['email' => $request->email]);

        $user->information()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'first_name'                     => $request->first_name,
                'middle_name'                    => $request->middle_name,
                'last_name'                      => $request->last_name,
                'suffix'                         => $request->suffix,
                'contact_number'                 => $request->contact_number,
                'sex'                            => $request->sex,
                'birth_place'                    => $request->birth_place,
                'birth_date'                     => $request->birth_date,
                'civil_status'                   => $request->civil_status,
                'religion'                       => $request->religion,
                'pwd'                            => $request->pwd,
                'nationality'                    => $request->nationality,
                'occupation'                     => $request->occupation,
                'educational_attainment'         => $request->educational_attainment,
                'emergency_contact_person'       => $request->emergency_contact_person,
                'emergency_contact_number'       => $request->emergency_contact_number,
                'emergency_contact_relationship' => $request->emergency_contact_relationship,
            ]
        );

        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'house_no'  => $request->house_no,
                'purok'     => $request->purok,
                'sitio'     => $request->sitio,
                'latitude'  => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        $user->status()->updateOrCreate(
            ['user_id' => $user->id],
            ['status' => $request->status]
        );

        return response()->json([
            'message'  => 'Resident updated successfully.',
            'resident' => $user->load(['information', 'status', 'role', 'address']),
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

        return response()->json(['message' => 'Resident deleted successfully.']);
    }
}
