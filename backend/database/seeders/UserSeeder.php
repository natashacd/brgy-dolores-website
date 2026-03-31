<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Resident_Information;
use App\Models\Resident_Address;
use App\Models\Resident_Status;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'admin@gmail.com',
                'password' => 'adminadmin',
                'role_id' => 1,
                'info' => ['first_name' => 'Admin'],
            ],
            [
                'email' => 'staff@gmail.com',
                'password' => 'adminadmin',
                'role_id' => 4,
                'info' => ['first_name' => 'Staff'],
            ],
            [
                'email' => 'resident@gmail.com',
                'password' => 'adminadmin',
                'role_id' => 7,
                'info' => ['first_name' => 'Resident'],
            ],
            [
                'email' => 'lupon@gmail.com',
                'password' => 'adminadmin',
                'role_id' => 5,
                'info' => ['first_name' => 'Lupon'],
            ],
        ];

        foreach ($users as $data) {

            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'password' => Hash::make($data['password']),
                    'role_id' => $data['role_id'],
                ]
            );

            Resident_Status::updateOrCreate(
                ['user_id' => $user->id],
                ['status' => 1]
            );

            Resident_Address::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'house_no' => 'Admin House No.',
                    'purok' => 'Admin Purok',
                    'sitio' => 'Admin Sitio',
                    'latitude' => '0.000000',
                    'longitude' => '0.000000',
                ]
            );

            Resident_Information::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'first_name' => $data['info']['first_name'],
                    'middle_name' => $data['info']['first_name'],
                    'last_name' => $data['info']['first_name'],
                    'contact_number' => '09123456789',
                    'sex' => 'Male',
                    'birth_place' => 'Admin Birth Place',
                    'birth_date' => '1990-01-01',
                    'civil_status' => 'Single',
                    'religion' => 'Admin Religion',
                    'pwd' => 'No',
                    'nationality' => 'Filipino',
                    'occupation' => 'Admin Occupation',
                    'educational_attainment' => 'Admin Educational Attainment',
                    'emergency_contact_person' => 'Admin Emergency Contact Person',
                    'emergency_contact_number' => '09123456789',
                    'emergency_contact_relationship' => 'Admin Emergency Contact Relationship',
                    'image_path' => null,
                ]
            );
        }
    }
}