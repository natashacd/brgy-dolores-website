<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident_Service_Request extends Model
{
    protected $table = 'resident_service_request';

    protected $fillable = [
        'resident_id',
        'staff_id',
        'type',
        'status',
    ];

    public function resident()
    {
        return $this->belongsTo(User::class, 'resident_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
}
