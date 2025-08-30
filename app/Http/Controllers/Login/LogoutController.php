<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use App\Models\User;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        // =========================
        // Logout for all guards
        // =========================
        foreach (['superadmin', 'admin', 'clientadmin', 'user'] as $guard) {
            if (auth()->guard($guard)->check()) {
                auth()->guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully');
    }
}
