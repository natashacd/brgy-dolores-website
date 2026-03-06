<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Status extends Model
{
    protected $table = "user_status";

    protected $fillable = [
        'user_id',
        'status',
    ];
}
