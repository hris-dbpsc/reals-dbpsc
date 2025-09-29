<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use App\Models\User;

class LoginController extends Controller
{
    public function login_multiauth(Request $request)
    {
        // =========================
        // Credentials Validation
        // Accept a single 'username' field (could be email or employeenumber)
        // =========================

        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Ensure any existing authentication state is cleared for all guards
        // This avoids having multiple guard sessions active in the same browser/session.
        $allGuards = ['superadmin', 'admin', 'clientadmin', 'user'];
        foreach ($allGuards as $g) {
            try { auth()->guard($g)->logout(); } catch (\Throwable $e) { /* ignore */ }
        }
        // Invalidate current session and regenerate CSRF token
        try {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        } catch (\Throwable $e) { /* ignore session driver issues */ }

        // =========================
        // Multi-Auth Authentication
        // =========================
        $roles = [
            [
                'model' => Superadmin::class,
                'guard' => 'superadmin',
                'dashboard' => 'superadmin_dashboard',
            ],
            [
                'model' => Admin::class,
                'guard' => 'admin',
                'dashboard' => 'admin_dashboard',
            ],
            [
                'model' => Clientadmin::class,
                'guard' => 'clientadmin',
                'dashboard' => 'clientadmin_dashboard',
            ],
            [
                'model' => User::class,
                'guard' => 'user',
                'dashboard' => 'user_dashboard',
            ],
        ];

        foreach ($roles as $role) {

            // =========================
            // For user, use employeenumber; for others, use email
            // =========================
            if ($role['guard'] === 'user') {
                $user = $role['model']::where('employeenumber', $username)->first();
                if ($user) {
                    if ($user->isactive == 0) {
                        return redirect()->back()->with('error', 'Account is deactivated.');
                    }
                    if ($user->isactive == 2) {
                        return redirect()->back()->with('error', 'Account is suspended.');
                    }
                    $credentials = ['employeenumber' => $username, 'password' => $password];
                    if (auth()->guard('user')->attempt($credentials)) {
                        // regenerate session id for the newly authenticated user
                        try { $request->session()->regenerate(); } catch (\Throwable $e) { /* ignore */ }
                        return redirect()->route('user_dashboard')->with('success', 'Login Successful');
                    } else {
                        return redirect()->back()->with('error', 'Invalid credentials!');
                    }
                }
            } else {
                $user = $role['model']::where('email', $username)->first();
                if ($user) {
                    if ($user->isactive == 0) {
                        return redirect()->back()->with('error', 'Account is deactivated.');
                    }
                    if ($user->isactive == 2) {
                        return redirect()->back()->with('error', 'Account is suspended.');
                    }
                    $credentials = ['email' => $username, 'password' => $password];
                    if (auth()->guard($role['guard'])->attempt($credentials)) {
                        // regenerate session id for the newly authenticated user
                        try { $request->session()->regenerate(); } catch (\Throwable $e) { /* ignore */ }
                        return redirect()->route($role['dashboard'])->with('success', 'Login Successful');
                    } else {
                        return redirect()->back()->with('error', 'Invalid credentials!');
                    }
                }
            }
        }

        return redirect()->back()->with('error', 'We could not find an account matching these credentials.');
    }
}
