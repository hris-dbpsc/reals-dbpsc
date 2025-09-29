<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SuperadminExport;

class UserSuperadminController extends Controller
{
    // =========================================
    // User Superadmin Account Management
    //

    /**
     * View User Superadmin route
     */
    public function usersuperadmin()
    {
        // Eager load any relationships if needed, e.g. ->with('relation')
        $superadmins = Superadmin::orderBy('lastname')
            ->orderBy('firstname')
            ->orderBy('middlename')
            ->get();
        return view('superadmin.usermanagement.usersuperadmin', compact('superadmins'));
    }

    /**
     * Add User Superadmin route
     */
    public function addsuperadmin()
    {
        return view('superadmin.usermanagement.addsuperadmin');
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

        // Send email notification to the newly added Superadmin
        if (!empty($superadmin->email)) {
            $subject = 'REALS - Superadmin Account Created';
            $fields = [
            'Employee Number' => $superadmin->employeenumber ?? '',
            'Name' => $superadmin->lastname . ', ' . $superadmin->firstname . ' ' . $superadmin->middlename,
            'Contact' => $superadmin->contact,
            'Username' => $superadmin->email,
            'Default Password' => 'superadmin',
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
            <h3 style="color:#0d6efd;margin-bottom:20px;">Superadmin Account Created</h3>
            <p style="font-size:16px;color:#212529;">Your Superadmin account has been <strong>created</strong> successfully. Please use the details below to login.</p>
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
            \Mail::to($superadmin->email)->send(new \App\Mail\Websitemail($subject, $message));
        }

        return redirect()->route('superadmin_usersuperadmin')->with('success', 'Superadmin added successfully.');
    }

    /**
     * Edit User Superadmin route
     */
    public function editusersuperadmin(Request $request, $id)
    {
        $superadmin = Superadmin::find($id);
        return view('superadmin.usermanagement.editusersuperadmin', compact('superadmin'));
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
}
