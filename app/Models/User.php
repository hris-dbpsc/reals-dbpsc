<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'employeenumber',
        'clientname',
        'branchname',
        'departmentname',
        'position',
        'firstname',
        'middlename',
        'lastname',
        'suffix',
        'dateofbirth',
        'gender',
        'assumptiondate',
        'startdate',
        'enddate',
        'templatename',
        'hiretype',
        'wagetype',
        'paymode',
        'salaryrate',
        'billingrate',
        'positioncategory',
        'leavecredits',
        'civilstatus',
        'address',
        'contact',
        'tin',
        'sssnumber',
        'phicnumber',
        'hdmfnumber',
        'lastpaydate',
        'region',
        'email',
        'password',
        'role',
        'isactive',
        'token',
    ];
}
