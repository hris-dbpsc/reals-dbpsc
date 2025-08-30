<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Api_Keys extends Model
{
    protected $table = 'api_keys';
    
    protected $fillable = [
            'app_name',
            'api_key',
        ];
}
