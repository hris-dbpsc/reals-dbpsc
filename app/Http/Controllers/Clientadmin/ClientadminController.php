<?php

namespace App\Http\Controllers\Clientadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientadminController extends Controller
{
     // Dashboard route
     public function dashboard()
    {
        return view('clientadmin.dashboard');
    }
}
