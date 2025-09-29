<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use App\Models\User;
use App\Models\Client;

class UserManagementController extends Controller
{
    // =========================================
    // User Management Routes
    // 

    /**
     * Display the User Management page.
     */
    public function usermanagement()
    {
        $activeSuperadmins = Superadmin::where('isactive', 1)->get();
        $activeAdmins = Admin::where('isactive', 1)->get();
        $activeClientadmins = Clientadmin::where('isactive', 1)->get();
        $activeEmployees = User::where('isactive', 1)->get();
        $superadminCount = $activeSuperadmins->count();
        $adminCount = $activeAdmins->count();
        $clientadminCount = $activeClientadmins->count();
        $employeeCount = $activeEmployees->count();
        $totalActive = $superadminCount + $adminCount + $clientadminCount;
        $clients = Client::select('id', 'clientshortname')->get();
        // Build a map of clientid => clientname for fast lookup
        $clientMap = $clients->pluck('clientshortname', 'id');
        return view('superadmin.usermanagement.usermanagement', compact('activeSuperadmins', 'activeAdmins', 'activeClientadmins', 'activeEmployees', 'totalActive', 'superadminCount', 'adminCount', 'clientadminCount', 'employeeCount', 'clientMap'));
    }

    //
    // End User Management Routes
    // =========================================



}
