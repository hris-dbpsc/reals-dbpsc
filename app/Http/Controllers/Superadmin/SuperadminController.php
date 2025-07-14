<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;   
use Hash;
use App\Models\Superadmin;
use App\Mail\Websitemail;


class SuperadminController extends Controller
{
    // Dashboard route
    public function dashboard()
    {
        return view('superadmin.dashboard');
    }
    // Login route
    public function index()
    {
    return view('superadmin.index');
    }



    // Login validation and authentication
    public function login_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $check = $request->all();
        $data = [
            'email' => $check['email'],
            'password' => $check['password'],
        ];

        if(Auth::guard('superadmin')->attempt($data)) {
            return redirect()->route('superadmin_dashboard')->with('Success', 'Login Successfull');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials!');
        }

    }

    // Forget password route
    public function forget_password()
    {
        return view('superadmin.forget_password');
    }

      public function forget_password_submit(Request $request)
    {
    
    }
    
    // Logout route
    public function logout()
    {
        Auth::guard('superadmin')->logout();
        return redirect()->route('superadmin_index')->with('success', 'Logged out successfully');   
    }

}


