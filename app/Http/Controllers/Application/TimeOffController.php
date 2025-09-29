<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\ClientAdmin;
use App\Models\User;
use App\Models\Branches;
use App\Models\Holidays;
use App\Models\TimeOff;
use App\Mail\Websitemail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class TimeOffController extends Controller
{
    // =========================================
    // TimeOff for Superadmin Routes
    //

    /**
     *  Superadmin TimeOff View route
     */
    public function superadmin_timeoff()
    {
        // Get active users
        $activeUsers = User::where('isactive', 1)->pluck('employeenumber')->all();

        // Only show timeoff for active users
        $timeOff = TimeOff::whereIn('leaveby', $activeUsers)
            ->orderBy('leavestatus', 'desc')
            ->orderBy('leaverequestdate', 'desc')
            ->get();

        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();

        $timeOffCountPending = TimeOff::whereIn('leaveby', $activeUsers)->where('leavestatus', 'pending')->count();
        $timeOffCountApproved = TimeOff::whereIn('leaveby', $activeUsers)->where('leavestatus', 'approved')->count();
        $timeOffCountDisapproved = TimeOff::whereIn('leaveby', $activeUsers)->where('leavestatus', 'disapproved')->count();
        $timeOffCountCancelled = TimeOff::whereIn('leaveby', $activeUsers)->where('leavestatus', 'cancelled')->count();
        $timeOffCountAll = $timeOffCountPending + $timeOffCountApproved + $timeOffCountDisapproved + $timeOffCountCancelled;

        return view('superadmin.apps.timeoff.timeoff', compact(
            'timeOff',
            'holidays',
            'timeOffCountAll',
            'timeOffCountPending',
            'timeOffCountApproved',
            'timeOffCountDisapproved',
            'timeOffCountCancelled'
        ));
    }

    /**
     *  Superadmin All TimeOff View route
     */
    public function superadmin_alltimeoff(Request $request)
    {
        // Keyed collections for O(1) lookup in blade
        $admins = Admin::all()->keyBy('id');
        $users = User::where('isactive', 1)->get()->keyBy('employeenumber');

        // allow passing a `status` query parameter; default to 'all'
        $status = $request->query('status', 'all');
        $allowed = ['all', 'pending', 'approved', 'disapproved', 'cancelled'];
        if (!in_array($status, $allowed)) {
            // fallback to 'all' for unknown values
            $status = 'all';
        }

        // Only show timeoff for users who are active
        $activeEmployeeNumbers = $users->keys()->all();

        $query = TimeOff::whereIn('leaveby', $activeEmployeeNumbers)
            ->orderBy('leavestatus', 'desc')
            ->orderBy('leaverequestdate', 'desc');

        if ($status !== 'all') {
            $query->where('leavestatus', $status);
        }

        $timeOffs = $query->get();

        return view('superadmin.apps.timeoff.alltimeoff', compact('timeOffs', 'admins', 'users', 'status'));
    }
    // =========================================


    // =========================================
    // TimeOff for User Routes
    //

    /**
     *  User TimeOff View route
     */
    public function user_timeoff()
    {
        $timeOff = TimeOff::orderBy('leavestatus', 'desc')->orderBy('leaverequestdate', 'desc')->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $user = Auth::guard('user')->user();
        $timeOffCountAll = TimeOff::where('leaveby', $user->employeenumber)->whereYear('created_at', now()->year)->count();
        $timeOffCountPending = TimeOff::where('leaveby', $user->employeenumber)->whereYear('created_at', now()->year)->where('leavestatus', 'pending')->count();
        $timeOffCountApproved = TimeOff::where('leaveby', $user->employeenumber)->whereYear('created_at', now()->year)->where('leavestatus', 'approved')->count();
        $timeOffCountDisapproved = TimeOff::where('leaveby', $user->employeenumber)->whereYear('created_at', now()->year)->where('leavestatus', 'disapproved')->count();
        $timeOffCountCancelled = TimeOff::where('leaveby', $user->employeenumber)->whereYear('created_at', now()->year)->where('leavestatus', 'cancelled')->count();
        return view('user.apps.timeoff.timeoff', compact('timeOff', 'holidays', 'timeOffCountAll', 'timeOffCountPending', 'timeOffCountApproved', 'timeOffCountDisapproved', 'timeOffCountCancelled'));
    }

    /**
     *  User TimeOff Add route
     */
    public function user_addtimeoff()
    {
        $holidaysArr = Holidays::whereYear('date', now()->year)
            ->orderBy('date')
            ->get()
            ->pluck('date')
            ->map(function ($d) {
                return \Carbon\Carbon::parse($d)->format('Y-m-d');
            })
            ->toArray();
        $user = Auth::user();
        return view('user.apps.timeoff.addtimeoff', compact('holidaysArr', 'user'));
    }

    /**
     *  User TimeOff Submit route
     */
    public function user_addtimeoff_submit(Request $request)
    {
        $request->validate([
            'leavetype' => 'required',
            'number_of_days' => 'required|integer|min:1',
            'leave_date_from' => 'required|date',
            'leave_date_to' => 'required|date|after_or_equal:leave_date_from',
            'leavereason' => 'required|string',
            'leaveattachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('user')->user();
        if (!$user) {
            return redirect()->route('index')->withErrors(['auth' => 'You must be logged in to submit a leave request.']);
        }
        $branchid = null;
        if (!empty($user->branchname)) {
            $branch = Branches::where('branchname', $user->branchname)->first();
            if ($branch) {
                $branchid = $branch->id;
            }
        }
        if (empty($branchid)) {
            return redirect()->back()->withErrors(['branchid' => 'Your branch information is missing or invalid. Please contact your administrator.']);
        }

        $timeoff = new TimeOff();
        $timeoff->leaveclientid = $user->clientid ?? null;
        $timeoff->leavebranchid = $branchid;
        $timeoff->leaveby = $user->employeenumber;
        $timeoff->leavetype = $request->leavetype;
        $timeoff->leaverequestdate = now('Asia/Manila')->toDateTimeString();
        $timeoff->leavedatefrom = $request->leave_date_from;
        $timeoff->leavedateto = $request->leave_date_to;
        $timeoff->leavedays = $request->number_of_days;
        $timeoff->leavereason = $request->leavereason;
        $timeoff->leavestatus = 'pending';

        // Handle attachment
        if ($request->hasFile('leaveattachment')) {
            $file = $request->file('leaveattachment');
            $filename = $user->employeenumber . '-' . $request->leavetype . '_' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('private/timeoff', $filename); // Store in storage/app/private/timeoff
            $timeoff->leaveattachment = $filename;
        }

        $timeoff->save();

        return redirect()->route('user_timeoff')->with('success', 'Leave request submitted successfully.');
    }

    /**
     *  User All TimeOff View route
     */
    public function user_alltimeoff(Request $request)
    {
        $admins = Admin::all()->keyBy('id');
        $user = Auth::guard('user')->user();

        // allow passing a `status` query parameter; default to 'all'
        $status = $request->query('status', 'all');
        $allowed = ['all', 'pending', 'approved', 'disapproved', 'cancelled'];
        if (!in_array($status, $allowed)) {
            $status = 'all';
        }

        $query = TimeOff::where('leaveby', $user->employeenumber)
            ->orderBy('leavestatus', 'desc')
            ->orderBy('leaverequestdate', 'desc');

        if ($status !== 'all') {
            $query->where('leavestatus', $status);
        }

        $timeOffs = $query->get();

        return view('user.apps.timeoff.alltimeoff', compact('timeOffs', 'admins', 'status'));
    }

    /**
     *  User TimeOff Attachment View
     */
    public function user_viewAttachment($id)
    {
        $user = Auth::guard('user')->user();
        $timeOff = TimeOff::findOrFail($id);
        // Strict check: Only owner can access
        if ($timeOff->leaveby !== $user->employeenumber) {
            abort(404, 'not found');
        }
        $filePath = 'private/timeoff/' . $timeOff->leaveattachment;
        if (!$timeOff->leaveattachment || !Storage::exists($filePath)) {
            abort(404, 'Attachment not found.');
        }
        $mimeType = Storage::mimeType($filePath);
        $absolutePath = Storage::path($filePath);
        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $timeOff->leaveattachment . '"'
        ]);
    }

    /**
     *  User Cancel TimeOff Submit route
     */
    public function user_cancel_timeoff(Request $request, $id)
    {
        $timeOff = TimeOff::findOrFail($id);
        $timeOff->leavestatus = 'cancelled';
        $timeOff->save();
        return redirect()->back()->with('success', 'Request cancelled successfully.');
    }

    /**
     *  User Edit TimeOff Submit route
     */
    public function user_edit_timeoff_submit(Request $request, $id)
    {
        $request->validate([
            'leavetype' => 'required',
            'number_of_days' => 'required|integer|min:1',
            'leave_date_from' => 'required|date',
            'leave_date_to' => 'required|date|after_or_equal:leave_date_from',
            'leavereason' => 'required|string',
            'leaveattachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::guard('user')->user();
        $timeoff = TimeOff::findOrFail($id);
        if ($timeoff->leaveby !== $user->employeenumber) {
            abort(403, 'Unauthorized access');
        }

        $branchid = $timeoff->leavebranchid;
        if (!empty($user->branchname)) {
            $branch = Branches::where('branchname', $user->branchname)->first();
            if ($branch) {
                $branchid = $branch->id;
            }
        }
        if (empty($branchid)) {
            return redirect()->back()->withErrors(['branchid' => 'Your branch information is missing or invalid. Please contact your administrator.']);
        }

        $timeoff->leavebranchid = $branchid;
        $timeoff->leavetype = $request->leavetype;
        $timeoff->leavedatefrom = $request->leave_date_from;
        $timeoff->leavedateto = $request->leave_date_to;
        $timeoff->leavedays = $request->number_of_days;
        $timeoff->leavereason = $request->leavereason;

        // Handle attachment update
        if ($request->hasFile('leaveattachment')) {
            // Delete old attachment if exists
            if ($timeoff->leaveattachment && Storage::exists('private/timeoff/' . $timeoff->leaveattachment)) {
                Storage::delete('private/timeoff/' . $timeoff->leaveattachment);
            }
            $file = $request->file('leaveattachment');
            $filename = $user->employeenumber . '-' . $request->leavetype . '_' . date('YmdHis') . '.' . $file->getClientOriginalExtension();
            $file->storeAs('private/timeoff', $filename);
            $timeoff->leaveattachment = $filename;
        }

        $timeoff->save();

        return redirect()->route('user_alltimeoff')->with('success', 'Leave request updated successfully.');
    }
    // =========================================


    // =========================================
    // TimeOff for Admin Routes
    //

    /**
     *  Admin TimeOff View route
     */
    public function admin_timeoff()
    {
        // Get active users
        $activeUsers = User::where('isactive', 1)->pluck('employeenumber')->all();

        // Only show timeoff for active users
        $timeOff = TimeOff::whereIn('leaveby', $activeUsers)
            ->orderBy('leavestatus', 'desc')
            ->orderBy('leaverequestdate', 'desc')
            ->get();

        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();

        $timeOffCountPending = TimeOff::whereIn('leaveby', $activeUsers)->where('leavestatus', 'pending')->count();
        $timeOffCountApproved = TimeOff::whereIn('leaveby', $activeUsers)->where('leavestatus', 'approved')->count();
        $timeOffCountDisapproved = TimeOff::whereIn('leaveby', $activeUsers)->where('leavestatus', 'disapproved')->count();
        $timeOffCountCancelled = TimeOff::whereIn('leaveby', $activeUsers)->where('leavestatus', 'cancelled')->count();
        $timeOffCountAll = $timeOffCountPending + $timeOffCountApproved + $timeOffCountDisapproved + $timeOffCountCancelled;

        return view('admin.apps.timeoff.timeoff', compact(
            'timeOff',
            'holidays',
            'timeOffCountAll',
            'timeOffCountPending',
            'timeOffCountApproved',
            'timeOffCountDisapproved',
            'timeOffCountCancelled'
        ));
    }

    /**
     *  Admin All TimeOff View route
     */
    public function admin_alltimeoff(Request $request)
    {
        // Keyed collections for O(1) lookup in blade
        $admins = Admin::all()->keyBy('id');
        $users = User::where('isactive', 1)->get()->keyBy('employeenumber');

        // allow passing a `status` query parameter; default to 'all'
        $status = $request->query('status', 'all');
        $allowed = ['all', 'pending', 'approved', 'disapproved', 'cancelled'];
        if (!in_array($status, $allowed)) {
            // fallback to 'all' for unknown values
            $status = 'all';
        }

        // Only show timeoff for users who are active
        $activeEmployeeNumbers = $users->keys()->all();

        $query = TimeOff::whereIn('leaveby', $activeEmployeeNumbers)
            ->orderBy('leavestatus', 'desc')
            ->orderBy('leaverequestdate', 'desc');

        if ($status !== 'all') {
            $query->where('leavestatus', $status);
        }

        $timeOffs = $query->get();

        return view('admin.apps.timeoff.alltimeoff', compact('timeOffs', 'admins', 'users', 'status'));
    }

    /**
     *  Admin TimeOff Attachment View
     */
    public function admin_viewAttachment($id)
    {
        $timeOff = TimeOff::findOrFail($id);
        // Admins can view any attachment, no owner check
        $filePath = 'private/timeoff/' . $timeOff->leaveattachment;
        if (!$timeOff->leaveattachment || !Storage::exists($filePath)) {
            abort(404, 'Attachment not found.');
        }
        $mimeType = Storage::mimeType($filePath);
        $absolutePath = Storage::path($filePath);
        return response()->file($absolutePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $timeOff->leaveattachment . '"'
        ]);
    }

    /**
     *  Admin TimeOff Approve
     */
    public function admin_approve_timeoff_submit(Request $request, $id)
    {
        $request->validate([
            'leaveremarks' => 'nullable|string',
        ]);
        $timeoff = TimeOff::findOrFail($id);
        $timeoff->leaveapprovedby = Auth::guard('admin')->user()->id;
        $timeoff->leaveapproveddate = now('Asia/Manila')->toDateTimeString();
        $timeoff->leaveremarks = $request->leaveremarks;
        $timeoff->leavestatus = 'approved';
        $timeoff->update();

        return redirect()->route('admin_alltimeoff')->with('success', 'Leave request approved successfully.');
    }

    /**
     *  Admin TimeOff Approve
     */
    public function admin_disapprove_timeoff_submit(Request $request, $id)
    {
        $request->validate([
            'leaveremarks' => 'nullable|string',
        ]);
        $timeoff = TimeOff::findOrFail($id);
        $timeoff->leaveapprovedby = Auth::guard('admin')->user()->id;
        $timeoff->leaveapproveddate = now('Asia/Manila')->toDateTimeString();
        $timeoff->leaveremarks = $request->leaveremarks;
        $timeoff->leavestatus = 'disapproved';
        $timeoff->update();

        return redirect()->route('admin_alltimeoff')->with('success', 'Leave request disapproved successfully.');
    }
    // =========================================


    // =========================================
    // TimeOff for Client admin Routes
    //

    /**
     *  Client TimeOff View route
     */
    public function clientadmin_timeoff()
    {
        $clientAdmin = Auth::guard('clientadmin')->user();
        $clientId = $clientAdmin->clientid ?? $clientAdmin->id;

        // Get active users for this client
        $activeUsers = User::where('isactive', 1)->pluck('employeenumber')->all();

        $timeOff = TimeOff::where('leaveclientid', $clientId)
            ->whereIn('leaveby', $activeUsers)
            ->orderBy('leavestatus', 'desc')
            ->orderBy('leaverequestdate', 'desc')
            ->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();

        // Counts scoped to this client and active users
        $timeOffCountPending = TimeOff::where('leaveclientid', $clientId)
            ->whereIn('leaveby', $activeUsers)
            ->where('leavestatus', 'pending')->count();
        $timeOffCountApproved = TimeOff::where('leaveclientid', $clientId)
            ->whereIn('leaveby', $activeUsers)
            ->where('leavestatus', 'approved')->count();
        $timeOffCountDisapproved = TimeOff::where('leaveclientid', $clientId)
            ->whereIn('leaveby', $activeUsers)
            ->where('leavestatus', 'disapproved')->count();
        $timeOffCountCancelled = TimeOff::where('leaveclientid', $clientId)
            ->whereIn('leaveby', $activeUsers)
            ->where('leavestatus', 'cancelled')->count();
        $timeOffCountAll = $timeOffCountPending + $timeOffCountApproved + $timeOffCountDisapproved + $timeOffCountCancelled;

        return view('clientadmin.apps.timeoff.timeoff', compact('timeOff', 'holidays', 'timeOffCountAll', 'timeOffCountPending', 'timeOffCountApproved', 'timeOffCountDisapproved', 'timeOffCountCancelled'));
    }

    /**
     *  Client All TimeOff View route
     */
    public function clientadmin_alltimeoff(Request $request)
    {
        $clientAdmin = Auth::guard('clientadmin')->user();
        $clientId = $clientAdmin->clientid ?? $clientAdmin->id;

        // Keyed collections for O(1) lookup in blade
        $admins = Admin::all()->keyBy('id');
        $users = User::where('isactive', 1)->get()->keyBy('employeenumber');

        // Only show timeoff for users who are active
        $activeEmployeeNumbers = $users->keys()->all();

        // allow passing a `status` query parameter; default to 'all'
        $status = $request->query('status', 'all');
        $allowed = ['all', 'pending', 'approved', 'disapproved', 'cancelled'];
        if (!in_array($status, $allowed)) {
            $status = 'all';
        }

        $query = TimeOff::where('leaveclientid', $clientId)
            ->whereIn('leaveby', $activeEmployeeNumbers)
            ->orderBy('leavestatus', 'desc')
            ->orderBy('leaverequestdate', 'desc');

        if ($status !== 'all') {
            $query->where('leavestatus', $status);
        }

        $timeOffs = $query->get();

        return view('clientadmin.apps.timeoff.alltimeoff', compact('timeOffs', 'admins', 'users', 'status'));
    }

    // =========================================




    // =========================================
}
