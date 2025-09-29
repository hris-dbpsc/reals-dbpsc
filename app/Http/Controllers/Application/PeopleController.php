<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdminApplicationsAccess;
use App\Models\ApplicationsAccess;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class PeopleController extends Controller
{


    // =========================================
    // People for Superadmin Routes
    //

    /**
     *  Superadmin People View route
     */
    public function superadmin_people()
    {
        return view('superadmin.apps.people.people');
    }

    public function superadmin_peopleview(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
        } catch (\Exception $e) {
            // Show user-friendly error page for invalid encrypted ID
            return redirect()->back();
        }
        $user = User::find($decryptedId);

        return view('superadmin.apps.people.peopleview', compact('user'));
    }

    /**
     *  Admin People Searhch Users (AJAX) route
     */
    public function superadmin_searchUsers(Request $request)
    {
        $q = $request->query('q', '');
        if (empty($q)) {
            return response()->json([]);
        }

        $users = User::where('role', 'user')
            ->where(function ($query) use ($q) {
                if (stripos($q, 'Region ') === 0) {
                    $regionSearch = trim(substr($q, strlen('Region ')));
                    $query->where('region', 'like', "%$regionSearch%");
                } else {
                    $query->where('firstname', 'like', "%$q%")
                        ->orWhere('lastname', 'like', "%$q%")
                        ->orWhere('middlename', 'like', "%$q%")
                        ->orWhere('employeenumber', 'like', "%$q%")
                        ->orWhere('clientid', 'like', "%$q%")
                        ->orWhere('branchname', 'like', "%$q%")
                        ->orWhere('position', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('contact', 'like', "%$q%")
                        ->orWhere('region', 'like', "%$q%");
                }
            })
            ->limit(20)
            ->get();

        $results = $users->map(function ($user) {
            $employeenumber = $user->employeenumber ?? '';
            $userPhotoPath = 'assets/users/users/' . $employeenumber . '.jpg';
            $userPhotoExists = !empty($employeenumber) && file_exists(public_path($userPhotoPath));
            // Get clientname from Client model using clientid
            $clientname = '';
            if (!empty($user->clientid)) {
                $clientname = Client::where('id', $user->clientid)->value('clientname') ?? '';
            }
            return [
                'id' => encrypt($user->id),
                'photo_url' => $userPhotoExists
                    ? asset($userPhotoPath)
                    : asset('assets/assets/img/demo/user-placeholder.svg'),
                'clientid' => $user->clientid ?? '',
                'clientname' => $clientname,
                'branchname' => $user->branchname ?? '',
                'employeenumber' => $employeenumber,
                'position' => $user->position ?? '',
                'lastname' => $user->lastname ?? '',
                'firstname' => $user->firstname ?? '',
                'middlename' => $user->middlename ?? '',
                'region' => $user->region ?? '',
            ];
        });

        return response()->json($results);
    }


    //
    // End People for Superadmin Routes
    // =========================================


    // =========================================
    // People for Admin Routes
    //

    /**
     *  Admin People View route
     */

    public function admin_people()
    {
        $adminId = Auth::guard('admin')->id(); // or Auth::user()->id if using default guard
        $access = AdminApplicationsAccess::where('adminid', $adminId)->first();

        if (!$access || $access->app_1 != 1) {
            abort(403, 'Unauthorized access to app people.');
        }
        return view('admin.apps.people.people');
    }

    public function admin_peopleview(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
        } catch (\Exception $e) {
            // Show user-friendly error page for invalid encrypted ID
            return redirect()->back();
        }
        $adminId = Auth::guard('admin')->id();
        $access = AdminApplicationsAccess::where('adminid', $adminId)->first();

        $user = User::find($decryptedId);
        if (!$access || $access->app_1 != 1) {
            abort(403, 'Unauthorized access to app people.');
        }
        
        return view('admin.apps.people.peopleview', compact('user'));
    }

    /**
     *  Admin People Searhch Users (AJAX) route
     */
    public function admin_searchUsers(Request $request)
    {
        $q = $request->query('q', '');
        if (empty($q)) {
            return response()->json([]);
        }

        $users = User::where('role', 'user')
            ->where(function ($query) use ($q) {
                if (stripos($q, 'Region ') === 0) {
                    $regionSearch = trim(substr($q, strlen('Region ')));
                    $query->where('region', 'like', "%$regionSearch%");
                } else {
                    $query->where('firstname', 'like', "%$q%")
                        ->orWhere('lastname', 'like', "%$q%")
                        ->orWhere('middlename', 'like', "%$q%")
                        ->orWhere('employeenumber', 'like', "%$q%")
                        ->orWhere('clientid', 'like', "%$q%")
                        ->orWhere('branchname', 'like', "%$q%")
                        ->orWhere('position', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('contact', 'like', "%$q%")
                        ->orWhere('region', 'like', "%$q%");
                }
            })
            ->limit(20)
            ->get();

        $results = $users->map(function ($user) {
            $employeenumber = $user->employeenumber ?? '';
            $userPhotoPath = 'assets/users/users/' . $employeenumber . '.jpg';
            $userPhotoExists = !empty($employeenumber) && file_exists(public_path($userPhotoPath));
            return [
                'id' => encrypt($user->id),
                'photo_url' => $userPhotoExists
                    ? asset($userPhotoPath)
                    : asset('assets/assets/img/demo/user-placeholder.svg'),
                'clientid' => $user->clientid ?? '',
                'branchname' => $user->branchname ?? '',
                'employeenumber' => $employeenumber,
                'position' => $user->position ?? '',
                'lastname' => $user->lastname ?? '',
                'firstname' => $user->firstname ?? '',
                'middlename' => $user->middlename ?? '',
                'region' => $user->region ?? '',
            ];
        });

        return response()->json($results);
    }
    //
    // End People for Admin Routes
    // =========================================


    // =========================================
    // People for Admin Routes
    //

    /**
     *  ClientAdmin People View route
     */

    public function clientadmin_people()
    {
        $clientId = Auth::guard('clientadmin')->user()->clientid; // get clientid from authenticated clientadmin user
        $access = ApplicationsAccess::where('clientid', $clientId)->first();

        if (!$access || $access->app_1 != 1) {
            abort(403, 'Unauthorized access to app people.');
        }

        return view('clientadmin.apps.people.people');
    }

    public function clientadmin_peopleview(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
        } catch (\Exception $e) {
            // Show user-friendly error page for invalid encrypted ID
            return redirect()->back();
        }
        $clientId = Auth::guard('clientadmin')->user()->clientid; // get clientid from authenticated clientadmin user
        $access = ApplicationsAccess::where('clientid', $clientId)->first();

        $user = User::find($decryptedId);

        if (!$access || $access->app_1 != 1) {
            abort(403, 'Unauthorized access to app people.');
        }

        return view('clientadmin.apps.people.peopleview', compact('user'));
    }

    /**
     *  Client Admin People Searhch Users (AJAX) route
     */
    public function clientadmin_searchUsers(Request $request)
    {
        $q = $request->query('q', '');
        if (empty($q)) {
            return response()->json([]);
        }

        // Get the clientadmin's clientid
        $clientId = Auth::guard('clientadmin')->user()->clientid;

        $users = User::where('role', 'user')
            ->where('clientid', $clientId)
            ->where(function ($query) use ($q) {
                if (stripos($q, 'Region ') === 0) {
                    $regionSearch = trim(substr($q, strlen('Region ')));
                    $query->where('region', 'like', "%$regionSearch%");
                } else {
                    $query->where('firstname', 'like', "%$q%")
                        ->orWhere('lastname', 'like', "%$q%")
                        ->orWhere('middlename', 'like', "%$q%")
                        ->orWhere('employeenumber', 'like', "%$q%")
                        ->orWhere('clientid', 'like', "%$q%")
                        ->orWhere('branchname', 'like', "%$q%")
                        ->orWhere('position', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('contact', 'like', "%$q%")
                        ->orWhere('region', 'like', "%$q%");
                }
            })
            ->limit(20)
            ->get();

        $results = $users->map(function ($user) {
            $employeenumber = $user->employeenumber ?? '';
            $userPhotoPath = 'assets/users/users/' . $employeenumber . '.jpg';
            $userPhotoExists = !empty($employeenumber) && file_exists(public_path($userPhotoPath));
            // Get clientname from Client model using clientid
            $clientname = '';
            if (!empty($user->clientid)) {
                $clientname = Client::where('id', $user->clientid)->value('clientname') ?? '';
            }
            return [
                'id' => encrypt($user->id),
                'photo_url' => $userPhotoExists
                    ? asset($userPhotoPath)
                    : asset('assets/assets/img/demo/user-placeholder.svg'),
                'clientid' => $user->clientid ?? '',
                'clientname' => $clientname,
                'branchname' => $user->branchname ?? '',
                'employeenumber' => $employeenumber,
                'position' => $user->position ?? '',
                'lastname' => $user->lastname ?? '',
                'firstname' => $user->firstname ?? '',
                'middlename' => $user->middlename ?? '',
                'region' => $user->region ?? '',
            ];
        });

        return response()->json($results);
    }


    //
    // End People for ClientAdmin Routes
    // =========================================

}
