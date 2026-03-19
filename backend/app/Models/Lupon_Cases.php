<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lupon_Cases extends Model
{
    protected $table = 'lupon_cases';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'incident_date',
        'location',
        'description',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
