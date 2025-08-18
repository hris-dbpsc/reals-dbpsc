<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branches extends Model
{
     protected $fillable = [
        'clientid',
        'clientname',
        'clientshortname',
        'branchname',
        'branchcontact',
        'branchcontactperson',
        'branchaddress',
        'branchregion',
        'branchprovince',
        'branchcity',
        'branchgeolocation',
        'branchstreetview',
        'clienttype',
        'isactive',
        'encodedby'
    ];
}
       