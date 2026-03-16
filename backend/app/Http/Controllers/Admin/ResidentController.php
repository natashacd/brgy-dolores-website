<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Resident_Address;
use App\Models\Resident_Information;
use App\Models\Resident_Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ResidentController extends Controller
{
    public function index()
    {
        $residents = User::with(['information', 'status', 'role', 'address'])->get();

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
            'email' => 'required|email|unique:users,email',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:4096',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::create([
                'email'    => $request->email,
                'password' => Hash::make('adminadmin'),
                'role_id'  => 7,
            ]);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image     = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $imageName = $user->id . '.' . $extension;
                $imagePath = $image->storeAs('images', $imageName, 'public');
            }

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
                'image_path'                     => $imagePath,
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
        });
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:4096',
        ]);

        return DB::transaction(function () use ($request, $user) {
            User::where('id', $user->id)->update(['email' => $request->email]);

            $imagePath = $user->information?->image_path;
            if ($request->hasFile('image')) {
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $image     = $request->file('image');
                $extension = $image->getClientOriginalExtension();
                $imageName = $user->id . '.' . $extension;
                $imagePath = $image->storeAs('images', $imageName, 'public');
            }

            Resident_Information::updateOrCreate(
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
                    'image_path'                     => $imagePath,
                ]
            );

            Resident_Address::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'house_no'  => $request->house_no,
                    'purok'     => $request->purok,
                    'sitio'     => $request->sitio,
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                ]
            );

            Resident_Status::updateOrCreate(
                ['user_id' => $user->id],
                ['status' => $request->status]
            );

            return response()->json([
                'message'  => 'Resident updated successfully.',
                'resident' => $user->load(['information', 'status', 'role', 'address']),
            ]);
        });
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        return DB::transaction(function () use ($user) {
            User::where('id', $user->id)->update([
                'password' => Hash::make('adminadmin'),
            ]);

            return response()->json(['message' => 'Password reset to default successfully.']);
        });
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        return DB::transaction(function () use ($user) {
            if ($user->information && $user->information->image_path) {
                Storage::disk('public')->delete($user->information->image_path);
            }

            User::where('id', $user->id)->delete();

            return response()->json(['message' => 'Resident deleted successfully.']);
        });
    }
}
