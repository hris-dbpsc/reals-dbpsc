<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api_Keys;
use App\Models\User;
use App\Models\Client;
use App\Models\Branches;
use Illuminate\Support\Facades\Auth;

class LocatorController extends Controller
{

    // =========================================
    // Locator for Superadmin Routes
    //

    /**
     *  App Locator View route
     */
    public function superadmin_locator()
    {
        $branches = Branches::get(); // or your query to get branches
        return view('superadmin.apps.locator.locator', compact('branches'));
    }

    /**
     *  App Locator View route
     */
    public function superadmin_locatorclient()
    {
        $branches = Branches::get(); // or your query to get branches
        $clients = Client::get(); // or your query to get clients
        $users = User::where('role', 'user')->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        $activeClients = $clients->where('isactive', 1)->sortBy('clientshortname'); // get active clients sorted by shortname
        return view('superadmin.apps.locator.locatorclient', compact('clients', 'branches', 'users', 'apiKey', 'activeClients'));
    }

    /**
     *  App Locator Branch View route
     */
    public function superadmin_locatorbranch()
    {
        $branches = Branches::leftJoin('clients', 'branches.clientid', '=', 'clients.id')
            ->select('branches.*', 'clients.clientname', 'clients.clientshortname', 'clients.id as clientid')
            ->get();

        $clients = Client::get();
        $users = User::where('role', 'user')->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first();

        // Only pass active branches sorted by clientname to the view
        $activeBranches = $branches->where('isactive', 1)->sortBy('clientname');
        $activeClients = $clients->where('isactive', 1)->sortBy('clientname');

        // Check if a client has active branches
        // Usage: $hasBranches = $branches->where('clientid', $client->id)->where('isactive', 1)->count() > 0;

        return view('superadmin.apps.locator.locatorbranch', compact('activeBranches', 'clients', 'users', 'apiKey', 'activeClients', 'branches'));
    }

    /**
     *  App Locator Employee Data View route
     */
    public function superadmin_locatordata(Request $request)
    {
        $clientId = $request->query('client');
        $branch = $request->query('branch');
        $branches = Branches::get();
        $clients = Client::get();
        $users = User::where('role', 'user')->get();

        // Pass both variables to the view
        return view('superadmin.apps.locator.locatordata', compact('branches', 'clients', 'users', 'clientId', 'branch'));
    }


    /**
     *  App Locator Employee View route
     */
    public function superadmin_locatoremployee(Request $request)
    {
        $clientId = $request->query('clientid');
        $branches = $clientId ? Branches::where('clientid', $clientId)->get() : Branches::get();
        $clients = Client::get();
        $users = $clientId
            ? User::where('role', 'user')->where('clientid', $clientId)->get()
            : User::where('role', 'user')->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first();
        $activeClients = $clients->where('isactive', 1)->sortBy('clientshortname');
        return view('superadmin.apps.locator.locatoremployee', compact('branches', 'clients', 'users', 'apiKey', 'activeClients', 'clientId'));
    }

    /**
     *  App Locator Employee Map View route
     */
    public function superadmin_locatoremployeemap(Request $request, $id)
    {
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        // Get the user by ID
        $user = User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        // Join with branches to get geolocation
        $branch = Branches::where('branchname', $user->branchname)->first();
        $geolocation = $branch ? $branch->branchgeolocation : null;

        // Pass user and geolocation to the view
        return view('superadmin.apps.locator.locatoremployeemap', [
            'id' => $id,
            'user' => $user,
            'branch' => $branch,
            'geolocation' => $geolocation,
            'apiKey' => $apiKey,
        ]);
    }

