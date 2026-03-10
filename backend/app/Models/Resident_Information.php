<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resident_Information extends Model
{
    protected $table = "resident_information";

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'contact_number',
        'sex',
        'birth_place',
        'birth_date',
        'civil_status',
        'religion',
        'pwd',
        'nationality',
        'occupation',
        'educational_attainment',
        'emergency_contact_person',
        'emergency_contact_number',
        'emergency_contact_relationship',
        'image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
