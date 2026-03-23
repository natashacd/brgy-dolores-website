<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident_Status extends Model
{
    protected $table = "resident_status";

    protected $fillable = [
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
