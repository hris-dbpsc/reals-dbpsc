<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeLog extends Model
{
    protected $table = 'time_logs';

    protected $fillable = [
        'client_id',
        'user_id',
        'employeenumber',
        'branch_id',
        'action',
        'recorded_at',
        'timezone',
        'duration_seconds',
        'device',
        'ip_address',
        'location',
        'meta',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'meta' => 'array',
        'duration_seconds' => 'integer',
    ];
}
