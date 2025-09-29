<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Superadmin;
use App\Models\Clientadmin;
use App\Models\User;
use App\Models\Client;
use App\Models\TimeOff;
use App\Models\WorkforceWatson;
use Carbon\Carbon;
use App\Models\AdminApplicationsAccess;


class AdminController extends Controller
{

    // =========================================
    // Admin Main Routes
    // 

    /**
     * Display the Admin dashboard.
     */

    public function dashboard()
    {
        $adminId = auth('admin')->id();
        $access = AdminApplicationsAccess::where('adminid', $adminId)->first();
        // Fetch Application Access Permissions
        $totalAppAccess = 0;
        if ($access) {
            for ($i = 1; $i <= 10; $i++) {
                $col = 'app_' . $i;
                $totalAppAccess += (int) ($access->{$col} ?? 0);
            }
        }

        // Fetch active users
        $activeAdmins = Admin::where('isactive', 1)->get();
        $activeClientadmins = Clientadmin::where('isactive', 1)->get();
        $activeEmployees = User::where('isactive', 1)->get();

        // Count active users
        $adminCount = $activeAdmins->count();
        $clientadminCount = $activeClientadmins->count();
        $employeeCount = $activeEmployees->count();
        $totalActive = $adminCount + $clientadminCount + $employeeCount;

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
            'admin.dashboard',
            compact(
                'activeAdmins',              // List of active admins
                'activeClientadmins',        // List of active client admins
                'activeEmployees',           // List of active employees
                'totalActive',               // Total number of active superadmins, admins, and client admins
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
                'timeOffData',               // Monthly counts for TimeOff chart
                'totalAppAccess'             // Total application access permissions for this admin
            )
        );
    }
    /**
     * Display the Apps.
     */
    public function apps()
    {
        return view('admin.apps');
    }

    // =========================================
    // Admin Account Management

    /**
     * Edit Admin route
     */
    public function editprofile(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
        } catch (\Exception $e) {
            // Show user-friendly error page for invalid encrypted ID
            return redirect()->back();
        }
        $admin = Admin::find(auth('admin')->id());
        return view('admin.profile', compact('admin'));
    }

    /**
     * Edit Admin Submit
     */
    public function editprofile_submit(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'middlename' => 'nullable',
            'lastname' => 'required',
            'email' => 'required|email',
            'contact' => 'required|numeric',
        ]);

        $id = decrypt($request->id);
        $admin = Admin::find(auth('admin')->id());

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found.');
        }

        $admin->update([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'contact' => $request->contact,
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Upload Admin Profile Picture
     */
    public function uploadprofilepicture(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $id = decrypt($request->id);
        $admin = Admin::find(auth('admin')->id());

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found.');
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = 'admin_' . $admin->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/users/admin/');
            $image->move($path, $name);

            // Optionally delete old photo
            if ($admin->profile_picture && file_exists($path . $admin->profile_picture)) {
                @unlink($path . $admin->profile_picture);
            }

            $admin->photo = $name;
            $admin->save();

            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }
        return redirect()->back()->with('success', 'Profile picture updated successfully.')->with('id', $admin->id);
        return redirect()->back()->with('error', 'No photo uploaded.');
    }

    /**
     * Change Admin Password
     */
    public function changepassword(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'oldpassword' => 'required',
            'newpassword' => 'required',
            'confirmpassword' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Please fill all fields')->withInput();
        }

        $id = decrypt($request->id);
        $admin = Admin::find(auth('admin')->id());

        if (!$admin || !Hash::check($request->oldpassword, $admin->password)) {
            return redirect()->back()->with('error', 'Old password is incorrect.');
        }

        if ($request->newpassword !== $request->confirmpassword) {
            return redirect()->back()->with('error', 'Passwords did not match.');
        }

        $admin->password = Hash::make($request->newpassword);
        $admin->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    // 
    // End Admin Account Management
    // =========================================

}
