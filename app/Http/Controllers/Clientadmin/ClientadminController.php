<?php

namespace App\Http\Controllers\Clientadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Branches;
use App\Models\Api_Keys;
use App\Models\Clientadmin;

class ClientadminController extends Controller
{
    // =========================================
    // Client Admin Main Routes
    //

    /**
     * Display the Client Admin dashboard.
     */
    // Dashboard route
    public function dashboard()
    {
        return view('clientadmin.dashboard');
    }

    // =========================================
    // Client Admin Account Management

    /**
     * Edit Client Admin route
     */
    public function editprofile(Request $request, $id)
    {
        $id = decrypt($id);
        $clientadmin = Clientadmin::find($id);
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
        $clientadmin = Clientadmin::find($id);

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
        $clientadmin = Clientadmin::find($id);

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
        $clientadmin = Clientadmin::find($id);

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
