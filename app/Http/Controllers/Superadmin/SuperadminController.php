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


class SuperadminController extends Controller
{

    // Login route start
    public function index()
    {
        return view('superadmin.index');
    }
    // Login route end

    // Dashboard Route Start
    public function dashboard()
    {
        return view('superadmin.dashboard');
    }
    // Dashboard Route End

    // App Route Start
    public function apps()
    {
        return view('superadmin.apps');
    }

    // App Locator route start
    public function applocator()
    {
        $branches = Branches::get(); // or your query to get branches
        return view('superadmin.applocator', compact('branches'));
    }

    public function applocatorclient()
    {
        $branches = Branches::get(); // or your query to get branches
        $clients = Client::get(); // or your query to get clients
        $users = User::where('role', 'user')->get();
        return view('superadmin.applocatorclient', compact('clients', 'branches', 'users'));
    }
    public function applocatorbranch()
    {
        $branches = Branches::get(); // or your query to get branches
        $clients = Client::get(); // or your query to get clients
        $users = User::where('role', 'user')->get();
        return view('superadmin.applocatorbranch', compact('branches', 'clients', 'users'));
    }
    public function applocatoremployee()
    {
        $branches = Branches::get(); // or your query to get branches
        $clients = Client::get(); // or your query to get clients
        return view('superadmin.applocatoremployee', compact('branches', 'clients'));
    }
    // App Locator route end
    // App Route End


    // User Management Routes Start
    public function usermanagement()
    {
        return view('superadmin.usermanagement');
    }

    // Superadmin Management Routes Start
    // View Superadmin
    public function usersuperadmin()
    {
        $superadmins = Superadmin::get();
        return view('superadmin.usersuperadmin', compact('superadmins'));
    }
    // Add Superadmin
    public function addsuperadmin()
    {
        return view('superadmin.addsuperadmin');
    }

    // Export Superadmin
    public function exportusersuperadmin()
    {
        $filename = 'superadmins_' . date('YmdHis') . '.xlsx';
        return Excel::download(new SuperadminExport(), $filename);
    }

    // Add Superadmin submit
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
    // Edit Superadmin route
    public function editsuperadmin(Request $request, $id)
    {
        $superadmin = Superadmin::find($id);
        return view('superadmin.editsuperadmin', compact('superadmin'));
    }

    // Edit Superadmin Submit route
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

    // Upload Superadmin Profile Picture route
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

    // Edit User Superadmin route
    public function editusersuperadmin(Request $request, $id)
    {
        $superadmin = Superadmin::find($id);
        return view('superadmin.editusersuperadmin', compact('superadmin'));
    }

    // Edit User Superadmin Submit route
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

    // Soft delete User Superadmin (set isactive to 0)
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

    // Suspend User Superadmin (set isactive to 2)
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

    // Change Superadmin Password route
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

