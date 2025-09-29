<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use App\Models\User;
use App\Models\Client;
use App\Models\TimeOff;
use App\Models\WorkforceWatson;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;


class SuperadminController extends Controller
{

    // =========================================
    // Superadmin Main Routes
    // 
    
    /**
     * Display the Superadmin dashboard.
     */
    public function dashboard()
    {
        // Fetch active users
        $activeSuperadmins = Superadmin::where('isactive', 1)->get();
        $activeAdmins = Admin::where('isactive', 1)->get();
        $activeClientadmins = Clientadmin::where('isactive', 1)->get();
        $activeEmployees = User::where('isactive', 1)->get();

        // Count active users
        $superadminCount = $activeSuperadmins->count();
        $adminCount = $activeAdmins->count();
        $clientadminCount = $activeClientadmins->count();
        $employeeCount = $activeEmployees->count();
        $totalActive = $superadminCount + $adminCount + $clientadminCount;

        // Fetch clients
        $clients = Client::select('id', 'clientshortname')->get();
        // Build a map of clientid => clientname for fast lookup
        $clientMap = $clients->pluck('clientshortname', 'id');
        //Count active clients
        $clientCount = Client::where('isactive', 1)->count();

        // Fetch TimeOff and WatsonsWorkforce
        $timeOff = TimeOff::all();
        $WatsonsWorkforce = WorkforceWatson::all();

        // Pending Count
        $timeOffPending = TimeOff::where('leavestatus', 'pending')->count();
        $WatsonsWorkforcePending = WorkforceWatson::where('status', 'pending')->count();
        $totalPending = $timeOffPending + $WatsonsWorkforcePending;

        // Simple monthly aggregation for WatsonsWorkforce (current year)
        $year = Carbon::now()->year;
        $watsonCounts = array_fill(1, 12, 0);
        $watsonRows = WorkforceWatson::whereNotNull('requestdate')->get(['requestdate']);
        foreach ($watsonRows as $r) {
            try {
                $dt = Carbon::parse($r->requestdate);
            } catch (\Exception $e) {
                continue;
            }
            if ($dt->year === (int) $year) {
                $watsonCounts[(int)$dt->month]++;
            }
        }
        $watsonLabels = [];
        $watsonData = [];
        for ($m = 1; $m <= 12; $m++) {
            $watsonLabels[] = Carbon::createFromDate($year, $m, 1)->format('M');
            $watsonData[] = (int) ($watsonCounts[$m] ?? 0);
        }

        // Simple monthly aggregation for TimeOff (current year) using leaverequestdate
        $year = Carbon::now()->year;
        $timeOffCounts = array_fill(1, 12, 0);
        $timeOffRows = TimeOff::whereNotNull('leaverequestdate')->get(['leaverequestdate']);
        foreach ($timeOffRows as $r) {
            try {
            $dt = Carbon::parse($r->leaverequestdate);
            } catch (\Exception $e) {
            continue;
            }
            if ($dt->year === (int) $year) {
            $timeOffCounts[(int)$dt->month]++;
            }
        }
        $timeOffLabels = [];
        $timeOffData = [];
        for ($m = 1; $m <= 12; $m++) {
            $timeOffLabels[] = Carbon::createFromDate($year, $m, 1)->format('M');
            $timeOffData[] = (int) ($timeOffCounts[$m] ?? 0);
        }
        // Return the dashboard view with all required data
        return view(
            'superadmin.dashboard',
            compact(
                'activeSuperadmins',         // List of active superadmins
                'activeAdmins',              // List of active admins
                'activeClientadmins',        // List of active client admins
                'activeEmployees',           // List of active employees
                'totalActive',               // Total number of active superadmins, admins, and client admins
                'superadminCount',           // Count of active superadmins
                'adminCount',                // Count of active admins
                'clientadminCount',          // Count of active client admins
                'employeeCount',             // Count of active employees
                'clientMap',                 // Map of client IDs to client short names
                'clientCount',               // Count of active clients
                'timeOff',                   // All time off records
                'WatsonsWorkforce',          // All WatsonsWorkforce records
                'timeOffPending',            // Count of pending time off requests
                'WatsonsWorkforcePending',   // Count of pending WatsonsWorkforce requests
                'totalPending',              // Total pending requests
                'watsonLabels',              // Monthly labels for WatsonsWorkforce chart
                'watsonData',                // Monthly counts for WatsonsWorkforce chart
                'timeOffLabels',             // Monthly labels for TimeOff chart
                'timeOffData'                // Monthly counts for TimeOff chart
            )
        );
    }

    // =========================================
    // Superadmin Account Management

    /**
     * Edit Superadmin route
     */
    public function profile(Request $request, $id)
    {
        $superadmin = Superadmin::find($id);
        return view('superadmin.profile', compact('superadmin'));
    }

    /**
     * Edit Superadmin Submit
     */
    public function profile_submit(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'contact' => 'required|numeric',
        ]);

        $superadmin = Superadmin::find($request->id);

        $superadmin->update([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'contact' => $request->contact,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload Superadmin Profile Picture
     */
    public function profile_uploadprofilepicture(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $superadmin = Superadmin::find($request->id);

        if (!$superadmin) {
            return redirect()->back()->with('error', 'Superadmin not found.');
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = 'superadmin_' . $superadmin->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/users/superadmin/');
            $image->move($path, $name);

            // Optionally delete old photo
            if ($superadmin->profile_picture && file_exists($path . $superadmin->profile_picture)) {
                @unlink($path . $superadmin->profile_picture);
            }

            $superadmin->photo = $name;
            $superadmin->save();

            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }
        return redirect()->back()->with('success', 'Profile picture updated successfully.')->with('id', $superadmin->id);
        return redirect()->back()->with('error', 'No photo uploaded.');
    }

    /**
     * Change Superadmin Password
     */
    public function changepassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'oldpassword' => 'required',
            'newpassword' => 'required',
            'confirmpassword' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please fill all fields')->withInput();
        }

        $superadmin = Superadmin::find($request->id);

        if (!$superadmin || !Hash::check($request->oldpassword, $superadmin->password)) {
            return redirect()->back()->with('error', 'Old password is incorrect.');
        }

        if ($request->newpassword !== $request->confirmpassword) {
            return redirect()->back()->with('error', 'Passwords did not match.');
        }

        $superadmin->password = Hash::make($request->newpassword);
        $superadmin->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    // 
    // End Superadmin Account Management
    // =========================================



    // TEST APPS ONLY
    public function qrcode()
    {
        return view('superadmin.qrcode');
    }
}
