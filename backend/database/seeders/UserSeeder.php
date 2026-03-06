<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\User_Information;
use App\Models\User_Status;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@gmail.com',
        ], [
            'password' => Hash::make('adminadmin'),
            'role_id' => 1,
        ]);

        User_Information::updateOrCreate([
            'user_id' => 1,
        ], [
            'first_name' => 'Admin',
            'middle_name' => 'Admin',
            'last_name' => 'Admin',
            'contact_number' => '09123456789',
            'address' => 'Admin Street, Admin City, Admin Province',
        ]);

        User_Status::updateOrCreate([
            'user_id' => 1,
        ], [
            'status' => '1',
        ]);
    }
}
