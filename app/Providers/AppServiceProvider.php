<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ApplicationsAccess;
use App\Models\AdminApplicationsAccess;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $clientadminViews = [
            'clientadmin.partials.client_sidenav',
            'clientadmin.apps',
            'clientadmin.dashboard',
        ];
        $adminViews = [
            'admin.apps',
            'admin.partials.admin_sidenav',
            'admin.dashboard',
        ];
        $appKeys = [
            'canAccessPeopleApp' => 'app_1',
            'canAccessWatsonsWorkforceApp' => 'app_2',
            'canAccessTimeoffApp' => 'app_3',
            'canAccessLocatorApp' => 'app_4',
            'canAccessWorkchatApp' => 'app_5',
            'canAccessTimelogApp' => 'app_6',
        ];

        // Clientadmin views
        View::composer($clientadminViews, function ($view) use ($appKeys) {
            $clientId = Auth::guard('clientadmin')->user()->clientid ?? null;
            $clientAccess = $clientId ? ApplicationsAccess::where('clientid', $clientId)->first() : null;
            foreach ($appKeys as $key => $field) {
                $view->with($key, $clientAccess && $clientAccess->$field == 1);
            }
        });
        
        // Admin views
        View::composer($adminViews, function ($view) use ($appKeys) {
            $adminId = Auth::guard('admin')->user()->id ?? null;
            $adminAccess = $adminId ? AdminApplicationsAccess::where('adminid', $adminId)->first() : null;
            foreach ($appKeys as $key => $field) {
                $view->with($key, $adminAccess && $adminAccess->$field == 1);
            }
        });
    }
}
