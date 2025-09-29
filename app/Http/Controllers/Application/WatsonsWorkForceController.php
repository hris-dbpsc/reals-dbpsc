<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Branches;
use App\Models\WorkforceWatson;
use App\Models\Holidays;
use App\Mail\Websitemail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class WatsonsWorkForceController extends Controller
{
    // =========================================
    // Watsons WorkForce for Superadmin Routes
    //

    /**
     *  App WorkForce View route
     */
    public function superadmin_watsonsworkforce()
    {
        $branches = Branches::where('isactive', 1)->orderBy('branchname')->get();
        $workforces = WorkforceWatson::orderBy('status', 'desc')->orderBy('requestdate', 'desc')->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $workforceCountAll = WorkforceWatson::whereYear('created_at', now()->year)->count();
        $workforceCountPending = WorkforceWatson::whereYear('created_at', now()->year)->where('status', 'pending')->count();
        $workforceCountCompleted = WorkforceWatson::whereYear('created_at', now()->year)->where('status', 'completed')->count();
        return view('superadmin.apps.watsonsworkforce.workforce', compact('branches', 'workforces', 'holidays', 'workforceCountAll', 'workforceCountPending', 'workforceCountCompleted'));
    }
    /**
     *  App WorkForce All Request route
     */
    public function superadmin_watsons_allworkforce()
    {
        $branches = Branches::where('isactive', 1)->get();
        $workforces = WorkforceWatson::orderBy('status', 'desc')->orderBy('requestdate', 'desc')->get();
        $areacoordinatorIds = Admin::where('role', 'areacoordinator')->pluck('id');
        $workforces_areacoordinator = WorkforceWatson::whereIn('assignedto', $areacoordinatorIds)
            ->orderBy('status', 'desc')
            ->orderBy('requestdate', 'desc')
            ->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admins = Admin::where('role', 'areacoordinator')->orderBy('lastname')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminMap = $admins->keyBy('id');
        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $transformWorkforce = function ($workforce) use ($adminMap, $adminAllMap, $holidaysArr) {
            $admin = $adminMap->get($workforce->assignedto);
            $workforce->assigned_admin_name = $admin ? "{$admin->lastname}, {$admin->firstname}" : '';
            $adminAll = $adminAllMap->get($workforce->attendedby);
            $workforce->admin_name = $adminAll ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'text-danger fw-bold';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'text-warning fw-bold';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }
            return $workforce;
        };

        $workforces->transform($transformWorkforce);
        $workforces_areacoordinator->transform($transformWorkforce);

        return view('superadmin.apps.watsonsworkforce.allworkforce', compact('branches', 'workforces', 'holidays', 'admins', 'workforces_areacoordinator'));
    }

    /**
     *  App WorkForce Pending Request route
     */
    public function superadmin_watsons_pendingworkforce()
    {
        $branches = Branches::where('isactive', 1)->get();
        $workforces = WorkforceWatson::where('status', 'pending')->orderBy('requestdate', 'desc')->get();
        $areacoordinatorIds = Admin::where('role', 'areacoordinator')->pluck('id');
        $workforces_areacoordinator = WorkforceWatson::whereIn('assignedto', $areacoordinatorIds)
            ->where('status', 'pending')
            ->orderBy('status', 'desc')
            ->orderBy('requestdate', 'desc')
            ->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admins = Admin::where('role', 'areacoordinator')->orderBy('lastname')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminMap = $admins->keyBy('id');
        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $transformWorkforce = function ($workforce) use ($adminMap, $adminAllMap, $holidaysArr) {
            $admin = $adminMap->get($workforce->assignedto);
            $workforce->assigned_admin_name = $admin ? "{$admin->lastname}, {$admin->firstname}" : '';
            $adminAll = $adminAllMap->get($workforce->attendedby);
            $workforce->admin_name = $adminAll ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'text-danger fw-bold';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'text-warning fw-bold';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }
            return $workforce;
        };

        $workforces->transform($transformWorkforce);
        $workforces_areacoordinator->transform($transformWorkforce);

        return view('superadmin.apps.watsonsworkforce.pendingworkforce', compact('branches', 'workforces', 'holidays', 'admins', 'workforces_areacoordinator'));
    }

    /**
     *  App WorkForce Completed Request route
     */
    public function superadmin_watsons_completedworkforce()
    {
        $branches = Branches::where('isactive', 1)->get();
        $workforces = WorkforceWatson::where('status', 'completed')->orderBy('requestdate', 'desc')->get();
        $areacoordinatorIds = Admin::where('role', 'areacoordinator')->pluck('id');
        $workforces_areacoordinator = WorkforceWatson::whereIn('assignedto', $areacoordinatorIds)
            ->where('status', 'completed')
            ->orderBy('status', 'desc')
            ->orderBy('requestdate', 'desc')
            ->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admins = Admin::where('role', 'areacoordinator')->orderBy('lastname')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminMap = $admins->keyBy('id');
        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $transformWorkforce = function ($workforce) use ($adminMap, $adminAllMap, $holidaysArr) {
            $admin = $adminMap->get($workforce->assignedto);
            $workforce->assigned_admin_name = $admin ? "{$admin->lastname}, {$admin->firstname}" : '';
            $adminAll = $adminAllMap->get($workforce->attendedby);
            $workforce->admin_name = $adminAll ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'text-danger fw-bold';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'text-warning fw-bold';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }
            return $workforce;
        };

        $workforces->transform($transformWorkforce);
        $workforces_areacoordinator->transform($transformWorkforce);

        return view('superadmin.apps.watsonsworkforce.completedworkforce', compact('branches', 'workforces', 'holidays', 'admins', 'workforces_areacoordinator'));
    }


    // =========================================
    // Watsons WorkForce for Admin Routes
    //

    /**
     *  App WorkForce View route
     */
    public function admin_watsonsworkforce()
    {
        $branches = Branches::where('isactive', 1)->orderBy('branchname')->get();
        $workforces = WorkforceWatson::latest('created_at')->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $role = Auth::guard('admin')->user()->role;

        // All counts for admin
        $workforceCountAll = WorkforceWatson::whereYear('created_at', now()->year)->count();
        $workforceCountPending = WorkforceWatson::whereYear('created_at', now()->year)->where('status', 'pending')->count();
        $workforceCountCompleted = WorkforceWatson::whereYear('created_at', now()->year)->where('status', 'completed')->count();

        // All counts for areacoordinator
        $areacoordinatorIds = Admin::where('role', 'areacoordinator')->pluck('id');
        $workforceCountAllAreacoordinator = WorkforceWatson::whereYear('created_at', now()->year)
            ->whereIn('assignedto', $areacoordinatorIds)
            ->count();
        $workforceCountPendingAreacoordinator = WorkforceWatson::whereYear('created_at', now()->year)
            ->whereIn('assignedto', $areacoordinatorIds)
            ->where('status', 'pending')
            ->count();
        $workforceCountCompletedAreacoordinator = WorkforceWatson::whereYear('created_at', now()->year)
            ->whereIn('assignedto', $areacoordinatorIds)
            ->where('status', 'completed')
            ->count();

        // Pass all counts to the view
        return view('admin.apps.watsonsworkforce.workforce', compact(
            'branches',
            'workforces',
            'holidays',
            'workforceCountAll',
            'workforceCountPending',
            'workforceCountCompleted',
            'workforceCountAllAreacoordinator',
            'workforceCountPendingAreacoordinator',
            'workforceCountCompletedAreacoordinator',
            'role'
        ));
    }

    /**
     *  App WorkForce All Request route
     */
    public function admin_watsons_allworkforce()
    {
        $branches = Branches::where('isactive', 1)->get();
        $workforces = WorkforceWatson::orderBy('status', 'desc')->orderBy('requestdate', 'desc')->get();
        $areacoordinatorIds = Admin::where('role', 'areacoordinator')->pluck('id');
        $workforces_areacoordinator = WorkforceWatson::whereIn('assignedto', $areacoordinatorIds)
            ->orderBy('status', 'desc')
            ->orderBy('requestdate', 'desc')
            ->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admins = Admin::where('role', 'areacoordinator')->orderBy('lastname')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminMap = $admins->keyBy('id');
        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $transformWorkforce = function ($workforce) use ($adminMap, $adminAllMap, $holidaysArr) {
            $admin = $adminMap->get($workforce->assignedto);
            $workforce->assigned_admin_name = $admin ? "{$admin->lastname}, {$admin->firstname}" : '';
            $adminAll = $adminAllMap->get($workforce->attendedby);
            $workforce->admin_name = $adminAll ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'text-danger fw-bold';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'text-warning fw-bold';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }
            return $workforce;
        };

        $workforces->transform($transformWorkforce);
        $workforces_areacoordinator->transform($transformWorkforce);

        return view('admin.apps.watsonsworkforce.allworkforce', compact('branches', 'workforces', 'holidays', 'admins', 'workforces_areacoordinator'));
    }

    /**
     *  App WorkForce Pending Request route
     */
    public function admin_watsons_pendingworkforce()
    {
        $branches = Branches::where('isactive', 1)->get();
        $workforces = WorkforceWatson::where('status', 'pending')->latest('requestdate')->get();
        $areacoordinatorIds = Admin::where('role', 'areacoordinator')->pluck('id');
        $workforces_areacoordinator = WorkforceWatson::whereIn('assignedto', $areacoordinatorIds)
            ->where('status', 'pending')
            ->orderBy('status', 'desc')
            ->orderBy('requestdate', 'desc')
            ->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admins = Admin::where('role', 'areacoordinator')->orderBy('lastname')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminMap = $admins->keyBy('id');
        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $transformWorkforce = function ($workforce) use ($adminMap, $adminAllMap, $holidaysArr) {
            $admin = $adminMap->get($workforce->assignedto);
            $workforce->assigned_admin_name = $admin ? "{$admin->lastname}, {$admin->firstname}" : '';
            $adminAll = $adminAllMap->get($workforce->attendedby);
            $workforce->admin_name = $adminAll ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'text-danger fw-bold';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'text-warning fw-bold';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }
            return $workforce;
        };

        $workforces->transform($transformWorkforce);
        $workforces_areacoordinator->transform($transformWorkforce);

        return view('admin.apps.watsonsworkforce.pendingworkforce', compact('branches', 'workforces', 'holidays', 'admins', 'workforces_areacoordinator'));
    }

    /**
     *  App WorkForce Completed Request route
     */
    public function admin_watsons_completedworkforce()
    {
        $branches = Branches::where('isactive', 1)->get();
        $workforces = WorkforceWatson::where('status', 'completed')->latest('requestdate')->get();
        $areacoordinatorIds = Admin::where('role', 'areacoordinator')->pluck('id');
        $workforces_areacoordinator = WorkforceWatson::whereIn('assignedto', $areacoordinatorIds)
            ->where('status', 'completed')
            ->orderBy('status', 'desc')
            ->orderBy('requestdate', 'desc')
            ->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admins = Admin::where('role', 'areacoordinator')->orderBy('lastname')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminMap = $admins->keyBy('id');
        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $transformWorkforce = function ($workforce) use ($adminMap, $adminAllMap, $holidaysArr) {
            $admin = $adminMap->get($workforce->assignedto);
            $workforce->assigned_admin_name = $admin ? "{$admin->lastname}, {$admin->firstname}" : '';
            $adminAll = $adminAllMap->get($workforce->attendedby);
            $workforce->admin_name = $adminAll ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'text-danger fw-bold';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'text-warning fw-bold';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }
            return $workforce;
        };

        $workforces->transform($transformWorkforce);
        $workforces_areacoordinator->transform($transformWorkforce);

        return view('admin.apps.watsonsworkforce.completedworkforce', compact('branches', 'workforces', 'holidays', 'admins', 'workforces_areacoordinator'));
    }

    /**
     *  App WorkForce Completed Request
     */
    public function admin_watsons_acknowledgeworkforce($id)
    {
        $workforce = WorkforceWatson::findOrFail($id);
        $workforce->acknowledged = 1;
        $workforce->acknowledgedby = Auth::guard('admin')->user()->lastname . ', ' .
            Auth::guard('admin')->user()->firstname . ' ' .
            Auth::guard('admin')->user()->middlename;
        $workforce->acknowledgeddate = now('Asia/Manila')->toDateTimeString();
        $workforce->save();
        return redirect()->back()->with('success', 'Request acknowledged successfully.');
    }
    /**
     *  App WorkForce Assign a Request to Area Coordinator
     */
    public function admin_watsons_assignworkforce(Request $request, $id)
    {
        $workforce = WorkforceWatson::findOrFail($id);
        $workforce->assignedto = $request->input('areacoordinator');
        $workforce->assignedby = Auth::guard('admin')->user()->lastname . ', ' .
            Auth::guard('admin')->user()->firstname . ' ' .
            Auth::guard('admin')->user()->middlename;
        $workforce->assigneddate = now('Asia/Manila')->toDateTimeString();
        $workforce->save();

        // Send mail to assigned area coordinator
        $recipient = Admin::find($workforce->assignedto);
        if ($recipient && !empty($recipient->email)) {
            $subject = 'REALS - WORKFORCE REQUEST ASSIGNED [' . $workforce->requesttype . ']';
            $fields = [
                'Request Type' => $workforce->requesttype ?? '',
                'Request ID' => $workforce->id,
                'Branch' => $workforce->branchtarget ?? '',
                'Date Assigned' => $workforce->assigneddate,
                'Assigned By' => $workforce->assignedby,
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
            <div id="workforcemailer" style="max-width:600px;margin:30px auto;padding:30px;border:1px solid #e3e3e3;border-radius:8px;font-family:sans-serif;background:#f8f9fa;">
                <div style="text-align:center;">
                <h2 style="color:#0d6efd;margin-bottom:20px;">REALS - DBPSC</h2>
                </div>
                <hr style="margin:20px 0;">
                <h3 style="color:#0d6efd;margin-bottom:20px;">Workforce Request Assigned</h3>
                <p style="font-size:16px;color:#212529;">A workforce request has been <strong>assigned</strong> to you as Area Coordinator.</p>
                <hr style="margin:20px 0;">
                <table style="width:100%;font-size:15px;">
                ' . $tableRows . '
                </table>
                <div style="margin-top:20px;text-align:center;">
                <span style="color:#dc3545;">This email is auto generated. Do not reply.</span>
                </div>
            </div>
            ';
            Mail::to($recipient->email)->send(new Websitemail($subject, $message));
        }

        return redirect()->back()->with('success', 'Request assigned successfully.');
    }

    /**
     *  App WorkForce Assign a Request to Area Coordinator
     */
    public function admin_watsons_attendworkforce(Request $request, $id)
    {
        $workforce = WorkforceWatson::findOrFail($id);
        $workforce->attendedby = Auth::guard('admin')->user()->id;
        $workforce->adminremarks = $request->input('adminremarks');
        $workforce->attendeddate = now('Asia/Manila')->toDateTimeString();
        $workforce->status = 'attended';
        $workforce->save();

        // Send mail to clientadmin owner after attending request
        $subject = 'REALS - WORKFORCE REQUEST ATTENDED [' . $workforce->requesttype . ']';

        // Build reshuffle table if available
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

        // Build transfer table if available
        $transferTable = '';
        if (
            !empty($workforce->employeestransferred) &&
            !empty($workforce->branchtransferfrom) &&
            !empty($workforce->branchtransferto)
        ) {
            $transferTable .= '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse;width:100%;margin-bottom:10px;">';
            $transferTable .= '<tr style="background:#e9ecef;"><th>EMPLOYEE NAME</th><th>FROM</th><th>TO</th></tr>';
            $transferTable .= '<tr>';
            $transferTable .= '<td>' . htmlentities($workforce->employeestransferred) . '</td>';
            $transferTable .= '<td>' . htmlentities($workforce->branchtransferfrom) . '</td>';
            $transferTable .= '<td>' . htmlentities($workforce->branchtransferto) . '</td>';
            $transferTable .= '</tr>';
            $transferTable .= '</table>';
        }

        $fields = [
            'Request Type' => $workforce->requesttype,
            'Request ID' => $workforce->id,
            'Branch' => $workforce->branchtarget,
            'Date Attended' => $workforce->attendeddate,
            'Attended By' => optional(Admin::find($workforce->attendedby))->lastname . ', ' . optional(Admin::find($workforce->attendedby))->firstname ?? '',
            'Remarks' => $workforce->adminremarks,
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

        // Add transfer table if available
        if ($transferTable) {
            $tableRows .= '<tr>
            <td style="padding:5px 0;vertical-align:top;"><strong>Transferred Employee:</strong></td>
            <td style="padding:5px 0;">' . $transferTable . '</td>
            </tr>';
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
            <h3 style="color:#0d6efd;margin-bottom:20px;">Workforce Request Attended</h3>
            <p style="font-size:16px;color:#212529;">Your workforce request has been <strong>attended</strong> by an admin.</p>
            <hr style="margin:20px 0;">
            <table style="width:100%;font-size:15px;">
            ' . $tableRows . '
            </table>
            <div style="margin-top:20px;text-align:center;">
            <span style="color:#dc3545;">This email is auto generated. Do not reply.</span>
            </div>
        </div>
        ';

        \Mail::to($workforce->requestemail)->send(new Websitemail($subject, $message));

        return redirect()->back()->with('success', 'Request attended successfully.');
    }

    /**
     *  App WorkForce Assign a Request to Area Coordinator
     */
    public function areacoordinator_watsons_attendworkforce(Request $request, $id)
    {
        $workforce = WorkforceWatson::findOrFail($id);
        $workforce->attendedby = Auth::guard('admin')->user()->id;
        $workforce->acremarks = $request->input('areacoordinator_remarks');
        $workforce->acremarksdate = now('Asia/Manila')->toDateTimeString();
        $workforce->save();
        return redirect()->back()->with('success', 'Request attended successfully.');
    }

    //
    // End Watsons WorkForce for Admin Routes
    // =========================================


    // =========================================
    // Watsons WorkForce for Client Admin Routes
    //

    /**
     *  App WorkForce View route
     */
    public function clientadmin_watsons_workforce()
    {
        $branches = Branches::where('isactive', 1)->orderBy('branchname')->get();
        $workforces = WorkforceWatson::latest('created_at')->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $workforceCountAll = WorkforceWatson::whereYear('created_at', now()->year)->count();
        $workforceCountPending = WorkforceWatson::whereYear('created_at', now()->year)->where('status', 'pending')->count();
        $workforceCountCompleted = WorkforceWatson::whereYear('created_at', now()->year)->where('status', 'completed')->count();
        return view('clientadmin.apps.watsonsworkforce.workforce', compact('branches', 'workforces', 'holidays', 'workforceCountAll', 'workforceCountPending', 'workforceCountCompleted'));
    }

    /**
     *  App WorkForce Add Request route
     */
    public function clientadmin_watsons_addworkforce()
    {
        $clientId = Auth::guard('clientadmin')->user()->clientid;
        $branches = Branches::where('isactive', 1)
            ->where('clientid', $clientId)
            ->orderBy('branchname', 'asc')
            ->get();
        return view('clientadmin.apps.watsonsworkforce.addworkforce', compact('branches'));
    }

    /**
     *  App WorkForce Request Submit
     */
    public function clientadmin_watsons_workforce_submit(Request $request)
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
        $workforce->requestclient = Auth::guard('clientadmin')->user()->email;
        $workforce->requestdate = now('Asia/Manila')->toDateTimeString();
        $workforce->requestby = Auth::guard('clientadmin')->user()->id . '. ' .
            Auth::guard('clientadmin')->user()->lastname . ', ' .
            Auth::guard('clientadmin')->user()->firstname . ' ' .
            Auth::guard('clientadmin')->user()->middlename;
        $workforce->requestemail = Auth::guard('clientadmin')->user()->email;

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
        \Mail::to(Auth::guard('clientadmin')->user()->email)->send(new Websitemail($subject, $message));

        return redirect()->back()->with('success', 'Request added successfully.');
    }


    /**
     *  App WorkForce All Request route
     */
    public function clientadmin_watsons_allworkforce()
    {
        $clientId = Auth::guard('clientadmin')->user()->clientid;
        $branches = Branches::where('isactive', 1)
            ->where('clientid', $clientId)
            ->orderBy('branchname', 'asc')
            ->get();
        $workforces = WorkforceWatson::orderBy('status', 'desc')->orderBy('requestdate', 'desc')->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $workforces->transform(function ($workforce) use ($adminAllMap, $holidaysArr) {
            // Admin name
            $workforce->admin_name = ($adminAll = $adminAllMap->get($workforce->attendedby))
                ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'color:red;font-weight:bold;';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'color:orange;font-weight:bold;';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }

            // Reshuffle details for modal table
            $employees = is_array($workforce->employeesreshuffled) ? $workforce->employeesreshuffled : (empty($workforce->employeesreshuffled) ? [] : explode(',', $workforce->employeesreshuffled));
            $froms = is_array($workforce->branchreshufflefrom) ? $workforce->branchreshufflefrom : (empty($workforce->branchreshufflefrom) ? [] : explode(',', $workforce->branchreshufflefrom));
            $tos = is_array($workforce->branchreshuffleto) ? $workforce->branchreshuffleto : (empty($workforce->branchreshuffleto) ? [] : explode(',', $workforce->branchreshuffleto));
            $max = max(count($employees), count($froms), count($tos));
            $details = [];
            for ($i = 0; $i < $max; $i++) {
                $details[] = [
                    'employee' => isset($employees[$i]) ? trim($employees[$i]) : '',
                    'from' => isset($froms[$i]) ? trim($froms[$i]) : '',
                    'to' => isset($tos[$i]) ? trim($tos[$i]) : '',
                ];
            }
            $workforce->reshuffle_details = $details;

            return $workforce;
        });

        return view('clientadmin.apps.watsonsworkforce.allworkforce', compact('branches', 'workforces', 'holidays', 'admin'));
    }

    /**
     *  App WorkForce Pending Request route
     */
    public function clientadmin_watsons_pendingworkforce()
    {
        $clientId = Auth::guard('clientadmin')->user()->clientid;
        $branches = Branches::where('isactive', 1)
            ->where('clientid', $clientId)
            ->orderBy('branchname', 'asc')
            ->get();
        $workforces = WorkforceWatson::where('status', 'pending')->latest('created_at')->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $workforces->transform(function ($workforce) use ($adminAllMap, $holidaysArr) {
            // Admin name
            $workforce->admin_name = ($adminAll = $adminAllMap->get($workforce->attendedby))
                ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'color:red;font-weight:bold;';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'color:orange;font-weight:bold;';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }

            // Reshuffle details for modal table
            $employees = is_array($workforce->employeesreshuffled) ? $workforce->employeesreshuffled : (empty($workforce->employeesreshuffled) ? [] : explode(',', $workforce->employeesreshuffled));
            $froms = is_array($workforce->branchreshufflefrom) ? $workforce->branchreshufflefrom : (empty($workforce->branchreshufflefrom) ? [] : explode(',', $workforce->branchreshufflefrom));
            $tos = is_array($workforce->branchreshuffleto) ? $workforce->branchreshuffleto : (empty($workforce->branchreshuffleto) ? [] : explode(',', $workforce->branchreshuffleto));
            $max = max(count($employees), count($froms), count($tos));
            $details = [];
            for ($i = 0; $i < $max; $i++) {
                $details[] = [
                    'employee' => isset($employees[$i]) ? trim($employees[$i]) : '',
                    'from' => isset($froms[$i]) ? trim($froms[$i]) : '',
                    'to' => isset($tos[$i]) ? trim($tos[$i]) : '',
                ];
            }
            $workforce->reshuffle_details = $details;

            return $workforce;
        });

        return view('clientadmin.apps.watsonsworkforce.pendingworkforce', compact('branches', 'workforces', 'holidays', 'admin'));
    }

    /**
     *  App WorkForce Completed Request route
     */
    public function clientadmin_watsons_completedworkforce()
    {
        $clientId = Auth::guard('clientadmin')->user()->clientid;
        $branches = Branches::where('isactive', 1)
            ->where('clientid', $clientId)
            ->orderBy('branchname', 'asc')
            ->get();
        $workforces = WorkforceWatson::where('status', 'completed')->latest('created_at')->get();
        $holidays = Holidays::whereYear('date', now()->year)->orderBy('date')->get();
        $admin = Admin::where('role', 'admin')->orderBy('lastname')->get();

        $adminAllMap = $admin->keyBy('id');
        $holidaysArr = $holidays->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))->toArray();

        $workforces->transform(function ($workforce) use ($adminAllMap, $holidaysArr) {
            // Admin name
            $workforce->admin_name = ($adminAll = $adminAllMap->get($workforce->attendedby))
                ? "{$adminAll->lastname}, {$adminAll->firstname}" : '';

            // TAT calculation
            if ($workforce->requestdate) {
                $s = \Carbon\Carbon::parse($workforce->requestdate);
                $e = $workforce->attendeddate ? \Carbon\Carbon::parse($workforce->attendeddate) : \Carbon\Carbon::now();
                $bd = 0;
                $current = $s->copy()->startOfDay();
                while ($current->lt($e)) {
                    $nextDay = $current->copy()->addDay();
                    if ($nextDay->lte($e)) {
                        if (!$current->isWeekend() && !in_array($current->format('Y-m-d'), $holidaysArr)) {
                            $bd++;
                        }
                    }
                    $current->addDay();
                }
                $workforce->tat_days = $bd;
                if ($bd >= 6) {
                    $workforce->tat_class = 'color:red;font-weight:bold;';
                } elseif (in_array($bd, [3, 4, 5])) {
                    $workforce->tat_class = 'color:orange;font-weight:bold;';
                } else {
                    $workforce->tat_class = '';
                }
            } else {
                $workforce->tat_days = null;
                $workforce->tat_class = '';
            }

            // Reshuffle details for modal table
            $employees = is_array($workforce->employeesreshuffled) ? $workforce->employeesreshuffled : (empty($workforce->employeesreshuffled) ? [] : explode(',', $workforce->employeesreshuffled));
            $froms = is_array($workforce->branchreshufflefrom) ? $workforce->branchreshufflefrom : (empty($workforce->branchreshufflefrom) ? [] : explode(',', $workforce->branchreshufflefrom));
            $tos = is_array($workforce->branchreshuffleto) ? $workforce->branchreshuffleto : (empty($workforce->branchreshuffleto) ? [] : explode(',', $workforce->branchreshuffleto));
            $max = max(count($employees), count($froms), count($tos));
            $details = [];
            for ($i = 0; $i < $max; $i++) {
                $details[] = [
                    'employee' => isset($employees[$i]) ? trim($employees[$i]) : '',
                    'from' => isset($froms[$i]) ? trim($froms[$i]) : '',
                    'to' => isset($tos[$i]) ? trim($tos[$i]) : '',
                ];
            }
            $workforce->reshuffle_details = $details;

            return $workforce;
        });

        return view('clientadmin.apps.watsonsworkforce.completedworkforce', compact('branches', 'workforces', 'holidays', 'admin'));
    }

    /**
     *  App WorkForce Cancel Request
     */
    public function clientadmin_watsons_cancelworkforce(Request $request, $id)
    {
        $workforce = WorkforceWatson::findOrFail($id);
        $workforce->status = 'cancelled';
        $workforce->save();
        return redirect()->back()->with('success', 'Request cancelled successfully.');
    }

    /**
     *  App WorkForce Completed Request
     */
    public function clientadmin_watsons_iscompletedworkforce(Request $request, $id)
    {
        $workforce = WorkforceWatson::findOrFail($id);
        $workforce->status = 'completed';
        $workforce->completeddate = now('Asia/Manila')->toDateTimeString();
        $workforce->save();
        return redirect()->back()->with('success', 'Request completed successfully.');
    }

    /**
     *  App WorkForce Update Request
     */
    public function clientadmin_watsons_updateworkforce(Request $request, $id)
    {
        $request->validate([
            'branchtarget' => 'nullable',
            'employeestransferred' => 'nullable',
            'branchtransferfrom' => 'nullable',
            'branchtransferto' => 'nullable',
            'employeesreshuffled' => 'array',
            'branchreshufflefrom' => 'array',
            'branchreshuffleto' => 'array',
            'clientremarks' => 'nullable|string|max:1000',
            'status' => 'required',
        ]);

        $workforce = WorkforceWatson::findOrFail($id);

        $workforce->branchtarget = $request->branchtarget;
        $workforce->employeestransferred = $request->employeestransferred;
        $workforce->branchtransferfrom = $request->branchtransferfrom;
        $workforce->branchtransferto = $request->branchtransferto;
        $workforce->clientremarks = $request->clientremarks;
        $workforce->status = $request->status;

        // Save reshuffle arrays as comma-separated strings
        $workforce->employeesreshuffled = is_array($request->employeesreshuffled) ? collect($request->employeesreshuffled)->filter()->implode(', ') : null;
        $workforce->branchreshufflefrom = is_array($request->branchreshufflefrom) ? collect($request->branchreshufflefrom)->filter()->implode(', ') : null;
        $workforce->branchreshuffleto = is_array($request->branchreshuffleto) ? collect($request->branchreshuffleto)->filter()->implode(', ') : null;

        $workforce->save();

        return redirect()->back()->with('success', 'Request updated successfully.');
    }

    //
    // End Watsons WorkForce for Client Admin Routes
    // =========================================


}
