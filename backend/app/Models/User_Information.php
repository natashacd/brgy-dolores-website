<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User_Information extends Model
{
    protected $table = "user_information";

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'contact_number',
        'address',
    ];
}
