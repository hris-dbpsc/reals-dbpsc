<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('user.dashboard');
    }


    // Logout route
    public function logout()
    {
        Auth::guard('user')->logout();
        return redirect('/')->with('success', 'Logged out successfully');
    }
}
