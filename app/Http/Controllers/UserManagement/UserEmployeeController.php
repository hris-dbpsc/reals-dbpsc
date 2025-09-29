<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Branches;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserEmployeeController extends Controller
{
    // =========================================
    // User Employee Management Routes Start
    //

    /**
     * View User Employee route
     */
    public function useremployee()
    {
        // Build a query that can order by a column on the related branches table
        $usersQuery = User::select('users.*')
            ->leftJoin('branches', 'users.branchname', '=', 'branches.branchname')
            ->where('users.role', 'user')
            ->with(['branch', 'client']) // keep eager loading for relations used in the view
            ->orderBy('users.isactive', 'desc')
            ->orderBy('branches.branchname', 'asc')
            ->orderBy('users.lastname', 'asc');

        $clients = Client::orderBy('clientshortname')->get();
        $branches = Branches::orderBy('branchname')->get();
        $users = $usersQuery->get();

        // Check user role to determine which view to return
        if (Auth::guard('superadmin')->check()) {
            return view('superadmin.usermanagement.useremployee', compact('users', 'clients', 'branches'));
        } elseif (Auth::guard('admin')->check()) {
            return view('admin.usermanagement.useremployee', compact('users', 'clients', 'branches'));
        } elseif (Auth::guard('clientadmin')->check()) {
            return view('clientadmin.usermanagement.useremployee', compact('users', 'clients', 'branches'));
        }
    }

    /**
     * View User Employee route
     */
    public function activateemployee($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $user->isactive = 1;
        $user->save();
        return redirect()->back()->with('success', 'Employee activated successfully.');
    }

    /**
     * View User Employee route
     */
    public function deactivateemployee($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }
        $user->isactive = 0;
        $user->save();
        return redirect()->back()->with('success', 'Employee deactivated successfully.');
    }

    /**
     * Import User Employee
     */
    public function importemployee(Request $request)
    {
        // Handle CSV import logic here
        $request->validate([
            'csv_file' => 'required|max:10000',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('csv_file'));
            return redirect()->route('superadmin_useremployee')->with('success', 'Employees imported successfully.');
        } catch (\Exception $e) {
            return redirect()->route('superadmin_useremployee')->with('import_error', $e->getMessage());
        }
    }

    /**
     * Export User Employees
     */
    public function exportemployee(Request $request)
    {
        $clientshortname = $request->input('clientname');
        if ($clientshortname === 'ALL CLIENTS' || empty($clientshortname)) {
            $clientshortname = null; // Pass null to export all employees
        }
        $filename = ($clientshortname ? $clientshortname . '_employees_' : 'ALL_employees_') . date('YmdHis') . '.xlsx';
        return Excel::download(new UsersExport($clientshortname), $filename);
    }

    /**
     * Export User Employees
     */
    public function truncateemployeetable(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = Auth::guard('superadmin')->user();
        if (!$user || $user->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->with('error', 'Incorrect password.');
        }

        // Only remove employee (role = 'user') records to avoid deleting admin/superadmin accounts
        DB::transaction(function () {
            DB::table('users')->where('role', 'user')->delete();
        });

        return redirect()->route('superadmin_useremployee')->with('success', 'All employee records deleted successfully.');
    }

    //
    // End User Employee Management Routes
    // =========================================
}