    /**
     *  App Locator Employee Search Engine (AJAX - CAN BE REUSED IN OTHER PAGES)
     */
    public function superadmin_locator_searchUsers(Request $request)
    {
        $q = $request->query('q', '');
        if (empty($q)) {
            return response()->json([]);
        }

        $users = User::where('role', 'user')
            ->where(function ($query) use ($q) {
                // If the search query starts with "Region ", search only in the 'region' column
                if (stripos($q, 'Region ') === 0) {
                    $regionSearch = trim(substr($q, strlen('Region ')));
                    $query->where('region', 'like', "%$regionSearch%");
                } else {
                    $query->where('firstname', 'like', "%$q%")
                        ->orWhere('lastname', 'like', "%$q%")
                        ->orWhere('middlename', 'like', "%$q%")
                        ->orWhere('employeenumber', 'like', "%$q%")
                        ->orWhere('branchname', 'like', "%$q%")
                        ->orWhere('position', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('contact', 'like', "%$q%")
                        ->orWhere('region', 'like', "%$q%");
                    // If query is numeric, also search by clientid
                    if (is_numeric($q)) {
                        $query->orWhere('clientid', $q);
                    }
                }
            })
            ->limit(50)
            ->get();

        $results = $users->map(function ($user) {
            // Fetch readable clientname from Client model
            $client = Client::find($user->clientid);
            return [
                'id' => $user->id,
                'photo_url' => (
                    $user->employeenumber && file_exists(public_path('assets/users/users/' . $user->employeenumber . '.jpg'))
                )
                    ? asset('assets/users/users/' . $user->employeenumber . '.jpg')
                    : asset('assets/assets/img/demo/user-placeholder.svg'),
                'clientid' => $user->clientid,
                'clientname' => $client ? $client->clientname : '',
                'branchname' => $user->branchname,
                'employeenumber' => $user->employeenumber,
                'position' => $user->position,
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'middlename' => $user->middlename,
                'region' => $user->region,
            ];
        });

        return response()->json($results);
    }

    //
    // End Locator for Superadmin Routes
    // =========================================





    // =========================================
    // Locator for  Admin Routes
    //

    /**
     *  App Locator view route
     */
    public function admin_locator()
    {
        $branches = Branches::get(); // or your query to get branches
        return view('admin.apps.locator.locator', compact('branches'));
    }

    /**
     *  App Locator Client view route
     */
    public function admin_locatorclient()
    {
        $branches = Branches::get(); // or your query to get branches
        $clients = Client::get(); // or your query to get clients
        $users = User::where('role', 'user')->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        $activeClients = $clients->where('isactive', 1)->sortBy('clientshortname'); // get active clients sorted by shortname
        return view('admin.apps.locator.locatorclient', compact('clients', 'branches', 'users', 'apiKey', 'activeClients'));
    }


    /**
     *  App Locator Branch View route
     */
    public function admin_locatorbranch()
    {
        $branches = Branches::leftJoin('clients', 'branches.clientid', '=', 'clients.id')
            ->select('branches.*', 'clients.clientname', 'clients.clientshortname')
            ->get();

        $clients = Client::get();
        $users = User::where('role', 'user')->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first();

        // Only pass active branches sorted by clientname to the view
        $activeBranches = $branches->where('isactive', 1)->sortBy('clientname');
        $activeClients = $clients->where('isactive', 1)->sortBy('clientname');

        // Check if a client has active branches
        // Usage: $hasBranches = $branches->where('clientid', $client->id)->where('isactive', 1)->count() > 0;

        return view('admin.apps.locator.locatorbranch', compact('activeBranches', 'clients', 'users', 'apiKey', 'activeClients', 'branches'));
    }

    /**
     *  App Locator Employee View route
     */
    public function admin_locatoremployee()
    {
        $branches = Branches::get(); // get all branches
        $clients = Client::get(); // get all clients
        $users = User::where('role', 'user')->get(); // get all users with role 'user'
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        $activeClients = $clients->where('isactive', 1)->sortBy('clientshortname'); // get active clients sorted by shortname
        return view('admin.apps.locator.locatoremployee', compact('branches', 'clients', 'users', 'apiKey', 'activeClients'));
    }


    /**
     *  App Locator Employee Data View route
     */
    public function admin_locatordata(Request $request)
    {
        $clientId = $request->query('client');
        $branch = $request->query('branch');
        $branches = Branches::get();
        $clients = Client::get();
        $users = User::where('role', 'user')->get();

        // Pass both variables to the view
        return view('admin.apps.locator.locatordata', compact('branches', 'clients', 'users', 'clientId', 'branch'));
    }


    /**
     *  App Locator Employee Map View route
     */
    public function admin_locatoremployeemap(Request $request, $id)
    {
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        // Get the user by ID
        $user = User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        // Join with branches to get geolocation
        $branch = Branches::where('branchname', $user->branchname)->first();
        $geolocation = $branch ? $branch->branchgeolocation : null;

        // Pass user and geolocation to the view
        return view('admin.apps.locator.locatoremployeemap', [
            'id' => $id,
            'user' => $user,
            'branch' => $branch,
            'geolocation' => $geolocation,
            'apiKey' => $apiKey,
        ]);
    }

    /**
     *  App Locator Employee Search Engine (AJAX - CAN BE REUSED IN OTHER PAGES)
     */
    public function admin_searchUsers(Request $request)
    {
        $q = $request->query('q', '');
        if (empty($q)) {
            return response()->json([]);
        }

        $users = User::where('role', 'user')
            ->where(function ($query) use ($q) {
                // If the search query starts with "Region ", search only in the 'region' column
                if (stripos($q, 'Region ') === 0) {
                    $regionSearch = trim(substr($q, strlen('Region ')));
                    $query->where('region', 'like', "%$regionSearch%");
                } else {
                    $query->where('firstname', 'like', "%$q%")
                        ->orWhere('lastname', 'like', "%$q%")
                        ->orWhere('middlename', 'like', "%$q%")
                        ->orWhere('employeenumber', 'like', "%$q%")
                        ->orWhere('branchname', 'like', "%$q%")
                        ->orWhere('position', 'like', "%$q%")
                        ->orWhere('email', 'like', "%$q%")
                        ->orWhere('contact', 'like', "%$q%")
                        ->orWhere('region', 'like', "%$q%");
                }
            })
            ->limit(50)
            ->get();

        $results = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'photo_url' => (
                    $user->employeenumber && file_exists(public_path('assets/users/users/' . $user->employeenumber . '.jpg'))
                )
                    ? asset('assets/users/users/' . $user->employeenumber . '.jpg')
                    : asset('assets/assets/img/demo/user-placeholder.svg'),
                'clientname' => $user->clientname,
                'branchname' => $user->branchname,
                'employeenumber' => $user->employeenumber,
                'position' => $user->position,
                'lastname' => $user->lastname,
                'firstname' => $user->firstname,
                'middlename' => $user->middlename,
                'region' => $user->region,
            ];
        });

        return response()->json($results);
    }
    //
    // End Locator for Client Admin Routes
    // =========================================





    // =========================================
    // Locator for Client Admin Routes
    //

    /**
     *  App Locator View route
     */
    public function clientadmin_locator()
    {
        $branches = Branches::get(); // or your query to get branches
        return view('clientadmin.apps.locator.locator', compact('branches'));
    }

    /**
     *  App Locator Branch View route
     */
    public function clientadmin_locatorbranch()
    {
        $clientadmin = Auth::guard('clientadmin')->user();
        if (!$clientadmin) {
            abort(403, 'Unauthorized');
        }
        $clientId = $clientadmin->clientid;

        // Get the client name from clientadmins table
        $clientName = $clientadmin->clientname;

        $branches = Branches::leftJoin('clients', 'branches.clientid', '=', 'clients.id')
            ->select('branches.*', 'clients.clientname', 'clients.clientshortname')
            ->where('branches.clientid', $clientId)
            ->where('branches.isactive', 1)
            ->orderBy('clients.clientname')
            ->get();

        // Filter users by clientname from clientadmins table
        $users = User::where('role', 'user')->where('clientid', $clientId)->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first();

        return view('clientadmin.apps.locator.locatorbranch', compact('branches', 'users', 'apiKey'));
    }

    /**
     *  App Locator Employee View route
     */
    public function clientadmin_locatoremployee()
    {
        $clientadmin = Auth::guard('clientadmin')->user();
        if (!$clientadmin) {
            abort(403, 'Unauthorized');
        }
        $clientId = $clientadmin->clientid;
        $clientName = $clientadmin->clientname;

        // Get branches for this client
        $branches = Branches::where('clientid', $clientId)->get();
        // Get users for this client
        $users = User::where('role', 'user')->where('clientid', $clientId)->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first();

        return view('clientadmin.apps.locator.locatoremployee', compact('branches', 'users', 'apiKey'));
    }


    /**
     *  App Locator Employee Data View route
     */
    public function clientadmin_locatordata(Request $request)
    {
        $clientId = $request->query('client');
        $branch = $request->query('branch');
        $branches = Branches::where('clientid', $clientId)->get();
        $clients = Client::get();
        $users = User::where('role', 'user')
            ->when($branch, function ($query) use ($branch) {
                return $query->where('branchname', $branch);
            })
            ->when($clientId, function ($query) use ($clientId) {
                return $query->where('clientid', $clientId);
            })
            ->get();
        return view('clientadmin.apps.locator.locatordata', compact('branches', 'clients', 'users', 'clientId', 'branch'));
    }


    /**
     *  App Locator Employee Map View route
     */
    public function clientadmin_locatoremployeemap(Request $request, $id)
    {

        try {
            $decryptedId = decrypt($id);
        } catch (\Exception $e) {
            // Show user-friendly error page for invalid encrypted ID
            return redirect()->back();
        }
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first();
        $user = User::find($decryptedId);
        $clientId = Auth::guard('clientadmin')->user()->clientid;
        if (!$user || $user->clientid != $clientId) {
            abort(404, 'User not found or unauthorized');
        }
        $branch = Branches::where('branchname', $user->branchname)->first();
        $geolocation = $branch ? $branch->branchgeolocation : null;
        return view('clientadmin.apps.locator.locatoremployeemap', [
            'id' => $id,
            'user' => $user,
            'branch' => $branch,
            'geolocation' => $geolocation,
            'apiKey' => $apiKey,
        ]);
    }

    /**
     *  App Locator Employee Search Engine (AJAX - CAN BE REUSED IN OTHER PAGES)
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

}
