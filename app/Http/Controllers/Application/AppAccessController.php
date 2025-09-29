<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Applications;
use App\Models\ApplicationsAccess;
use App\Models\Client;

class AppAccessController extends Controller
{
    // =========================================
    // App Access Management Start
    //

    /**
     * Display the Apps overview page.
     */
    public function apps()
    {
        return view('superadmin.appmanagement.apps');
    }

    /**
     * Display the App Management.
     */
    public function applist()
    {
        $applications = Applications::where('isactive', 1)->get();
        return view('superadmin.appmanagement.applist', compact('applications'));
    }

    /**
     * Add Application route.
     */
    public function addapplication()
    {
        return view('superadmin.appmanagement.addapplication');
    }

    /**
     * Add Application route.
     */
    public function addapplication_submit(Request $request)
    {
        $request->validate([
            'appname' => 'required|unique:applications,appname',
            'applabel' => 'required',
        ]);

        $application = new Applications();
        $application->appname = $request->appname;
        $application->applabel = $request->applabel;
        $application->save();

        return redirect()->route('superadmin_applist')->with('success', 'Application added successfully.');
    }

    /**
     * Edit Application route.
     */
    public function editapplication(Request $request, $id)
    {
        $application = Applications::find($id);
        return view('superadmin.appmanagement.editapplication', compact('application'));
    }

    /**
     * Edit Application Submit
     */
    public function editapplication_submit(Request $request, $id)
    {
        $request->validate([
            'appname' => 'required|unique:applications,appname,' . $id,
            'applabel' => 'required',
        ]);

        $application = Applications::find($id);

        $application->appname = $request->appname;
        $application->applabel = $request->applabel;
        $application->save();

        return redirect()->route('superadmin_applist')->with('success', 'Application updated successfully.');
    }

    /**
     * Soft delete Application (set isactive to 0)
     */
    public function softdeleteapplication(Request $request, $id)
    {
        $application = Applications::find($id);
        $application->isactive = 0;
        $application->save();

        return redirect()->route('superadmin_applist')->with('success', 'Application deactivated successfully.');
    }

    /**
     * App Access Route
     */
    public function appaccess()
    {
        // Get all active clients that have at least one ApplicationsAccess row
        $clientIdsWithAccess = ApplicationsAccess::pluck('clientid')->unique();
        $clients = Client::where('isactive', 1)->whereIn('id', $clientIdsWithAccess)->get();
        $allApplications = Applications::where('isactive', 1)->get();
        $accesses = ApplicationsAccess::whereIn('clientid', $clients->pluck('id'))->get()->keyBy('clientid');
        return view('superadmin.appmanagement.appaccess', compact('clients', 'allApplications', 'accesses'));
    }

    /**
     * Add App Access
     */
    public function addappaccess()
    {
        $clients = Client::where('isactive', 1)->get();
        $applications = Applications::where('isactive', 1)->get();
        $applications_access = ApplicationsAccess::get();
        return view('superadmin.appmanagement.addappaccess', compact('clients', 'applications', 'applications_access'));
    }

    /**
     * Add App Access Submit
     */
    public function addappaccess_submit(Request $request)
    {
        $applications = Applications::where('isactive', 1)->get();

        $request->validate([
            'clientid' => 'required|unique:applications_access,clientid',
        ]);

        // Build the list of allowed app_X fields based on active applications
        $appFields = [];
        foreach ($applications as $application) {
            $field = 'app_' . $application->id;
            $appFields[$field] = $request->has($field) ? 1 : 0;
        }

        // Save using mass assignment
        $data = array_merge(['clientid' => $request->clientid], $appFields);

        ApplicationsAccess::create($data);

        return redirect()->route('superadmin_appaccess')->with('success', 'App access updated successfully.');
    }

    /**
     * Edit App Access
     */
    public function editappaccess($id)
    {
        $clients = Client::where('isactive', 1)->get();
        $applications = Applications::where('isactive', 1)->get();
        $applications_access = ApplicationsAccess::find($id);
        return view('superadmin.appmanagement.editappaccess', compact('clients', 'applications', 'applications_access'));
    }

    /**
     * Edit App Access Submit
     */
    public function editappaccess_submit(Request $request, $id)
    {
        $access = ApplicationsAccess::findOrFail($id);
        $applications = Applications::where('isactive', 1)->get();

        foreach ($applications as $application) {
            $field = 'app_' . $application->id;
            $access->$field = $request->has($field) ? 1 : 0;
        }

        $access->save();

        return redirect()->route('superadmin_appaccess')->with('success', 'Application access updated successfully.');
    }
    // End App Access Management
    // =========================================
}