        return redirect()->back()->with('success2', 'Password changed successfully.');
    }

    // Reset Superadmin password route
    public function reset_password($token, $email)
    {
        $superadmin = Superadmin::where('email', $email)->where('token', $token)->first();
        if (!$superadmin) {
            return redirect()->route('superadmin_index')->with('error', 'Invalid token or email!');
        }
        return view('superadmin.reset_password', compact('token', 'email'));
    }

    // Reset Superadmin password submit route
    public function reset_password_submit(Request $request, $token, $email)
    {
        $request->validate([
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        $superadmin = Superadmin::where('email', $email)->where('token', $token)->first();
        $superadmin->password = Hash::make($request->password);
        $superadmin->token = ''; // Clear the token after password reset
        $superadmin->update();

        return redirect()->route('superadmin_index')->with('success', 'Password reset successfully. Please login.');
    }
    // Superadmin Management Routes End

    // Admin Management Routes Start
    // View Admins route
    public function useradmin()
    {
        $admins = Admin::where('role', 'admin')->get();
        return view('superadmin.useradmin', compact('admins'));
    }
    // Add Admin route
    public function addadmin()
    {
        return view('superadmin.addadmin');
    }

    // Export Admins
    public function exportuseradmin()
    {
        $filename = 'admins_' . date('YmdHis') . '.xlsx';
        return Excel::download(new AdminExport(), $filename);
    }


    // Add Admin submit route
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

    public function edituseradmin(Request $request, $id)
    {
        $admin = Admin::find($id);
        return view('superadmin.edituseradmin', compact('admin'));
    }

    public function edituseradmin_submit(Request $request)
    {
        $request->validate([
            'employeenumber' => 'required',
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
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
            'email' => $request->email,
            'contact' => $request->contact,
            'isactive' => $request->status,
        ]);

        return redirect()->route('superadmin_useradmin')->with('success', 'Admin updated successfully.');
    }

    // Soft delete User Admin (set isactive to 0)
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

    // Suspend User Admin (set isactive to 2)
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
    // Admin Management Routes End

    // Client Admin Management Routes Start
    // Add Client Admin
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

    // Export Client Admin
    public function exportuserclientadmin()
    {
        $filename = 'clientadmins_' . date('YmdHis') . '.xlsx';
        return Excel::download(new ClientadminExport(), $filename);
    }
    // View Client Admin users
    public function userclientadmin()
    {
        $clientadmins = Clientadmin::get();
        return view('superadmin.userclientadmin', compact('clientadmins'));
    }

    // Add Client Admin route
    public function addclientadmin()
    {
        $clients = Client::select('clientname')->get();
        return view('superadmin.addclientadmin', compact('clients'));
    }

    // Edit Client Admin route
    public function edituserclientadmin(Request $request, $id)
    {
        $clientadmin = Clientadmin::find($id);
        $clients = Client::select('clientname')->get();
        return view('superadmin.edituserclientadmin', compact('clientadmin', 'clients'));
    }

    //Edit Client Admin Submit route
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

    // Soft delete User Admin (set isactive to 0)
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

    // Suspend User Admin (set isactive to 2)
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
    // Client Admin Management Routes End

    // User Management Routes Start
    // View Users route
    public function useremployee()
    {
        $users = User::where('role', 'user')->get();
        $clients = Client::get();
        $branches = Branches::get();
        return view('superadmin.useremployee', compact('users', 'clients', 'branches'));
    }

    public function importemployee(Request $request)
    {
        // Handle CSV import logic here
        $request->validate([
            'csv_file' => 'required|max:10000',
        ]);

        Excel::import(new UsersImport, $request->file('csv_file'));
        return redirect()->route('superadmin_useremployee')->with('success', 'Employees imported successfully.');
    }
    public function exportemployee(Request $request)
    {
        $clientshortname = $request->input('clientname');
        if ($clientshortname === 'ALL CLIENTS' || empty($clientshortname)) {
            $clientshortname = null; // Pass null to export all employees
        }
        $filename = ($clientshortname ? $clientshortname . '_employees_' : 'ALL_employees_') . date('YmdHis') . '.xlsx';
        return Excel::download(new UsersExport($clientshortname), $filename);
    }
    // User Management Routes End






    // Client Management Routes Start
    // View Clients route
    public function clientmanagement()
    {
        $clients = Client::get();
        return view('superadmin.clientmanagement', compact('clients'));
    }

    public function clients()
    {
        $clients = Client::get();
        return view('superadmin.clients', compact('clients'));
    }

    // Add Client route
    public function addclient()
    {
        return view('superadmin.addclient');
    }

    // Import Clients from CSV
    public function importclients(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|max:10000',
        ]);

        Excel::import(new ClientsImport, $request->file('csv_file'));
        return redirect()->route('superadmin_clients')->with('success', 'Clients imported successfully.');
    }
    // Export Clients
    public function exportclients()
    {
        $filename = 'clients_' . date('YmdHis') . '.xlsx';
        return Excel::download(new ClientsExport(), $filename);
    }

    // Add Client submit route
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

    // View Clients details route
    public function viewclients(Request $request, $id)
    {
        $client = Client::find($id);
        return view('superadmin.viewclients', compact('client'));
    }

    // Edit Clients details route
    public function editclients(Request $request, $id)
    {
        $client = Client::find($id);
        return view('superadmin.editclient', compact('client'));
    }

    // Edit Client Details Submit route
    public function editclients_submit(Request $request, $id)
    {
        $request->validate([
            'clientname' => 'required',
            'clientshortname' => 'required',
            'clienttype' => 'required',
            'clientgeolocation' => 'required',
            'clientstreetview' => 'required',
            'status' => 'required',
        ]);

        $client = Client::find($id);

        $client->update([
            'clientname' => $request->clientname,
            'clientshortname' => $request->clientshortname,
            'clienttype' => $request->clienttype,
            'clientgeolocation' => $request->clientgeolocation,
            'clientstreetview' => $request->clientstreetview,
            'isactive' => $request->status,
        ]);

        return redirect()->route('superadmin_clients')->with('success', 'Client updated successfully.');
    }

    // Upload Client Profile Picture route
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
    // Soft delete Client route
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

    // View Branches route
    public function branches()
    {
        $branches = Branches::leftJoin('clients', 'branches.clientname', '=', 'clients.clientname')
            ->select('branches.*', 'clients.clientphoto')
            ->get();
        $clients = Client::get();
        return view('superadmin.branches', compact('branches', 'clients'));
    }

    // Add Branch route
    public function addbranch()
    {
        $clients = Client::get();
        return view('superadmin.addbranch', compact('clients'));
    }
    
    // Import Branches from CSV
    public function importbranches(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|max:10000',
        ]);

        Excel::import(new BranchesImport, $request->file('csv_file'));
        return redirect()->route('superadmin_branches')->with('success', 'Branches imported successfully.');
    }
    // Export Branches
    public function exportbranches(Request $request)
    {
        $clientshortname = $request->input('clientname');
        if ($clientshortname === 'ALL CLIENTS' || $clientshortname === null || $clientshortname === '') {
            $clientshortname = null; // Pass null to export all branches
        }
        $filename = ($clientshortname ? $clientshortname . '_branches_' : 'ALL_branches_') . date('YmdHis') . '.xlsx';
        return Excel::download(new BranchesExport($clientshortname), $filename);
    }

    
    // Add Branch submit route
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

    // View Branch details route
    public function viewbranch(Request $request, $id)
    {
        $branch = Branches::leftJoin('clients', 'branches.clientname', '=', 'clients.clientname')
            ->select('branches.*', 'clients.clientphoto')
            ->where('branches.id', $id)
            ->first();
        return view('superadmin.viewbranch', compact('branch'));
    }

    // Edit Branch details route
    public function editbranch(Request $request, $id)
    {
        $branch = Branches::leftJoin('clients', 'branches.clientname', '=', 'clients.clientname')
            ->select('branches.*', 'clients.clientphoto')
            ->where('branches.id', $id)
            ->first();
        return view('superadmin.editbranch', compact('branch'));
    }

    // Edit Branch Details Submit route
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

    // Soft delete Branch route (set isactive to 0)
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
    // Client Management Routes End

















    // Forget password route
    public function forget_password()
    {
        return view('superadmin.forget_password');
    }

    // Forget password submit route    
    public function forget_password_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $superadmin = Superadmin::where('email', $request->email)->first();
        if (!$superadmin) {
            return redirect()->back()->with('error', 'Email not found!');
        }
        $token = hash('sha256', time());
        $superadmin->token = $token;
        $superadmin->save();

        $link = route('superadmin_reset_password', [$token, $request->email]);
        $subject = 'Reset Password';
        $message = 'Click the link to reset your password: <br>';
        $message .= '<a href="' . $link . '">' . $link . '</a>';

        \Mail::to($request->email)->send(new Websitemail($subject, $message));

        return redirect()->back()->with('success', 'Check your email for the reset link.');
    }


    // Login validation and authentication
    public function login_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        $superadmin = \App\Models\Superadmin::where('email', $request->email)->first();

        if (!$superadmin) {
            return redirect()->back()->with('error', 'Invalid credentials!');
        }

        if ($superadmin->isactive == 0) {
            return redirect()->back()->with('error', 'Account is deactivated.');
        }

        if ($superadmin->isactive == 2) {
            return redirect()->back()->with('error', 'Account is suspended.');
        }

        if (Auth::guard('superadmin')->attempt($credentials)) {
            return redirect()->route('superadmin_dashboard')->with('success', 'Login Successful');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials!');
        }
    }

    // Logout route
    public function logout()
    {
        Auth::guard('superadmin')->logout();
        return redirect()->route('superadmin_index')->with('success', 'Logged out successfully');
    }


    // TEST APPS ONLY
    public function qrcode()
    {
        return view('superadmin.qrcode');
    }
}
