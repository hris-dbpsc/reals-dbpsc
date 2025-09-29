<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AdminExport;
use App\Models\Applications;
use App\Models\AdminApplicationsAccess;


class UserAdminController extends Controller
{
    // =========================================
    // User Admin Account Management
    //

    /**
     * Export User Admin route
     */
    public function useradmin()
    {
        $admins = Admin::all()->sort(function ($a, $b) {
            $order = [1, 2, 0];
            $aIndex = array_search($a->isactive, $order);
            $bIndex = array_search($b->isactive, $order);
            if ($aIndex === $bIndex) {
                return strcmp($a->lastname, $b->lastname);
            }
            return $aIndex <=> $bIndex;
        });
        return view('superadmin.usermanagement.useradmin', compact('admins'));
    }

    /**
     * Add User Admin route
     */
    public function addadmin()
    {
        return view('superadmin.usermanagement.addadmin');
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
            'role' => 'required',
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
        $adminAccess = new AdminApplicationsAccess();

        $admin->employeenumber = $request->employeenumber;
        $admin->firstname = $request->firstname;
        $admin->middlename = $request->middlename;
        $admin->lastname = $request->lastname;
        $admin->email = $request->email;
        $admin->contact = $request->contact;
        $admin->role = $request->role;
        $admin->password = Hash::make('admin'); // Set a default password
        $admin->save();
        $adminAccess->adminid = $admin->id;
        $adminAccess->save();

        // Send email notification to the newly added Admin
        if (!empty($admin->email)) {
            $subject = 'REALS - Admin Account Created';
            $fields = [
            'Employee Number' => $admin->employeenumber ?? '',
            'Name' => $admin->lastname . ', ' . $admin->firstname . ' ' . $admin->middlename,
            'Contact' => $admin->contact,
            'Username' => $admin->email,
            'Default Password' => 'admin',
            ];
            $tableRows = '';
            foreach ($fields as $label => $value) {
            if (!empty($value)) {
                $tableRows .= '<tr>
                <td style="padding:5px 0;"><strong>' . htmlentities($label) . ':</strong></td>
                <td style="padding:5px 0;">' . htmlentities($value) . '</td>
                </tr>';
            }
            }
            $message = '
            <div style="max-width:600px;margin:30px auto;padding:30px;border:1px solid #e3e3e3;border-radius:8px;font-family:sans-serif;background:#f8f9fa;">
            <div style="text-align:center;">
                <h2 style="color:#0d6efd;margin-bottom:20px;">REALS - DBPSC</h2>
            </div>
            <hr style="margin:20px 0;">
            <h3 style="color:#0d6efd;margin-bottom:20px;">Admin Account Created</h3>
            <p style="font-size:16px;color:#212529;">Your Admin account has been <strong>created</strong> successfully. Please use the details below to login.</p>
            <hr style="margin:20px 0;">
            <table style="width:100%;font-size:15px;">
                ' . $tableRows . '
            </table>
            <div style="margin-top:20px;text-align:center;">
                <span style="color:#dc3545;">This email is auto generated. Do not reply.</span>
            </div>
            </div>
            <div style="margin-top:30px;text-align:center;">
            <a href="https://reals-dbpsc.com/" style="display:inline-block;padding:10px 24px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:5px;font-size:16px;font-weight:bold;">Go to REALS-DBPSC</a>
            </div>
            ';
            // Make sure you have imported Mail and Websitemail classes
            \Mail::to($admin->email)->send(new \App\Mail\Websitemail($subject, $message));
        }

        return redirect()->route('superadmin_useradmin')->with('success', 'Admin added successfully.');
    }

    /**
     * Edit User Admin route
     */
    public function edituseradmin(Request $request, $id)
    {
        $applications = Applications::where('isactive', 1)->get();
        $applications_access = AdminApplicationsAccess::where('adminid', $id)->first();

        $admin = Admin::find($id);
        $regions = [
            'I',
            'II',
            'III',
            'IV-A',
            'IV-B',
            'V',
            'VI',
            'VII',
            'VIII',
            'IX',
            'X',
            'XI',
            'XII',
            'XIII',
            'BARMM',
            'NCR',
            'CAR'
        ];
        $selectedRegion = $admin->region ?? null;
        return view('superadmin.usermanagement.edituseradmin', compact('admin', 'regions', 'selectedRegion', 'applications', 'applications_access'));
    }

    /**
     * Edit Admin App Access Submit
     */
    public function editadminappaccess_submit(Request $request, $id)
    {
        $access = AdminApplicationsAccess::findOrFail($id);
        $applications = Applications::where('isactive', 1)->get();

        foreach ($applications as $application) {
            $field = 'app_' . $application->id;
            $access->$field = $request->has($field) ? 1 : 0;
        }

        $access->save();

        return redirect()->back()->with('success', 'Application access updated successfully.');
    }

    /**
     * Edit User Admin submit
     */
    public function edituseradmin_submit(Request $request)
    {
        $request->validate([
            'employeenumber' => 'required',
            'firstname' => 'required',
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

}
