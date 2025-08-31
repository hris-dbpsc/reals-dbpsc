<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\Client;
use App\Models\Branches;
use App\Models\Api_Keys;
use App\Models\Admin;

class AdminController extends Controller
{

    // =========================================
    // Admin Main Routes
    // 

    /**
     * Display the Admin dashboard.
     */
    // Dashboard route
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    // =========================================
    // Admin Account Management

    /**
     * Edit Admin route
     */
    public function editprofile(Request $request, $id)
    {
        $id = decrypt($id);
        $admin = Admin::find($id);
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
        $admin = Admin::find($id);

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
        $admin = Admin::find($id);

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
        $admin = Admin::find($id);

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
