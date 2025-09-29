<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TimeLogController extends Controller
{

    // =========================================
    // TimeLog for User Routes
    //

    /**
     *  User TimeLog View route
     */
    public function user_timelog()
    {
        $user = Auth::guard('user')->user();
        if (! $user) {
            return redirect()->route('index')->withErrors(['auth' => 'You must be logged in to view timelogs.']);
        }

        // Force Manila timezone for all timelog date/times
        $tz = 'Asia/Manila';
        $today = Carbon::now($tz)->toDateString();

        // Fetch today's actions (single query) and determine available actions
        $actions = DB::table('timelog')
            ->where('employeenumber', $user->employeenumber)
            ->whereDate('recorded_at', $today)
            ->orderBy('recorded_at', 'asc')
            ->pluck('action')
            ->toArray();

        $hasClockIn = in_array('clock_in', $actions, true);
        $hasClockOut = in_array('clock_out', $actions, true);

        $lastAction = count($actions) ? end($actions) : null;

        $canClockIn = false;
        $canClockOut = false;

        if ($hasClockIn && $hasClockOut) {
            // already has a complete pair for today
            $canClockIn = $canClockOut = false;
        } elseif ($lastAction === 'clock_in') {
            $canClockOut = true;
        } else {
            // no logs or last was clock_out
            $canClockIn = true;
        }

        // Fetch all timelog records for the authenticated user (most recent first)
        // Range filtering support: daily, weekly, monthly, yearly, all
        $selectedRange = request()->get('range', 'all');
        $allowedRanges = ['daily', 'weekly', 'monthly', 'yearly', 'all'];
        if (! in_array($selectedRange, $allowedRanges, true)) {
            $selectedRange = 'all';
        }

        $query = DB::table('timelog')
            ->where('employeenumber', $user->employeenumber);

        if ($selectedRange !== 'all') {
            // compute Manila range boundaries (dates)
            switch ($selectedRange) {
                case 'daily':
                    $start = Carbon::now($tz)->startOfDay();
                    $end = Carbon::now($tz)->endOfDay();
                    break;
                case 'weekly':
                    // Use Sunday as the start of the week so 'weekly' matches typical PH expectation
                    $start = Carbon::now($tz)->startOfWeek(Carbon::SUNDAY);
                    // End of week will be Saturday
                    $end = Carbon::now($tz)->endOfWeek(Carbon::SATURDAY);
                    break;
                case 'monthly':
                    $start = Carbon::now($tz)->startOfMonth();
                    $end = Carbon::now($tz)->endOfMonth();
                    break;
                case 'yearly':
                    $start = Carbon::now($tz)->startOfYear();
                    $end = Carbon::now($tz)->endOfYear();
                    break;
                default:
                    $start = $end = null;
            }

            if (isset($start) && isset($end)) {
                // Compare by DATE to avoid time-string/timezone mismatches in DB storage
                $query->whereBetween(DB::raw('DATE(recorded_at)'), [$start->toDateString(), $end->toDateString()]);
            }
        }

        $timelogDays = $query->orderBy('recorded_at', 'desc')->get();

        // Parse JSON meta and attach convenient properties for the view
        $timelogDays = collect($timelogDays)->map(function ($row) use ($tz) {
            $row = (array) $row;
            // keep as object for blade compatibility
            $obj = (object) $row;

            $obj->meta_parsed = null;
            $obj->lat = $obj->lng = null;

            if (! empty($obj->meta)) {
                try {
                    $obj->meta_parsed = json_decode($obj->meta, true);
                    if (is_array($obj->meta_parsed) && isset($obj->meta_parsed['geo']) && is_array($obj->meta_parsed['geo'])) {
                        $obj->lat = $obj->meta_parsed['geo']['lat'] ?? null;
                        $obj->lng = $obj->meta_parsed['geo']['lng'] ?? null;
                    }
                } catch (\Throwable $e) {
                    $obj->meta_parsed = null;
                }
            }

            // normalize recorded_at to Carbon instance in Manila timezone for easier formatting in blade
            try {
                $obj->recorded_at_dt = Carbon::parse($obj->recorded_at, $tz);
            } catch (\Throwable $e) {
                $obj->recorded_at_dt = null;
            }

            return $obj;
        });

        // Pass selectedRange so the view can access it directly if needed
        return view('user.apps.timelog.timelog', compact('user', 'canClockIn', 'canClockOut', 'timelogDays', 'selectedRange'));
    }

    /**
     *  Usert TimeLog Clock-In route
     */
    public function user_clock_in(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string|max:255',
            'device' => 'nullable|string|max:128',
            'meta' => 'nullable|array',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::guard('user')->user();
        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('index')->withErrors(['auth' => 'You must be logged in to clock in.']);
        }

        // Prevent consecutive clock_in without clock_out
        $last = DB::table('timelog')
            ->where('employeenumber', $user->employeenumber)
            ->orderBy('recorded_at', 'desc')
            ->first();

        if ($last && isset($last->action) && $last->action === 'clock_in') {
            $message = 'You are already clocked in. Please clock out before clocking in again.';
            if ($request->wantsJson()) {
                return response()->json(['error' => $message], 409);
            }
            return redirect()->back()->withErrors(['timelog' => $message]);
        }

        // Force Manila timezone for timestamps
        $tz = 'Asia/Manila';
        $now = Carbon::now($tz);
        $nowDt = $now->toDateTimeString();

        // Collect geo inputs if provided
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $accuracy = $request->input('accuracy');

        // Prepare meta payload: merge user-provided meta array with geo and branchname info
        $metaUser = $request->input('meta') ?: [];
        $metaUser['geo'] = null;
        if (! is_null($latitude) && ! is_null($longitude)) {
            $metaUser['geo'] = [
                'lat' => floatval($latitude),
                'lng' => floatval($longitude),
            ];
            if (! is_null($accuracy)) {
                $metaUser['geo']['accuracy'] = floatval($accuracy);
            }
        }
        if (! empty($user->branchname)) {
            $metaUser['branchname'] = $user->branchname;
        }

        // human readable location: prefer explicit location field, else lat,lng
        $humanLocation = $request->input('location');
        if (empty($humanLocation) && isset($metaUser['geo']) && $metaUser['geo']) {
            $humanLocation = $metaUser['geo']['lat'] . ',' . $metaUser['geo']['lng'];
        }
        $humanLocation = $humanLocation ? Str::limit($humanLocation, 255) : null;

        $payload = [
            'client_id' => $user->clientid ?? null,
            'user_id' => $user->id ?? null,
            'employeenumber' => $user->employeenumber ?? null,
            'branch_name' => $user->branchname ?? null,
            'action' => 'clock_in',
            'recorded_at' => $nowDt,
            'timezone' => $now->getTimezone()->getName() ?? $tz,
            'duration_seconds' => null,
            'device' => $request->input('device') ?? $request->header('User-Agent'),
            'ip_address' => $request->ip(),
            'location' => $humanLocation,
            'meta' => ! empty($metaUser) ? json_encode($metaUser) : null,
            'created_at' => $nowDt,
            'updated_at' => $nowDt,
        ];

        $id = DB::table('timelog')->insertGetId($payload);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $id], 201);
        }

        return redirect()->back()->with('success', 'Clock-in recorded successfully.');
    }

    /**
     *  Usert TimeLog Clock-Out route
     */
    public function user_clock_out(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string|max:255',
            'device' => 'nullable|string|max:128',
            'meta' => 'nullable|array',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0',
        ]);

        $user = Auth::guard('user')->user();
        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return redirect()->route('index')->withErrors(['auth' => 'You must be logged in to clock out.']);
        }

        // Find last timelog entry for this user
        $last = DB::table('timelog')
            ->where('employeenumber', $user->employeenumber)
            ->orderBy('recorded_at', 'desc')
            ->first();

        if (! $last || ! isset($last->action) || $last->action !== 'clock_in') {
            $message = 'No active clock-in found. Please clock in before clocking out.';
            if ($request->wantsJson()) {
                return response()->json(['error' => $message], 409);
            }
            return redirect()->back()->withErrors(['timelog' => $message]);
        }

        // Force Manila timezone for timestamps
        $tz = 'Asia/Manila';
        $now = Carbon::now($tz);
        $nowDt = $now->toDateTimeString();

        // compute duration in seconds between last recorded_at and now
        try {
            // parse last recorded_at as Manila time to compute accurate duration
            $start = Carbon::parse($last->recorded_at, $tz);
            $durationSeconds = max(0, $now->diffInSeconds($start));
        } catch (\Exception $e) {
            $durationSeconds = null;
        }

        // Prepare geo/meta info
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $accuracy = $request->input('accuracy');

        $metaUser = $request->input('meta') ?: [];
        $metaUser['geo'] = null;
        if (! is_null($latitude) && ! is_null($longitude)) {
            $metaUser['geo'] = [
                'lat' => floatval($latitude),
                'lng' => floatval($longitude),
            ];
            if (! is_null($accuracy)) {
                $metaUser['geo']['accuracy'] = floatval($accuracy);
            }
        }

        // For clock-out, store the user's branchname as branch_name
        $branchNameValue = $user->branchname ?? null;

        // human readable location: prefer explicit input, then geo, then last entry
        $humanLocation = $request->input('location');
        if (empty($humanLocation)) {
            if (isset($metaUser['geo']) && $metaUser['geo']) {
                $humanLocation = $metaUser['geo']['lat'] . ',' . $metaUser['geo']['lng'];
            } elseif (isset($last->location) && $last->location) {
                $humanLocation = $last->location;
            }
        }
        $humanLocation = $humanLocation ? Str::limit($humanLocation, 255) : null;

        // Use transaction to update previous record and insert clock_out atomically
        try {
            DB::beginTransaction();

            if (! is_null($durationSeconds) && isset($last->id)) {
                DB::table('timelog')->where('id', $last->id)->update([
                    'duration_seconds' => $durationSeconds,
                    'updated_at' => $nowDt,
                ]);
            }

            $payload = [
                'client_id' => $user->clientid ?? null,
                'user_id' => $user->id ?? null,
                'employeenumber' => $user->employeenumber ?? null,
                'branch_name' => $branchNameValue,
                'action' => 'clock_out',
                'recorded_at' => $nowDt,
                'timezone' => $now->getTimezone()->getName() ?? $tz,
                'duration_seconds' => $durationSeconds,
                'device' => $request->input('device') ?? $request->header('User-Agent'),
                'ip_address' => $request->ip(),
                'location' => $humanLocation,
                'meta' => ! empty($metaUser) ? json_encode($metaUser) : null,
                'created_at' => $nowDt,
                'updated_at' => $nowDt,
            ];

            $id = DB::table('timelog')->insertGetId($payload);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Failed to record clock-out.'], 500);
            }
            return redirect()->back()->withErrors(['timelog' => 'Failed to record clock-out.']);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'id' => $id, 'duration_seconds' => $durationSeconds], 201);
        }

        return redirect()->back()->with('success', 'Clock-out recorded successfully.');
    }

    // TimeLog for User Routes
    // =========================================




    // =========================================
    // TimeLog for Client admin Routes
    //

    public function clientadmin_timelog()
    {
        $user = Auth::guard('clientadmin')->user();
        if (! $user) {
            return redirect()->route('index')->withErrors(['auth' => 'You must be logged in to view timelogs.']);
        }

        // safe flags for view (not applicable for clientadmin but keep for compatibility)
        $canClockIn = $canClockOut = null;

        // Force Manila timezone for all timelog date/times
        $tz = 'Asia/Manila';
        $today = Carbon::now($tz)->toDateString();

        // Range filtering support: daily, weekly, monthly, yearly, all
        $selectedRange = request()->get('range', 'all');
        $allowedRanges = ['daily', 'weekly', 'monthly', 'yearly', 'all'];
        if (! in_array($selectedRange, $allowedRanges, true)) {
            $selectedRange = 'all';
        }

        // Branch filtering support (optional)
        $selectedBranch = request()->get('branch', 'all');

        // Base query for client's timelogs
        // Join users to pull employee name/branch where available. Left join so timelog-only rows still appear.
        $query = DB::table('timelog')
            ->leftJoin('users', function ($join) use ($user) {
                $join->on('timelog.employeenumber', '=', 'users.employeenumber')
                    ->where('users.clientid', '=', $user->clientid);
            })
            ->where('timelog.client_id', $user->clientid)
            ->select(
                'timelog.*',
                'users.firstname',
                'users.lastname',
                DB::raw('users.employeenumber as user_employeenumber'),
                DB::raw('users.branchname as user_branchname')
            );

        // Build list of branches for this client (for UI dropdowns)
        $branches = DB::table('timelog')
            ->where('client_id', $user->clientid)
            ->whereNotNull('branch_name')
            ->where('branch_name', '!=', '')
            ->distinct()
            ->orderBy('branch_name')
            ->pluck('branch_name')
            ->values();

        // Apply branch filter if requested
        if ($selectedBranch !== 'all' && $selectedBranch !== null && $selectedBranch !== '') {
            $query->where('timelog.branch_name', $selectedBranch);
        }

        if ($selectedRange !== 'all') {
            // compute Manila range boundaries (dates)
            switch ($selectedRange) {
                case 'daily':
                    $start = Carbon::now($tz)->startOfDay();
                    $end = Carbon::now($tz)->endOfDay();
                    break;
                case 'weekly':
                    // Use Sunday as the start of the week so 'weekly' matches typical PH expectation
                    $start = Carbon::now($tz)->startOfWeek(Carbon::SUNDAY);
                    // End of week will be Saturday
                    $end = Carbon::now($tz)->endOfWeek(Carbon::SATURDAY);
                    break;
                case 'monthly':
                    $start = Carbon::now($tz)->startOfMonth();
                    $end = Carbon::now($tz)->endOfMonth();
                    break;
                case 'yearly':
                    $start = Carbon::now($tz)->startOfYear();
                    $end = Carbon::now($tz)->endOfYear();
                    break;
                default:
                    $start = $end = null;
            }

            if (isset($start) && isset($end)) {
                // Compare by DATE to avoid time-string/timezone mismatches in DB storage
                $query->whereBetween(DB::raw('DATE(recorded_at)'), [$start->toDateString(), $end->toDateString()]);
            }
        }

        // Use server-side pagination to avoid loading huge datasets into memory
        $perPage = 100; // reasonable default; adjust if needed
        $timelogPaginated = $query->orderBy('recorded_at', 'desc')->paginate($perPage);

        // Map only the items on the current page for performance
        $mapped = collect($timelogPaginated->items())->map(function ($row) use ($tz) {
            $row = (array) $row;
            $obj = (object) $row; // keep as object for blade compatibility

            $obj->meta_parsed = null;
            $obj->lat = $obj->lng = null;
            if (! empty($obj->meta)) {
                try {
                    $obj->meta_parsed = json_decode($obj->meta, true);
                    if (is_array($obj->meta_parsed) && isset($obj->meta_parsed['geo']) && is_array($obj->meta_parsed['geo'])) {
                        $obj->lat = $obj->meta_parsed['geo']['lat'] ?? null;
                        $obj->lng = $obj->meta_parsed['geo']['lng'] ?? null;
                    }
                } catch (\Throwable $e) {
                    $obj->meta_parsed = null;
                }
            }

            try {
                $obj->recorded_at_dt = Carbon::parse($obj->recorded_at, $tz);
            } catch (\Throwable $e) {
                $obj->recorded_at_dt = null;
            }

            // Prefer branch stored on timelog, fallback to user's branchname from users table
            if (empty($obj->branch_name) && ! empty($obj->user_branchname)) {
                $obj->branch_name = $obj->user_branchname;
            }

            // Employee display values
            $fullname = trim((($obj->firstname ?? '') . ' ' . ($obj->lastname ?? '')));
            $obj->employee_name = $fullname !== '' ? $fullname : ($obj->employeenumber ?? 'Unknown');
            $obj->employee_employeenumber = $obj->user_employeenumber ?? $obj->employeenumber ?? null;
            $obj->employee_branch = $obj->user_branchname ?? $obj->branch_name ?? null;

            return $obj;
        })->values();

        // Attach mapped collection back to paginator so the view can use both the paginator (for links)
        // and the collection (for grouping) without re-querying.
        $timelogPaginated->setCollection($mapped);
        $timelogCollection = $timelogPaginated->getCollection();

        // Pass selectedRange, selectedBranch, branches, paginator and collection to the view
        return view('clientadmin.apps.timelog.timelog', compact('user', 'canClockIn', 'canClockOut', 'timelogPaginated', 'timelogCollection', 'selectedRange', 'selectedBranch', 'branches'));
    }

    // TimeLog for Client admin Routes
    // =========================================



    // =========================================
    // TimeLog for Admin Routes
    //

    public function admin_timelog()
    {
        $user = Auth::guard('admin')->user();
        if (! $user) {
            return redirect()->route('index')->withErrors(['auth' => 'You must be logged in to view timelogs.']);
        }

        // safe flags for view (not applicable for admin but keep for compatibility)
        $canClockIn = $canClockOut = null;

        // Force Manila timezone for all timelog date/times
        $tz = 'Asia/Manila';
        $today = Carbon::now($tz)->toDateString();

        // Range filtering support: daily, weekly, monthly, yearly, all
        $selectedRange = request()->get('range', 'all');
        $allowedRanges = ['daily', 'weekly', 'monthly', 'yearly', 'all'];
        if (! in_array($selectedRange, $allowedRanges, true)) {
            $selectedRange = 'all';
        }

        // Branch filtering support (optional)
        $selectedBranch = request()->get('branch', 'all');

        // Base query for timelogs (ADMIN: view ALL timelog records)
        // Left join users to pull employee name/branch where available. Do not restrict by clientid for admins.
        $query = DB::table('timelog')
            ->leftJoin('users', function ($join) {
                $join->on('timelog.employeenumber', '=', 'users.employeenumber');
            })
            ->select(
                'timelog.*',
                'users.firstname',
                'users.lastname',
                DB::raw('users.employeenumber as user_employeenumber'),
                DB::raw('users.branchname as user_branchname')
            );

        // Build list of branches across all timelogs (for UI dropdowns)
        $branches = DB::table('timelog')
            ->whereNotNull('branch_name')
            ->where('branch_name', '!=', '')
            ->distinct()
            ->orderBy('branch_name')
            ->pluck('branch_name')
            ->values();

        // Apply branch filter if requested (match either timelog.branch_name or users.branchname)
        if ($selectedBranch !== 'all' && $selectedBranch !== null && $selectedBranch !== '') {
            $query->where(function ($q) use ($selectedBranch) {
                $q->where('timelog.branch_name', $selectedBranch)
                  ->orWhere('users.branchname', $selectedBranch);
            });
        }

        if ($selectedRange !== 'all') {
            // compute Manila range boundaries (dates)
            switch ($selectedRange) {
                case 'daily':
                    $start = Carbon::now($tz)->startOfDay();
                    $end = Carbon::now($tz)->endOfDay();
                    break;
                case 'weekly':
                    // Use Sunday as the start of the week
                    $start = Carbon::now($tz)->startOfWeek(Carbon::SUNDAY);
                    $end = Carbon::now($tz)->endOfWeek(Carbon::SATURDAY);
                    break;
                case 'monthly':
                    $start = Carbon::now($tz)->startOfMonth();
                    $end = Carbon::now($tz)->endOfMonth();
                    break;
                case 'yearly':
                    $start = Carbon::now($tz)->startOfYear();
                    $end = Carbon::now($tz)->endOfYear();
                    break;
                default:
                    $start = $end = null;
            }

            if (isset($start) && isset($end)) {
                // Compare by DATE to avoid time-string/timezone mismatches in DB storage
                $query->whereBetween(DB::raw('DATE(recorded_at)'), [$start->toDateString(), $end->toDateString()]);
            }
        }

        // Use server-side pagination to avoid loading huge datasets into memory
        $perPage = 100; // reasonable default; adjust if needed
        $timelogPaginated = $query->orderBy('recorded_at', 'desc')->paginate($perPage);

        // Map only the items on the current page for performance
        $mapped = collect($timelogPaginated->items())->map(function ($row) use ($tz) {
            $row = (array) $row;
            $obj = (object) $row; // keep as object for blade compatibility

            $obj->meta_parsed = null;
            $obj->lat = $obj->lng = null;
            if (! empty($obj->meta)) {
                try {
                    $obj->meta_parsed = json_decode($obj->meta, true);
                    if (is_array($obj->meta_parsed) && isset($obj->meta_parsed['geo']) && is_array($obj->meta_parsed['geo'])) {
                        $obj->lat = $obj->meta_parsed['geo']['lat'] ?? null;
                        $obj->lng = $obj->meta_parsed['geo']['lng'] ?? null;
                    }
                } catch (\Throwable $e) {
                    $obj->meta_parsed = null;
                }
            }

            try {
                $obj->recorded_at_dt = Carbon::parse($obj->recorded_at, $tz);
            } catch (\Throwable $e) {
                $obj->recorded_at_dt = null;
            }

            // Prefer branch stored on timelog, fallback to user's branchname from users table
            if (empty($obj->branch_name) && ! empty($obj->user_branchname)) {
                $obj->branch_name = $obj->user_branchname;
            }

            // Employee display values
            $fullname = trim((($obj->firstname ?? '') . ' ' . ($obj->lastname ?? '')));
            $obj->employee_name = $fullname !== '' ? $fullname : ($obj->employeenumber ?? 'Unknown');
            $obj->employee_employeenumber = $obj->user_employeenumber ?? $obj->employeenumber ?? null;
            $obj->employee_branch = $obj->user_branchname ?? $obj->branch_name ?? null;

            return $obj;
        })->values();

        // Attach mapped collection back to paginator so the view can use both the paginator (for links)
        // and the collection (for grouping) without re-querying.
        $timelogPaginated->setCollection($mapped);
        $timelogCollection = $timelogPaginated->getCollection();

        // Pass selectedRange, selectedBranch, branches, paginator and collection to the view
        return view('admin.apps.timelog.timelog', compact('user', 'canClockIn', 'canClockOut', 'timelogPaginated', 'timelogCollection', 'selectedRange', 'selectedBranch', 'branches'));
    }

    // TimeLog for Admin Routes
    // =========================================

    // =========================================
    // TimeLog for Superdmin Routes
    //

    public function superadmin_timelog()
    {
        $user = Auth::guard('superadmin')->user();
        if (! $user) {
            return redirect()->route('index')->withErrors(['auth' => 'You must be logged in to view timelogs.']);
        }

        // safe flags for view (not applicable for admin but keep for compatibility)
        $canClockIn = $canClockOut = null;

        // Force Manila timezone for all timelog date/times
        $tz = 'Asia/Manila';
        $today = Carbon::now($tz)->toDateString();

        // Range filtering support: daily, weekly, monthly, yearly, all
        $selectedRange = request()->get('range', 'all');
        $allowedRanges = ['daily', 'weekly', 'monthly', 'yearly', 'all'];
        if (! in_array($selectedRange, $allowedRanges, true)) {
            $selectedRange = 'all';
        }

        // Branch filtering support (optional)
        $selectedBranch = request()->get('branch', 'all');

        // Base query for timelogs (ADMIN: view ALL timelog records)
        // Left join users to pull employee name/branch where available. Do not restrict by clientid for admins.
        $query = DB::table('timelog')
            ->leftJoin('users', function ($join) {
                $join->on('timelog.employeenumber', '=', 'users.employeenumber');
            })
            ->select(
                'timelog.*',
                'users.firstname',
                'users.lastname',
                DB::raw('users.employeenumber as user_employeenumber')
            );

        // Build list of branches across all timelogs (for UI dropdowns)
        $branches = DB::table('timelog')
            ->whereNotNull('branch_name')
            ->where('branch_name', '!=', '')
            ->distinct()
            ->orderBy('branch_name')
            ->pluck('branch_name')
            ->values();

        // Apply branch filter if requested (match only timelog.branch_name)
        if ($selectedBranch !== 'all' && $selectedBranch !== null && $selectedBranch !== '') {
            $query->where('timelog.branch_name', $selectedBranch);
        }

        if ($selectedRange !== 'all') {
            // compute Manila range boundaries (dates)
            switch ($selectedRange) {
                case 'daily':
                    $start = Carbon::now($tz)->startOfDay();
                    $end = Carbon::now($tz)->endOfDay();
                    break;
                case 'weekly':
                    // Use Sunday as the start of the week
                    $start = Carbon::now($tz)->startOfWeek(Carbon::SUNDAY);
                    $end = Carbon::now($tz)->endOfWeek(Carbon::SATURDAY);
                    break;
                case 'monthly':
                    $start = Carbon::now($tz)->startOfMonth();
                    $end = Carbon::now($tz)->endOfMonth();
                    break;
                case 'yearly':
                    $start = Carbon::now($tz)->startOfYear();
                    $end = Carbon::now($tz)->endOfYear();
                    break;
                default:
                    $start = $end = null;
            }

            if (isset($start) && isset($end)) {
                // Compare by DATE to avoid time-string/timezone mismatches in DB storage
                $query->whereBetween(DB::raw('DATE(recorded_at)'), [$start->toDateString(), $end->toDateString()]);
            }
        }

        // Use server-side pagination to avoid loading huge datasets into memory
        $perPage = 100; // reasonable default; adjust if needed
        $timelogPaginated = $query->orderBy('recorded_at', 'desc')->paginate($perPage);

        // Map only the items on the current page for performance
        $mapped = collect($timelogPaginated->items())->map(function ($row) use ($tz) {
            $row = (array) $row;
            $obj = (object) $row; // keep as object for blade compatibility

            $obj->meta_parsed = null;
            $obj->lat = $obj->lng = null;
            if (! empty($obj->meta)) {
                try {
                    $obj->meta_parsed = json_decode($obj->meta, true);
                    if (is_array($obj->meta_parsed) && isset($obj->meta_parsed['geo']) && is_array($obj->meta_parsed['geo'])) {
                        $obj->lat = $obj->meta_parsed['geo']['lat'] ?? null;
                        $obj->lng = $obj->meta_parsed['geo']['lng'] ?? null;
                    }
                } catch (\Throwable $e) {
                    $obj->meta_parsed = null;
                }
            }

            try {
                $obj->recorded_at_dt = Carbon::parse($obj->recorded_at, $tz);
            } catch (\Throwable $e) {
                $obj->recorded_at_dt = null;
            }

            // Employee display values
            $fullname = trim((($obj->firstname ?? '') . ' ' . ($obj->lastname ?? '')));
            $obj->employee_name = $fullname !== '' ? $fullname : ($obj->employeenumber ?? 'Unknown');
            $obj->employee_employeenumber = $obj->user_employeenumber ?? $obj->employeenumber ?? null;
            $obj->employee_branch = $obj->branch_name ?? null;

            return $obj;
        })->values();

        // Attach mapped collection back to paginator so the view can use both the paginator (for links)
        // and the collection (for grouping) without re-querying.
        $timelogPaginated->setCollection($mapped);
        $timelogCollection = $timelogPaginated->getCollection();

        // Pass selectedRange, selectedBranch, branches, paginator and collection to the view
        return view('superadmin.apps.timelog.timelog', compact('user', 'canClockIn', 'canClockOut', 'timelogPaginated', 'timelogCollection', 'selectedRange', 'selectedBranch', 'branches'));
    }

    // TimeLog for Superadmin Routes
    // =========================================



}
