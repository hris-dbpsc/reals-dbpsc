<?php

namespace App\Http\Controllers\Clientadmin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Clientadmin;
use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Client;
use App\Models\Branches;
use App\Mail\Websitemail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientadminController extends Controller
{
    // Dashboard Route Start
    public function dashboard()
    {
        return view('clientadmin.dashboard'); // Make sure resources/views/clientadmin/dashboard.blade.php exists
    }
    // Dashboard Route End
}
