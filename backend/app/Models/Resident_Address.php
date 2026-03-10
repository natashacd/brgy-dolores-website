<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident_Address extends Model
{
    protected $table = "resident_address";

    protected $fillable = [
        'user_id',
        'house_no',
        'purok',
        'sitio',
        'latitude',
        'longitude',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
