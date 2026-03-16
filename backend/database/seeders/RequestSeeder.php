<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Resident_Service_Request;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        Resident_Service_Request::updateOrCreate([
            'resident_id' => 3
        ], [
            'type' => 'ID',
            'image_path' => 'sample path',
            'preferred_date' => 'March 11, 2026',
            'notes' => 'Sample Note',
            'status' => 'pending'
        ]);
    }
}
