<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeOff extends Model
{
    protected $table = 'timeoff';
    
    protected $fillable = [
        'leaveclientid',
        'leavebranchid',
        'leaveby',
        'leavetype',
        'leaverequestdate',
        'leavedatefrom',
        'leavedateto',
        'leavedays',
        'leavereason',
        'leaveapprovedby',
        'leaveapproveddate',
        'leaveattachment',
        'leaveremarks',
        'leavestatus',
    ];
}
