<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TimeOff;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{

    // =========================================
    // Employee Main Routes
    //

    /**
     * Display the Employee Dashboard
     */
    public function dashboard()
    {
        $user = auth('user')->user();

        // Only get TimeOff records for the authenticated user using employeenumber
        $year = Carbon::now()->year;
        $timeOffCounts = array_fill(1, 12, 0);

        $timeOffRows = TimeOff::where('leaveby', $user->employeenumber)
            ->whereNotNull('leaverequestdate')
            ->get(['leaverequestdate']);

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

        // Pending Count
        $timeOffPending = TimeOff::where('leavestatus', 'pending')
            ->where('leaveby', $user->employeenumber)
            ->count();
        $totalPending = $timeOffPending;

        return view(
            'user.dashboard',
            compact(
                'timeOffLabels',
                'timeOffData',
                'timeOffPending',
                'totalPending'
            )
        );
    }


    /**
     * Display the Apps.
     */
    public function apps()
    {
        return view('user.apps');
    }

    /**
     * Display the Profile.
     */
    public function profile(Request $request, $id)
    {
        try {
            $decryptedId = decrypt($id);
        } catch (\Exception $e) {
            // Show user-friendly error page for invalid encrypted ID
            return redirect()->back();
        }
        $user = User::find(auth('user')->id());

        return view('user.profile', compact('user'));
    }


    /**
     * Upload Admin Profile Picture
     */
    public function uploadprofilepicture(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(auth('user')->id());

        if (!$user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name = $user->employeenumber . '.jpg';
            $path = public_path('assets/users/users/');

            // Convert image to jpg
            $imgResource = null;
            $extension = strtolower($image->getClientOriginalExtension());
            if ($extension === 'png') {
                $imgResource = imagecreatefrompng($image->getPathname());
            } elseif ($extension === 'gif') {
                $imgResource = imagecreatefromgif($image->getPathname());
            } else {
                $imgResource = imagecreatefromjpeg($image->getPathname());
            }

            if ($imgResource) {
                imagejpeg($imgResource, $path . $name, 90);
                imagedestroy($imgResource);

                // Optionally delete old photo
                if ($user->photo && file_exists($path . $user->photo)) {
                    @unlink($path . $user->photo);
                }

                $user->photo = $name;
                $user->save();

                return redirect()->back()->with('success', 'Profile picture updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to process image.');
            }
        }
        return redirect()->back()->with('error', 'No photo uploaded.');
    }

    /**
     * Change User Password
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
        $user = User::find(auth('user')->id());

        if (!$user || !Hash::check($request->oldpassword, $user->password)) {
            return redirect()->back()->with('error', 'Old password is incorrect.');
        }

        if ($request->newpassword !== $request->confirmpassword) {
            return redirect()->back()->with('error', 'Passwords did not match.');
        }

        $user->password = Hash::make($request->newpassword);
        $user->save();

        return redirect()->back()->with('success', 'Password changed successfully.');
    }

    // 
    // End Employee Account Management
    // =========================================

}
