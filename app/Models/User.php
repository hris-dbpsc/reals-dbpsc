<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Branches;
use App\Models\Client;


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
        'clientid',
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

    public function branch()
    {
        return $this->belongsTo(Branches::class, 'branchname', 'branchname');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'clientid', 'id');
    }

}
