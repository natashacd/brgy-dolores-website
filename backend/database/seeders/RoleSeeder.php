<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['role_name' => 'Admin'],
            ['role_name' => 'Secretary'],
            ['role_name' => 'Treasurer'],
            ['role_name' => 'Staff'],
            ['role_name' => 'Lupon'],
            ['role_name' => 'Kagawad'],
            ['role_name' => 'Resident'],
            ['role_name' => 'SK'],
            ['role_name' => 'Health-Worker']
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate($role);
        }
    }
}
