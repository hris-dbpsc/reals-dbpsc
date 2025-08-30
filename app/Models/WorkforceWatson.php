<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkforceWatson extends Model
{
    protected $fillable = [
            'requestclient',
            'requestdate',
            'requestby',
            'requestemail',
            'requesttype',
            'branchtarget',
            'branchreshufflefrom',
            'branchreshuffleto',
            'employeesreshuffled',
            'branchtransferfrom',
            'branchtransferto',
            'employeestransferred',
            'clientremarks',
            'status',
            'acknowledge',
            'acknowledgedate',
            'acknowledgeby',
            'attendedby',
            'attendeddate',
            'adminremarks',
            'token',
        ];
}
