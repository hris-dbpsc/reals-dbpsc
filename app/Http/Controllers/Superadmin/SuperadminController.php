<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use App\Models\User;
use App\Models\Client;
use App\Models\Branches;
use App\Models\Api_Keys;
use App\Mail\Websitemail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use App\Imports\ClientsImport;
use App\Exports\ClientsExport;
use App\Imports\BranchesImport;
use App\Exports\BranchesExport;
use App\Exports\SuperadminExport;
use App\Exports\AdminExport;
use App\Exports\ClientadminExport;
use App\Models\WorkforceWatson;


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
        return view('superadmin.dashboard');
    }

    /**
     * Display the User Management page.
     */
    public function usermanagement()
    {
        return view('superadmin.usermanagement');
    }

    /**
     * Display the Client Management page.
     */
    public function clientmanagement()
    {
        $clients = Client::get();
        return view('superadmin.clientmanagement', compact('clients'));
    }

    /**
     * Display the Apps overview page.
     */
    public function apps()
    {
        return view('superadmin.apps');
    }
    //
    // End Superadmin Main Routes
    // =========================================


    // =========================================
    // Access  Management Start

    /**
     * Display the Access Management page.
     */
    public function accessmanagement()
    {
        return view('superadmin.accessmanagement');
    }

    /**
     * Display the User Permissions.
     */
    public function userpermissions()
    {
        return view('superadmin.userpermissions');
    }

    /**
     * Display the App Permissions.
     */
    public function apppermissions()
    {
        return view('superadmin.apppermissions');
    }

    // End Access Management
    // =========================================


    // =========================================
    // Superadmin Account Management

    /**
     * Edit Superadmin route
     */
    public function editsuperadmin(Request $request, $id)
    {
        $superadmin = Superadmin::find($id);
        return view('superadmin.editsuperadmin', compact('superadmin'));
    }

    /**
     * Edit Superadmin Submit
     */
    public function editsuperadmin_submit(Request $request)
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
    public function editsuperadmin_uploadprofilepicture(Request $request)
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
        $validator = \Validator::make($request->all(), [
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



    // =========================================
    // User Superadmin Account Management
    //

    /**
     * View User Superadmin route
     */
    public function usersuperadmin()
    {
        $superadmins = Superadmin::get();
        return view('superadmin.usersuperadmin', compact('superadmins'));
    }

    /**
     * Add User Superadmin route
     */
    public function addsuperadmin()
    {
        return view('superadmin.addsuperadmin');
    }

    /**
     * Add User Superadmin Submit
     */
    public function addsuperadmin_submit(Request $request)
    {
        $request->validate([
            'employeenumber' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'contact' => 'required|numeric',
        ]);

        // Check if email already exists in Superadmin, Admin, or Clientadmin
        $email = $request->email;
        $emailExists = Superadmin::where('email', $email)->exists() ||
            Admin::where('email', $email)->exists() ||
            Clientadmin::where('email', $email)->exists();

        if ($emailExists) {
            return redirect()->back()->withInput()->with('error', 'Email already in use');
        }

        $superadmin = new Superadmin();
        $superadmin->employeenumber = $request->employeenumber;
        $superadmin->firstname = $request->firstname;
        $superadmin->middlename = $request->middlename;
        $superadmin->lastname = $request->lastname;
        $superadmin->email = $request->email;
        $superadmin->contact = $request->contact;
        $superadmin->password = Hash::make('superadmin'); // Set a default password
        $superadmin->save();

        return redirect()->route('superadmin_usersuperadmin')->with('success', 'Superadmin added successfully.');
    }

    /**
     * Edit User Superadmin route
     */
    public function editusersuperadmin(Request $request, $id)
    {
        $superadmin = Superadmin::find($id);
        return view('superadmin.editusersuperadmin', compact('superadmin'));
    }

    /**
     * Edit User Superadmin Submit route
     */
    public function editusersuperadmin_submit(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'contact' => 'required|numeric',
            'status' => 'required',
        ]);

        $superadmin = Superadmin::find($request->id);

        $superadmin->update([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'contact' => $request->contact,
            'isactive' => $request->status,
        ]);

        return redirect()->route('superadmin_usersuperadmin')->with('success', 'Superadmin updated successfully.');
    }

    /**
     * Soft delete User Superadmin (set isactive to 0)
     */
    public function softdelete(Request $request, $id)
    {
        $superadmin = Superadmin::find($id);
        if (!$superadmin) {
            return redirect()->back()->with('error', 'Superadmin not found.');
        }
        $superadmin->isactive = 0;
        $superadmin->save();
        return redirect()->back()->with('success', 'Superadmin deactivated successfully.');
    }

    /**
     * Suspend User Superadmin (set isactive to 2)
     */
    public function suspend(Request $request, $id)
    {
        $superadmin = Superadmin::find($id);
        if (!$superadmin) {
            return redirect()->back()->with('error', 'Superadmin not found.');
        }
        $superadmin->isactive = 2;
        $superadmin->save();
        return redirect()->back()->with('success', 'Superadmin suspended successfully.');
    }

    /**
     * Export User Superadmin
     */
    public function exportusersuperadmin()
    {
        $filename = 'superadmins_' . date('YmdHis') . '.xlsx';
        return Excel::download(new SuperadminExport(), $filename);
    }
    //
    // End User Superadmin Account Management
    // =========================================


    // =========================================
    // User Admin Account Management
    //

    /**
     * Export User Admin route
     */
    public function useradmin()
    {
        $admins = Admin::where('role', 'admin')->get();
        return view('superadmin.useradmin', compact('admins'));
    }

    /**
     * Add User Admin route
     */
    public function addadmin()
    {
        return view('superadmin.addadmin');
    }

    /**
     * Add User Admin submit
     */
    public function addadmin_submit(Request $request)
    {
        $request->validate([
            'employeenumber' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'contact' => 'required|numeric',
        ]);

        // Check if email already exists in Superadmin, Admin, or Clientadmin
        $email = $request->email;
        $emailExists = Superadmin::where('email', $email)->exists() ||
            Admin::where('email', $email)->exists() ||
            Clientadmin::where('email', $email)->exists();

        if ($emailExists) {
            return redirect()->back()->withInput()->with('error', 'Email already in use');
        }

        $admin = new Admin();

        $admin->employeenumber = $request->employeenumber;
        $admin->firstname = $request->firstname;
        $admin->middlename = $request->middlename;
        $admin->lastname = $request->lastname;
        $admin->email = $request->email;
        $admin->contact = $request->contact;
        $admin->password = Hash::make('admin'); // Set a default password
        $admin->save();

        return redirect()->route('superadmin_useradmin')->with('success', 'Admin added successfully.');
    }

    /**
     * Edit User Admin route
     */
    public function edituseradmin(Request $request, $id)
    {
        $admin = Admin::find($id);
        return view('superadmin.edituseradmin', compact('admin'));
    }

    /**
     * Edit User Admin submit
     */
    public function edituseradmin_submit(Request $request)
    {
        $request->validate([
            'employeenumber' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'region' => 'required',
            'email' => 'required|email',
            'contact' => 'required|numeric',
            'status' => 'required',
        ]);

        $admin = Admin::find($request->id);

        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found.');
        }

        $admin->update([
            'employeenumber' => $request->employeenumber,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'region' => $request->region,
            'email' => $request->email,
            'contact' => $request->contact,
            'isactive' => $request->status,
        ]);

        return redirect()->route('superadmin_useradmin')->with('success', 'Admin updated successfully.');
    }

    /**
     * Soft delete User Admin (set isactive to 0)
     */
    public function adminsoftdelete(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found.');
        }
        $admin->isactive = 0;
        $admin->save();
        return redirect()->back()->with('success', 'Admin deactivated successfully.');
    }

    /**
     * Suspend User Admin (set isactive to 2)
     */
    public function adminsuspend(Request $request, $id)
    {
        $admin = Admin::find($id);
        if (!$admin) {
            return redirect()->back()->with('error', 'Admin not found.');
        }
        $admin->isactive = 2;
        $admin->save();
        return redirect()->back()->with('success', 'Admin suspended successfully.');
    }

    /**
     * Export User Admin
     */
    public function exportuseradmin()
    {
        $filename = 'admins_' . date('YmdHis') . '.xlsx';
        return Excel::download(new AdminExport(), $filename);
    }

    //
    // End User Admin Account Management
    // =========================================



    // =========================================
    // User Client Admin Account Management

    /**
     * View User Client Admin route
     */
    public function userclientadmin()
    {
        $clientadmins = Clientadmin::get();
        return view('superadmin.userclientadmin', compact('clientadmins'));
    }

    /**
     * Add User Client Admin route
     */
    public function addclientadmin()
    {
        $clients = Client::select('clientname')->get();
        return view('superadmin.addclientadmin', compact('clients'));
    }

    /**
     * Add User Client Admin submit
     */
    public function addclientadmin_submit(Request $request)
    {
        $request->validate([
            'clientname' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'contact' => 'required|numeric',
        ]);

        // Check if email already exists in Superadmin, Admin, or Clientadmin
        $email = $request->email;
        $emailExists = Superadmin::where('email', $email)->exists() ||
            Admin::where('email', $email)->exists() ||
            Clientadmin::where('email', $email)->exists();

        if ($emailExists) {
            return redirect()->back()->withInput()->with('error', 'Email already in use');
        }

        $clientadmin = new Clientadmin();

        $clientadmin->clientname = $request->clientname;
        $clientadmin->firstname = $request->firstname;
        $clientadmin->middlename = $request->middlename;
        $clientadmin->lastname = $request->lastname;
        $clientadmin->email = $request->email;
        $clientadmin->contact = $request->contact;
        $clientadmin->password = Hash::make('clientadmin'); // Set a default password
        $clientadmin->save();

        return redirect()->route('superadmin_userclientadmin')->with('success', 'Client Admin added successfully.');
    }

    /**
     * Edit User Client Admin route
     */
    public function edituserclientadmin(Request $request, $id)
    {
        $clientadmin = Clientadmin::find($id);
        $clients = Client::select('clientname')->get();
        return view('superadmin.edituserclientadmin', compact('clientadmin', 'clients'));
    }

    /**
     * Edit User Client Admin Submit
     */
    public function edituserclientadmin_submit(Request $request)
    {
        $request->validate([
            'clientname' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'contact' => 'required|numeric',
            'status' => 'required',
        ]);

        $clientadmin = Clientadmin::find($request->id);

        if (!$clientadmin) {
            return redirect()->back()->with('error', 'Client Admin not found.');
        }

        $clientadmin->update([
            'clientname' => $request->clientname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'contact' => $request->contact,
            'isactive' => $request->status,
        ]);

        return redirect()->route('superadmin_userclientadmin')->with('success', 'Client Admin updated successfully.');
    }

    /**
     * Export User Client Admin
     */
    public function exportuserclientadmin()
    {
        $filename = 'clientadmins_' . date('YmdHis') . '.xlsx';
        return Excel::download(new ClientadminExport(), $filename);
    }

    /**
     * Soft delete User Client Admin (set isactive to 0)
     */
    public function clientadminadminsoftdelete(Request $request, $id)
    {
        $clientadmin = Clientadmin::find($id);
        if (!$clientadmin) {
            return redirect()->back()->with('error', 'Client Admin not found.');
        }
        $clientadmin->isactive = 0;
        $clientadmin->save();
        return redirect()->back()->with('success', 'Client Admin deactivated successfully.');
    }

    /**
     * Suspend User Client Admin (set isactive to 2)
     */
    public function clientadminadminsuspend(Request $request, $id)
    {
        $clientadmin = Clientadmin::find($id);
        if (!$clientadmin) {
            return redirect()->back()->with('error', 'Client Admin not found.');
        }
        $clientadmin->isactive = 2;
        $clientadmin->save();
        return redirect()->back()->with('success', 'Client Admin suspended successfully.');
    }
    // 
    // End User Client Admin Account Management
    // =========================================


    // =========================================
    // User Employee Management Routes Start
    //

    /**
     * View User Employee route
     */
    public function useremployee()
    {
        $users = User::where('role', 'user')->get();
        $clients = Client::get();
        $branches = Branches::get();
        return view('superadmin.useremployee', compact('users', 'clients', 'branches'));
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

    //
    // End User Employee Management Routes
    // =========================================

    // =========================================
    // Client Management Routes Start
    //

    /**
     * View Clients route
     */
    public function clients()
    {
        $clients = Client::get();
        return view('superadmin.clients', compact('clients'));
    }

    /**
     * Add Client route
     */
    public function addclient()
    {
        return view('superadmin.addclient');
    }

    /**
     * Add Client submit
     */
    public function addclient_submit(Request $request)
    {
        $request->validate([
            'clientname' => 'required',
            'clientshortname' => 'required',
            'clienttype' => 'required',
        ]);

        $client = new Client();
        $client->clientname = $request->clientname;
        $client->clientshortname = $request->clientshortname;
        $client->clienttype = $request->clienttype;
        $client->save();


        return redirect()->route('superadmin_clients')->with('success', 'Client added successfully.');
    }

    /**
     * View Clients details route
     */
    public function viewclients(Request $request, $id)
    {
        $client = Client::find($id);
        return view('superadmin.viewclients', compact('client'));
    }

    /**
     * Edit Clients route
     */
    public function editclient(Request $request, $id)
    {
        $client = Client::find($id);
        return view('superadmin.editclient', compact('client'));
    }

    /**
     * Edit Clients submit
     */
    public function editclient_submit(Request $request, $id)
    {
        $request->validate([
            'clientname' => 'required',
            'clientshortname' => 'required',
            'clienttype' => 'required',
            'clientcontact' => 'required',
            'clientcontactperson' => 'required',
            'clientemail' => 'required|email',
            'clientaddress' => 'required',
            'clientcity' => 'required',
            'clientprovince' => 'required',
            'clientregion' => 'required',
            'clientcontractstart' => 'required|date',
            'clientcontractend' => 'required|date',
            'clientgeolocation' => 'required',
            'clientstreetview' => 'required',
            'status' => 'required',
        ]);

        $client = Client::find($id);

        $client->update([
            'clientname' => $request->clientname,
            'clientshortname' => $request->clientshortname,
            'clienttype' => $request->clienttype,
            'clientcontact' => $request->clientcontact,
            'clientcontactperson' => $request->clientcontactperson,
            'clientemail' => $request->clientemail,
            'clientaddress' => $request->clientaddress,
            'clientcity' => $request->clientcity,
            'clientprovince' => $request->clientprovince,
            'clientregion' => $request->clientregion,
            'clientcontractstart' => $request->clientcontractstart,
            'clientcontractend' => $request->clientcontractend,
            'clientgeolocation' => $request->clientgeolocation,
            'clientstreetview' => $request->clientstreetview,
            'isactive' => $request->status,
        ]);

        return redirect()->route('superadmin_clients')->with('success', 'Client updated successfully.');
    }

    /**
     *  Upload Client Profile Picture
     */
    public function editclient_uploadprofilepicture(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $client = Client::find($request->id);

        if (!$client) {
            return redirect()->back()->with('error', 'Client not found.');
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = 'client_' . $client->id . '_' . time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('assets/clients/');
            $image->move($path, $name);

            // Optionally delete old photo
            if ($client->clientphoto && file_exists($path . $client->clientphoto)) {
                @unlink($path . $client->clientphoto);
            }

            $client->clientphoto = $name;
            $client->save();
            return redirect()->route('superadmin_clients')->with('success', 'Client profile picture updated successfully.');
        }

        return redirect()->back()->with('error', 'No photo uploaded.');
    }

    /**
     *  Soft delete Client (set isactive to 0)
     */
    public function softdeleteclient(Request $request, $id)
    {
        $client = Client::find($id);
        if (!$client) {
            return redirect()->route('superadmin_clients')->with('error', 'Client not found.');
        }
        $client->isactive = 0;
        $client->save();
        return redirect()->route('superadmin_clients')->with('success', 'Client deactivated successfully.');
    }

    /**
     *  Import Clients from CSV
     */
    public function importclients(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|max:10000',
        ]);

        Excel::import(new ClientsImport, $request->file('csv_file'));
        return redirect()->route('superadmin_clients')->with('success', 'Clients imported successfully.');
    }

    /**
     *  Export Clients
     */
    public function exportclients()
    {
        $filename = 'clients_' . date('YmdHis') . '.xlsx';
        return Excel::download(new ClientsExport(), $filename);
    }

    /**
     *  View Branches route
     */
    public function branches()
    {
        $branches = Branches::leftJoin('clients', 'branches.clientname', '=', 'clients.clientname')
            ->select('branches.*', 'clients.clientphoto')
            ->get();
        $clients = Client::get();
        return view('superadmin.branches', compact('branches', 'clients'));
    }

    /**
     *  Add Branch route
     */
    public function addbranch()
    {
        $clients = Client::get();
        return view('superadmin.addbranch', compact('clients'));
    }

    /**
     *  Add Branch submit
     */
    public function addbranch_submit(Request $request)
    {
        $request->validate([
            'clientname' => 'required',
            'clientshortname' => 'required',
            'branchname' => 'required',
            'clienttype' => 'required',
        ]);

        $branch = new Branches();
        $branch->clientname = $request->clientname;
        $branch->clientshortname = $request->clientshortname;
        $branch->branchname = $request->branchname;
        $branch->clienttype = $request->clienttype;
        $branch->save();


        return redirect()->route('superadmin_branches')->with('success', 'Branch added successfully.');
    }

    /**
     *  View Branch details route
     */
    public function viewbranch(Request $request, $id)
    {
        $branch = Branches::leftJoin('clients', 'branches.clientname', '=', 'clients.clientname')
            ->select('branches.*', 'clients.clientphoto')
            ->where('branches.id', $id)
            ->first();
        return view('superadmin.viewbranch', compact('branch'));
    }

    /**
     *  Edit Branch route
     */
    public function editbranch(Request $request, $id)
    {
        $branch = Branches::leftJoin('clients', 'branches.clientname', '=', 'clients.clientname')
            ->select('branches.*', 'clients.clientphoto')
            ->where('branches.id', $id)
            ->first();
        return view('superadmin.editbranch', compact('branch'));
    }

    /**
     *  Edit Branch Submit route
     */
    public function editbranch_submit(Request $request, $id)
    {
        $request->validate([
            'clientname' => 'required',
            'branchname' => 'required',
            'branchcontact' => 'required',
            'branchcontactperson' => 'required',
            'branchaddress' => 'required',
            'branchregion' => 'required',
            'branchcity' => 'required',
            'branchprovince' => 'required',
            'branchgeolocation' => 'required',
            'branchstreetview' => 'required',
            'status' => 'required',
        ]);

        $branch = Branches::find($id);

        $branch->update([
            'clientname' => $request->clientname,
            'branchname' => $request->branchname,
            'branchcontact' => $request->branchcontact,
            'branchcontactperson' => $request->branchcontactperson,
            'branchaddress' => $request->branchaddress,
            'branchregion' => $request->branchregion,
            'branchcity' => $request->branchcity,
            'branchprovince' => $request->branchprovince,
            'branchgeolocation' => $request->branchgeolocation,
            'branchstreetview' => $request->branchstreetview,
            'isactive' => $request->status,
        ]);

        return redirect()->route('superadmin_branches')->with('success', 'Branch updated successfully.');
    }

    /**
     *  Soft delete Branch route (set isactive to 0)
     */
    public function softdeletebranch(Request $request, $id)
    {
        $branch = Branches::find($id);
        if (!$branch) {
            return redirect()->route('superadmin_branches')->with('error', 'Branch not found.');
        }
        $branch->isactive = 0;
        $branch->save();
        return redirect()->route('superadmin_branches')->with('success', 'Branch deactivated successfully.');
    }

    /**
     *  Import Branches from CSV
     */
    public function importbranches(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|max:10000',
        ]);

        Excel::import(new BranchesImport, $request->file('csv_file'));
        return redirect()->route('superadmin_branches')->with('success', 'Branches imported successfully.');
    }
    /**
     *  Export Branches
     */
    public function exportbranches(Request $request)
    {
        $clientshortname = $request->input('clientname');
        if ($clientshortname === 'ALL CLIENTS' || $clientshortname === null || $clientshortname === '') {
            $clientshortname = null; // Pass null to export all branches
        }
        $filename = ($clientshortname ? $clientshortname . '_branches_' : 'ALL_branches_') . date('YmdHis') . '.xlsx';
        return Excel::download(new BranchesExport($clientshortname), $filename);
    }

    // 
    // End Client Management Routes
    // =========================================


    // =========================================
    // App Locator Routes Start 

    /**
     *  App Locator View route
     */
    public function applocator()
    {
        $branches = Branches::get(); // or your query to get branches
        return view('superadmin.applocator', compact('branches'));
    }

    /**
     *  App Locator Client View route
     */
    public function applocatorclient()
    {
        $branches = Branches::get(); // or your query to get branches
        $clients = Client::get(); // or your query to get clients
        $users = User::where('role', 'user')->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        return view('superadmin.applocatorclient', compact('clients', 'branches', 'users', 'apiKey'));
    }

    /**
     *  App Locator Branch View route
     */
    public function applocatorbranch()
    {
        $branches = Branches::get(); // or your query to get branches
        $clients = Client::get(); // or your query to get clients
        $users = User::where('role', 'user')->get();
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        return view('superadmin.applocatorbranch', compact('branches', 'clients', 'users', 'apiKey'));
    }

    /**
     *  App Locator Employee View route
     */
    public function applocatoremployee()
    {
        $branches = Branches::get(); // or your query to get branches
        $clients = Client::get(); // or your query to get clients
        $users = User::where('role', 'user')->get(); // pass users data
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        return view('superadmin.applocatoremployee', compact('branches', 'clients', 'users', 'apiKey'));
    }

    /**
     *  App Locator Employee Data View route
     */
    public function applocatordata(Request $request)
    {
        $client = $request->query('client');
        $branch = $request->query('branch');
        $branches = Branches::get();
        $clients = Client::get();
        $users = User::where('role', 'user')->get();

        // Pass both variables to the view
        return view('superadmin.applocatordata', compact('branches', 'clients', 'users', 'client', 'branch'));
    }


    /**
     *  App Locator Employee Map View route
     */
    public function applocatoremployeemap(Request $request, $id)
    {
        $apiKey = Api_Keys::where('app_name', 'GOOGLE_MAPS_APP_LOCATOR')->first(); // get api_key for Google Maps App Locator
        // Get the user by ID
        $user = \App\Models\User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }

        // Join with branches to get geolocation
        $branch = \App\Models\Branches::where('branchname', $user->branchname)->first();
        $geolocation = $branch ? $branch->branchgeolocation : null;

        // Pass user and geolocation to the view
        return view('superadmin.applocatoremployeemap', [
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
    public function searchUsers(Request $request)
    {
        $q = $request->query('q', '');
        if (empty($q)) {
            return response()->json([]);
        }

        $users = \App\Models\User::where('role', 'user')
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
                        ->orWhere('clientname', 'like', "%$q%")
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
    // End App Locator Routes
    // =========================================


    // =========================================
    // App People Routes Start
    //

    /**
     *  App People View route
     */
    public function apppeople()
    {
        return view('superadmin.apppeople');
    }

    /**
     *  App People View route
     */
    public function apppeopleview(Request $request, $id)
    {
        $user = \App\Models\User::find($id);
        if (!$user) {
            abort(404, 'User not found');
        }
        return view('superadmin.apppeopleview', compact('user'));
    }

    //
    // End App People Routes
    // =========================================


    // =========================================
    // App WorkForce Routes Start
    //

    /**
     *  App WorkForce View route
     */
    public function appworkforce()
    {
        $branches = Branches::where('isactive', 1)->orderBy('branchname', 'asc')->get();
        $users = User::where('role', 'user')->where('isactive', 1)->get();
        $workforces = WorkforceWatson::orderBy('created_at', 'desc')->get();
        return view('superadmin.appworkforce', compact('branches', 'users', 'workforces'));
    }

    /**
     *  App WorkForce Request Submit
     */
    public function appworkforce_submit(Request $request)
    {
        $request->validate([
            'requesttype' => 'required',
            'branchname' => 'nullable',
            'employeestransferred' => 'nullable',
            'branchtransferfrom' => 'nullable',
            'branchtransferto' => 'nullable',
            'employeesreshuffled' => 'array',
            'branchreshufflefrom' => 'array',
            'branchreshuffleto' => 'array',
            'clientremarks' => 'nullable|string|max:1000',
        ]);

        $workforce = new WorkforceWatson();

        $workforce->requesttype = $request->requesttype;
        $workforce->branchtarget = $request->branchname;
        $workforce->clientremarks = $request->clientremarks;
        $workforce->requestclient = Auth::guard('superadmin')->user()->email;
        $workforce->requestdate = now('Asia/Manila')->toDateTimeString();
        $workforce->requestby = Auth::guard('superadmin')->user()->id . '. ' .
            Auth::guard('superadmin')->user()->lastname . ', ' .
            Auth::guard('superadmin')->user()->firstname . ' ' .
            Auth::guard('superadmin')->user()->middlename;
        $workforce->requestemail = Auth::guard('superadmin')->user()->email;

        $workforce->employeestransferred = $request->employeestransferred;
        $workforce->branchtransferfrom = $request->branchtransferfrom;
        $workforce->branchtransferto = $request->branchtransferto;

        // Save reshuffle arrays as comma-separated strings
        $workforce->employeesreshuffled = is_array($request->employeesreshuffled) ? collect($request->employeesreshuffled)->filter()->implode(', ') : null;
        $workforce->branchreshufflefrom = is_array($request->branchreshufflefrom) ? collect($request->branchreshufflefrom)->filter()->implode(', ') : null;
        $workforce->branchreshuffleto = is_array($request->branchreshuffleto) ? collect($request->branchreshuffleto)->filter()->implode(', ') : null;

        $workforce->save();

        // CLIENT REQUEST EMAIL NOTIFICATION MESSAGE
        $subject = 'REALS - WORKFORCE[' . $workforce->requesttype . ']';

        // Build the table rows in PHP
        // Format reshuffle data as a table if all arrays are present and not empty
        $reshuffleTable = '';
        if (
            !empty($workforce->employeesreshuffled) &&
            !empty($workforce->branchreshufflefrom) &&
            !empty($workforce->branchreshuffleto)
        ) {
            $employees = array_map('trim', explode(',', $workforce->employeesreshuffled));
            $froms = array_map('trim', explode(',', $workforce->branchreshufflefrom));
            $tos = array_map('trim', explode(',', $workforce->branchreshuffleto));
            $count = min(count($employees), count($froms), count($tos));
            if ($count > 0) {
                $reshuffleTable .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;width:100%;margin-bottom:10px;">';
                $reshuffleTable .= '<tr style="background:#e9ecef;"><th>EMPLOYEE NAME</th><th>FROM</th><th>TO</th></tr>';
                for ($i = 0; $i < $count; $i++) {
                    $reshuffleTable .= '<tr>';
                    $reshuffleTable .= '<td>' . htmlentities($employees[$i]) . '</td>';
                    $reshuffleTable .= '<td>' . htmlentities($froms[$i]) . '</td>';
                    $reshuffleTable .= '<td>' . htmlentities($tos[$i]) . '</td>';
                    $reshuffleTable .= '</tr>';
                }
                $reshuffleTable .= '</table>';
            }
        }

        $fields = [
            'Request Type' => $workforce->requesttype,
            'Request ID' => $workforce->id,
            'Branch' => $workforce->branchtarget,
            'Date' => $workforce->requestdate,
            'Request By' => $workforce->requestby . ' - ' . $workforce->requestemail,
            'Transferred Employee' => $workforce->employeestransferred,
            'Transfer From' => $workforce->branchtransferfrom,
            'Transfer To' => $workforce->branchtransferto,
            'Remarks' => $workforce->clientremarks,
        ];
        $tableRows = '';
        foreach ($fields as $label => $value) {
            if (!empty($value)) {
                $displayValue = ($label === 'Remarks') ? nl2br(htmlentities($value)) : htmlentities($value);
                $tableRows .= '<tr>
                <td style="padding:5px 0;"><strong>' . htmlentities($label) . ':</strong></td>
                <td style="padding:5px 0;">' . $displayValue . '</td>
            </tr>';
            }
        }

        // Add reshuffle table if available
        if ($reshuffleTable) {
            $tableRows .= '<tr>
            <td style="padding:5px 0;vertical-align:top;"><strong>Reshuffled Employees:</strong></td>
            <td style="padding:5px 0;">' . $reshuffleTable . '</td>
            </tr>';
        }

        $message = '
        <div id="workforcemailer" style="max-width:600px;margin:30px auto;padding:30px;border:1px solid #e3e3e3;border-radius:8px;font-family:sans-serif;background:#f8f9fa;">
            <div style="text-align:center;">
            <h2 style="color:#0d6efd;margin-bottom:20px;">REALS - DBPSC</h2>
            </div>
            <hr style="margin:20px 0;">
            <h3 style="color:#0d6efd;margin-bottom:20px;">Workforce Request Submitted</h3>
            <p style="font-size:16px;color:#212529;">Your request has been <strong>successfully added!</strong></p>
            <hr style="margin:20px 0;">
            <table style="width:100%;font-size:15px;">
            ' . $tableRows . '
            </table>
            <div style="margin-top:20px;text-align:center;">
            <span style="color:#dc3545;">This email is auto generated. Do not reply.</span>
            </div>
        </div>
        ';

        // CLIENT REQUEST EMAIL NOTIFICATION MESSAGE
        \Mail::to(Auth::guard('superadmin')->user()->email)->send(new Websitemail($subject, $message));

        return redirect()->back()->with('success', 'Request added successfully.');
    }

    //
    // End App WorkForce Routes
    // =========================================
































    // TEST APPS ONLY
    public function qrcode()
    {
        return view('superadmin.qrcode');
    }
}
