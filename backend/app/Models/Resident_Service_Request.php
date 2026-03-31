<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident_Service_Request extends Model
{
    protected $table = 'resident_service_request';

    protected $fillable = [
        'resident_id',
        'type',
        'image_path',
        'preferred_date',
        'notes',
        'status',
        'remarks',
    ];

    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }
}
