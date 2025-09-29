<?php

namespace App\Http\Controllers\Clientadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Clientadmin;
use App\Models\User;
use App\Models\TimeOff;
use App\Models\WorkforceWatson;
use App\Models\ApplicationsAccess;
use App\Models\Branches;
use Carbon\Carbon;

class ClientadminController extends Controller
{
    // =========================================
    // Client Admin Main Routes
    //

    /**
     * Display the Client Admin dashboard.
     */
    public function dashboard()
    {
        $clientadmin = auth('clientadmin')->user();
        $clientid = $clientadmin ? $clientadmin->clientid : null;
        $access = ApplicationsAccess::where('clientid', $clientid)->first();
        // Fetch Application Access Permissions
        $totalAppAccess = 0;
        if ($access) {
            for ($i = 1; $i <= 10; $i++) {
                $col = 'app_' . $i;
                $totalAppAccess += (int) ($access->{$col} ?? 0);
            }
        }

        // Fetch active users
        $activeClientadmins = Clientadmin::where('isactive', 1)
            ->where('clientid', $clientid)
            ->get();
        $activeEmployees = User::where('isactive', 1)
            ->where('clientid', $clientid)
            ->get();

        // Count active users
        $clientadminCount = $activeClientadmins->count();
        $employeeCount = $activeEmployees->count();

        // Fetch Branches
        $branches = Branches::where('clientid', $clientid)
            ->where('isactive', 1)
            ->get();
        $branchesCount = $branches->count();

        // Fetch TimeOff and WatsonsWorkforce\
        $WatsonsWorkforce = WorkforceWatson::all();

        // Pending Count
        $WatsonsWorkforcePending = WorkforceWatson::where('status', 'pending')->count();

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
            'clientadmin.dashboard',
            compact(
                'activeClientadmins',        // List of active client admins
                'activeEmployees',           // List of active employees
                'clientadminCount',          // Count of active client admins
                'employeeCount',             // Count of active employees
                'WatsonsWorkforce',          // All WatsonsWorkforce records
                'WatsonsWorkforcePending',   // Count of pending WatsonsWorkforce requests
                'watsonLabels',              // Monthly labels for WatsonsWorkforce chart
                'watsonData',                // Monthly counts for WatsonsWorkforce chart
                'timeOffLabels',             // Monthly labels for TimeOff chart
                'timeOffData',               // Monthly counts for TimeOff chart
                'totalAppAccess',             // Total application access permissions for this admin
                'branchesCount',               // Total branches for this client admin
            )
        );
    }

    /**
     * Display the Client Admin dashboard.
     */
    public function apps()
    {
        return view('clientadmin.apps');
    }


    // =========================================
    // Client Admin Account Management

    /**
     * Edit Client Admin route
     */
    public function editprofile(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
        } catch (\Exception $e) {
            // Show user-friendly error page for invalid encrypted ID
            return redirect()->back();
        }
        $clientadmin = Clientadmin::find(auth('clientadmin')->user()->id);
        return view('clientadmin.profile', compact('clientadmin'));
    }

    /**
     * Edit Client Admin Submit
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
        $clientadmin = Clientadmin::find(auth('clientadmin')->user()->id);

        if (!$clientadmin) {
            return redirect()->back()->with('error', 'Client Admin not found.');
        }

        $clientadmin->update([
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
        $clientadmin = Clientadmin::find(auth('clientadmin')->user()->id);

        if (!$clientadmin) {
            return redirect()->back()->with('error', 'Client Admin not found.');
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = 'clientadmin_' . $clientadmin->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/users/clientadmin/');
            $image->move($path, $name);

            // Optionally delete old photo
            if ($clientadmin->profile_picture && file_exists($path . $clientadmin->profile_picture)) {
                @unlink($path . $clientadmin->profile_picture);
            }

            $clientadmin->photo = $name;
            $clientadmin->save();

            return redirect()->back()->with('success', 'Profile picture updated successfully.');
        }
        return redirect()->back()->with('success', 'Profile picture updated successfully.')->with('id', $clientadmin->id);
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
        $clientadmin = Clientadmin::find(auth('clientadmin')->user()->id);

        if (!$clientadmin || !Hash::check($request->oldpassword, $clientadmin->password)) {
            return redirect()->back()->with('error', 'Old password is incorrect.');
        }

        if ($request->newpassword !== $request->confirmpassword) {
            return redirect()->back()->with('error', 'Passwords did not match.');
        }

        $clientadmin->password = Hash::make($request->newpassword);
        $clientadmin->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    // 
    // End Admin Account Management
    // =========================================
}
