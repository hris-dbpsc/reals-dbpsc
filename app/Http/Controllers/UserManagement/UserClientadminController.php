<?php

namespace App\Http\Controllers\UserManagement;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ClientadminExport;


class UserClientadminController extends Controller
{
    // =========================================
    // User Client Admin Account Management

    /**
     * View User Client Admin route
     */
    public function userclientadmin()
    {
        $clientadmins = Clientadmin::all()->sortBy([
            function ($a, $b) {
                $order = [1, 2, 0];
                $aIndex = array_search($a->isactive, $order);
                $bIndex = array_search($b->isactive, $order);
                if ($aIndex === $bIndex) {
                    return strcmp($a->lastname, $b->lastname); // fallback to lastname asc
                }
                return $aIndex <=> $bIndex;
            },
            ['lastname', 'asc']
        ]);
        $clients = Client::select('id', 'clientname')->get();
        // Build a map of clientid => clientname for fast lookup
        $clientMap = $clients->pluck('clientname', 'id');
        return view('superadmin.usermanagement.userclientadmin', compact('clientadmins', 'clients', 'clientMap'));
    }

    /**
     * Add User Client Admin route
     */
    public function addclientadmin()
    {
        $clients = Client::select('id', 'clientname')->orderBy('clientname')->get();
        return view('superadmin.usermanagement.addclientadmin', compact('clients'));
    }

    /**
     * Add User Client Admin submit
     */
    public function addclientadmin_submit(Request $request)
    {
        $request->validate([
            'clientid' => 'required',
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

        $clientadmin->clientid = $request->clientid;
        $clientadmin->firstname = $request->firstname;
        $clientadmin->middlename = $request->middlename;
        $clientadmin->lastname = $request->lastname;
        $clientadmin->email = $request->email;
        $clientadmin->contact = $request->contact;
        $clientadmin->password = Hash::make('clientadmin'); // Set a default password
        $clientadmin->save();

        // Send email notification to the newly added Clientadmin
        if (!empty($clientadmin->email)) {
            $subject = 'REALS - Client Admin Account Created';
            $fields = [
            'Username' => $clientadmin->email,
            'Default Password' => 'clientadmin',
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
            <h3 style="color:#0d6efd;margin-bottom:20px;">Client Admin Account Created</h3>
            <p style="font-size:16px;color:#212529;">Your Client Admin account has been <strong>created</strong> successfully. Please use the details below to login.</p>
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
            \Mail::to($clientadmin->email)->send(new \App\Mail\Websitemail($subject, $message));
        }

        return redirect()->route('superadmin_userclientadmin')->with('success', 'Client Admin added successfully.');
    }

    /**
     * Edit User Client Admin route
     */
    public function edituserclientadmin(Request $request, $id)
    {
        $clientadmin = Clientadmin::find($id);
        $clients = Client::select('id', 'clientname')->get();
        return view('superadmin.usermanagement.edituserclientadmin', compact('clientadmin', 'clients'));
    }

    /**
     * Edit User Client Admin Submit
     */
    public function edituserclientadmin_submit(Request $request)
    {
        $request->validate([
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

}
