<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminApplicationsAccess extends Model
{
    protected $table = 'admin_applications_access';
    
    protected $fillable = [
        'adminid',
        'app_1',
        'app_2',
        'app_3',
        'app_4',
        'app_5',
        'app_6',
        'app_7',
        'app_8',
        'app_9',
        'app_10',
    ];
}
