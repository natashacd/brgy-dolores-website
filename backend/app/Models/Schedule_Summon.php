<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule_Summon extends Model
{
    protected $table = 'schedule_summon';

    protected $fillable = [ 
        'case_id',
        'date',
        'notes',
    ];

}
