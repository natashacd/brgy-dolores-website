<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit_Logs extends Model
{
    protected $table = 'audit_logs';
    
    protected $fillable = [
        'name',
        'role',
        'action',
        'description',
        'user_agent',
        'ip_address',
    ];
}
