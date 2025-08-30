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
        'clientcontact',
        'clientcontactperson',
        'clientemail',
        'clientaddress',
        'clientcity',
        'clientprovince',
        'clientregion',
        'clientcontractstart',
        'clientcontractend',
        'isactive',
        'clientgeolocation',
        'clientstreetview',
    ];
}
