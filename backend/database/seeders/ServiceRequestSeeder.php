<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident_Service_Request;

class ServiceRequestSeeder extends Seeder
{
    public function run(): void
    {
        $requests = [
            [
                'resident_id' => 3,
                'staff_id' => 2,
                'type' => 'barangay_clearance',
                'status' => 'pending'
            ],
                        [
                'resident_id' => 4,
                'staff_id' => 2,
                'type' => 'barangay_clearance',
                'status' => 'pending'
            ],
        ];

        foreach ($requests as $request) {
            Resident_Service_Request::create($request);
        }
    }
}
