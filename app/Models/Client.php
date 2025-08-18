<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    protected $fillable = [
        'clientname',
        'clientshortname',
        'clienttype',
        'clientphoto',
        'isactive',
        'clientgeolocation',
        'clientstreetview',
    ];
}
