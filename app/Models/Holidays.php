<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holidays extends Model
{
    protected $table = 'holidays';
    
    protected $fillable = [
        'name',
        'date',
        'type',
        'is_repeating',
        'description',
    ];
}
