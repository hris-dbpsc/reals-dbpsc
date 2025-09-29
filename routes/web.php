<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Login\ResetPasswordController;
use App\Http\Controllers\Login\LogoutController;
use App\Http\Controllers\Superadmin\SuperadminController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Clientadmin\ClientadminController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\ClientManagement\BranchController;
use App\Http\Controllers\ClientManagement\ClientController;
use App\Http\Controllers\Application\AppAccessController;
use App\Http\Controllers\Application\LocatorController;
use App\Http\Controllers\Application\PeopleController;
use App\Http\Controllers\Application\WatsonsWorkForceController;
use App\Http\Controllers\Application\TimeOffController;
use App\Http\Controllers\Application\TimeLogController;
use App\Http\Controllers\Application\PayslipController;
use App\Http\Controllers\UserManagement\UserAdminController;
use App\Http\Controllers\UserManagement\UserManagementController;
use App\Http\Controllers\UserManagement\UserSuperadminController;
use App\Http\Controllers\UserManagement\UserClientadminController;
use App\Http\Controllers\UserManagement\UserEmployeeController;
use PhpOffice\PhpSpreadsheet\Calculation\DateTimeExcel\Time;

// ==========================================================================================================================================
// Login Page Route
Route::get('/', function () {
    return view('index');
})->name('index');
// ==========================================================================================================================================


// ==========================================================================================================================================
// Login Multi-Authentication Route
Route::post('/index', [LoginController::class, 'login_multiauth'])->name('login_multiauth');
// ==========================================================================================================================================


// ==========================================================================================================================================
// Forget-Password Routes
Route::get('forget-password', [ResetPasswordController::class, 'forget_password'])->name('forget_password');
Route::post('forget-password', [ResetPasswordController::class, 'forget_password_submit'])->name('forget_password_submit');
Route::get('reset-password/{token}/{email}', [ResetPasswordController::class, 'reset_password'])->name('reset_password');
Route::post('reset-password/{token}/{email}', [ResetPasswordController::class, 'reset_password_submit'])->name('reset_password_submit');
// ==========================================================================================================================================


// ==========================================================================================================================================
// Logout Route
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
// =========================================





// ==========================================================================================================================================
// Superadmin Routes
//
// Authenticated Superadmin Routes
Route::middleware('superadmin')->prefix('superadmin')->group(function () {
    // =========================================
    // Superadmin Dashboard Page Route
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin_dashboard');
    // TEST APP
    Route::get('/qrcode', [SuperadminController::class, 'qrcode'])->name('superadmin_qrcode');
    // =========================================

    // =========================================
    // Superadmin Profile Routes
    Route::get('/profile/{id}', [SuperadminController::class, 'profile'])->name('profile');
    Route::put('/profile/{id}', [SuperadminController::class, 'profile_submit'])->name('profile_submit');
    Route::put('/profile_uploadprofilepicture/{id}', [SuperadminController::class, 'profile_uploadprofilepicture'])->name('profile_uploadprofilepicture');
    Route::post('/changepassword/{id}', [SuperadminController::class, 'changepassword'])->name('changepassword');
    // =========================================


    // =========================================
    // User Management Routes
    Route::get('/usermanagement', [UserManagementController::class, 'usermanagement'])->name('superadmin_usermanagement');

    // Superadmin User Management Routes
    Route::get('/usersuperadmin', [UserSuperadminController::class, 'usersuperadmin'])->name('superadmin_usersuperadmin');
    Route::get('/addsuperadmin', [UserSuperadminController::class, 'addsuperadmin'])->name('superadmin_addsuperadmin');
    Route::post('/addsuperadmin', [UserSuperadminController::class, 'addsuperadmin_submit'])->name('superadmin_addsuperadmin_submit');
    Route::get('/editusersuperadmin/{id}', [UserSuperadminController::class, 'editusersuperadmin'])->name('superadmin_editusersuperadmin');
    Route::put('/editusersuperadmin/{id}', [UserSuperadminController::class, 'editusersuperadmin_submit'])->name('superadmin_editusersuperadmin_submit');
    Route::patch('/softdelete/{id}', [UserSuperadminController::class, 'softdelete'])->name('superadmin_softdelete');
    Route::patch('/suspend/{id}', [UserSuperadminController::class, 'suspend'])->name('superadmin_suspend');
    Route::get('/usersuperadmin/export', [UserSuperadminController::class, 'exportusersuperadmin'])->name('superadmin_export_usersuperadmin');

    // Admin Management Routes
    Route::get('/useradmin', [UserAdminController::class, 'useradmin'])->name('superadmin_useradmin');
    Route::get('/addadmin', [UserAdminController::class, 'addadmin'])->name('superadmin_addadmin');
    Route::post('/addadmin', [UserAdminController::class, 'addadmin_submit'])->name('superadmin_addadmin_submit');
    Route::get('/edituseradmin/{id}', [UserAdminController::class, 'edituseradmin'])->name('superadmin_edituseradmin');
    Route::put('/edituseradmin/{id}', [UserAdminController::class, 'edituseradmin_submit'])->name('superadmin_edituseradmin_submit');
    Route::patch('/adminsoftdelete/{id}', [UserAdminController::class, 'adminsoftdelete'])->name('superadmin_adminsoftdelete');
    Route::patch('/adminsuspend/{id}', [UserAdminController::class, 'adminsuspend'])->name('superadmin_adminsuspend');
    Route::get('/useradmin/export', [UserAdminController::class, 'exportuseradmin'])->name('superadmin_export_useradmin');
    Route::put('/editadminappaccess/{id}', [UserAdminController::class, 'editadminappaccess_submit'])->name('superadmin_editadminappaccess_submit');

    // Client Admin Management Routes
    Route::get('/userclientadmin', [UserClientAdminController::class, 'userclientadmin'])->name('superadmin_userclientadmin');
    Route::get('/addclientadmin', [UserClientAdminController::class, 'addclientadmin'])->name('superadmin_addclientadmin');
    Route::post('/addclientadmin', [UserClientAdminController::class, 'addclientadmin_submit'])->name('superadmin_addclientadmin_submit');
    Route::get('/edituserclientadmin/{id}', [UserClientAdminController::class, 'edituserclientadmin'])->name('superadmin_edituserclientadmin');
    Route::put('/edituserclientadmin/{id}', [UserClientAdminController::class, 'edituserclientadmin_submit'])->name('superadmin_edituserclientadmin_submit');
    Route::patch('/clientadminsoftdelete/{id}', [UserClientAdminController::class, 'clientadminadminsoftdelete'])->name('superadmin_clientadminsoftdelete');
    Route::patch('/clientadminsuspend/{id}', [UserClientAdminController::class, 'clientadminadminsuspend'])->name('superadmin_clientadminsuspend');
    Route::get('/userclientadmin/export', [UserClientAdminController::class, 'exportuserclientadmin'])->name('superadmin_export_userclientadmin');

    // User Employee Management Routes
    Route::get('/useremployee', [UserEmployeeController::class, 'useremployee'])->name('superadmin_useremployee');
    Route::post('/useremployee/import', [UserEmployeeController::class, 'importemployee'])->name('superadmin_useremployee_import');
    Route::get('/useremployee/export', [UserEmployeeController::class, 'exportemployee'])->name('superadmin_useremployee_export');
    Route::post('/useremployee/truncate', [UserEmployeeController::class, 'truncateemployeetable'])->name('superadmin_useremployee_truncate');
    Route::patch('/useremployee/{id}/activate', [UserEmployeeController::class, 'activateemployee'])->name('superadmin_activateemployee');
    Route::patch('/useremployee/{id}/deactivate', [UserEmployeeController::class, 'deactivateemployee'])->name('superadmin_deactivateemployee');

    // End User Management Routes
    // =========================================


    // ========================================= 
    // Client Management Routes
    Route::get('/clientmanagement', [ClientController::class, 'clientmanagement'])->name('superadmin_clientmanagement');
    Route::get('/clients', [ClientController::class, 'clients'])->name('superadmin_clients');
    Route::get('/clients/{id}/viewclients', [ClientController::class, 'viewclients'])->name('superadmin_viewclients');
    Route::get('/clients/{id}/editclient', [ClientController::class, 'editclient'])->name('superadmin_editclient');
    Route::put('/clients/{id}', [ClientController::class, 'editclient_submit'])->name('superadmin_editclient_submit');
    Route::patch('/clients/{id}/softdelete', [ClientController::class, 'softdeleteclient'])->name('superadmin_softdeleteclient');
    Route::get('/addclient', [ClientController::class, 'addclient'])->name('superadmin_addclient');
    Route::post('/addclient', [ClientController::class, 'addclient_submit'])->name('superadmin_addclient_submit');
    Route::put('/editclient_uploadprofilepicture/{id}', [ClientController::class, 'editclient_uploadprofilepicture'])->name('superadmin_editclient_uploadprofilepicture');
    Route::post('/clients/import', [ClientController::class, 'importclients'])->name('superadmin_clients_import');
    Route::get('/clients/export', [ClientController::class, 'exportclients'])->name('superadmin_clients_export');

    // Branch Management Routes
    Route::get('/branches', [BranchController::class, 'branches'])->name('superadmin_branches');
    Route::get('/addbranch', [BranchController::class, 'addbranch'])->name('superadmin_addbranch');
    Route::post('/addbranch', [BranchController::class, 'addbranch_submit'])->name('superadmin_addbranch_submit');
    Route::get('/branches/{id}/viewbranches', [BranchController::class, 'viewbranch'])->name('superadmin_viewbranch');
    Route::get('/branches/{id}/editbranch', [BranchController::class, 'editbranch'])->name('superadmin_editbranch');
    Route::put('/branches/{id}', [BranchController::class, 'editbranch_submit'])->name('superadmin_editbranch_submit');
    Route::patch('/branches/{id}/softdelete', [BranchController::class, 'softdeletebranch'])->name('superadmin_softdeletebranch');
    Route::post('/branches/import', [BranchController::class, 'importbranches'])->name('superadmin_branches_import');
    Route::get('/branches/export', [BranchController::class, 'exportbranches'])->name('superadmin_branches_export');
    // End Client Management Routes
    // =========================================


    // =========================================
    // App Access Management Routes
    Route::get('/apps', [AppAccessController::class, 'apps'])->name('superadmin_apps');
    Route::get('/applist', [AppAccessController::class, 'applist'])->name('superadmin_applist');
    Route::get('/addapplication', [AppAccessController::class, 'addapplication'])->name('superadmin_addapplication');
    Route::post('/addapplication', [AppAccessController::class, 'addapplication_submit'])->name('superadmin_addapplication_submit');
    Route::get('/editapplication/{id}', [AppAccessController::class, 'editapplication'])->name('superadmin_editapplication');
    Route::put('/editapplication/{id}', [AppAccessController::class, 'editapplication_submit'])->name('superadmin_editapplication_submit');
    Route::patch('/softdeleteapplication/{id}', [AppAccessController::class, 'softdeleteapplication'])->name('superadmin_softdeleteapplication');
    Route::get('/appaccess', [AppAccessController::class, 'appaccess'])->name('superadmin_appaccess');
    Route::get('/addappaccess', [AppAccessController::class, 'addappaccess'])->name('superadmin_addappaccess');
    Route::post('/addappaccess', [AppAccessController::class, 'addappaccess_submit'])->name('superadmin_addappaccess_submit');
    Route::get('/editappaccess/{id}', [AppAccessController::class, 'editappaccess'])->name('superadmin_editappaccess');
    Route::put('/editappaccess/{id}', [AppAccessController::class, 'editappaccess_submit'])->name('superadmin_editappaccess_submit');
    // End Access Management Routes
    // =========================================


    // =========================================
    // Superadmin App Management Routes Start
    //

    // =========================================
    // App Locator Routes
    Route::get('/locator', [LocatorController::class, 'superadmin_locator'])->name('superadmin_locator');
    Route::get('/locatorclient', [LocatorController::class, 'superadmin_locatorclient'])->name('superadmin_locatorclient');
    Route::get('/locatorbranch', [LocatorController::class, 'superadmin_locatorbranch'])->name('superadmin_locatorbranch');
    Route::get('/locatordata', [LocatorController::class, 'superadmin_locatordata'])->name('superadmin_locatordata');
    Route::get('/locatoremployee', [LocatorController::class, 'superadmin_locatoremployee'])->name('superadmin_locatoremployee');
    Route::get('/locatoremployee/search-users', [LocatorController::class, 'superadmin_locator_searchUsers'])->name('superadmin_locator_search_users');
    Route::get('/locatoremployeemap/{id}', [LocatorController::class, 'superadmin_locatoremployeemap'])->name('superadmin_locatoremployeemap');
    // End App Locator Routes
    // =========================================

    // =========================================
    // App People Routes
    Route::get('/people', [PeopleController::class, 'superadmin_people'])->name('superadmin_people');
    Route::get('/people/search-users', [PeopleController::class, 'superadmin_searchUsers'])->name('superadmin_people_search_users');
    Route::get('/peopleview/{id}', [PeopleController::class, 'superadmin_peopleview'])->name('superadmin_peopleview');
    // End App People Routes
    // =========================================

    // =========================================
    // App Watsons WorkForce Routes
    Route::get('/watsonsworkforce', [WatsonsWorkForceController::class, 'superadmin_watsonsworkforce'])->name('superadmin_watsonsworkforce');
    Route::get('/allwatsonsworkforce', [WatsonsWorkForceController::class, 'superadmin_watsons_allworkforce'])->name('superadmin_watsons_allworkforce');
    Route::get('/pendingwatsonsworkforce', [WatsonsWorkForceController::class, 'superadmin_watsons_pendingworkforce'])->name('superadmin_watsons_pendingworkforce');
    Route::get('/completedwatsonsworkforce', [WatsonsWorkForceController::class, 'superadmin_watsons_completedworkforce'])->name('superadmin_watsons_completedworkforce');
    // End App Watsons WorkForce Routes
    // =========================================

    // =========================================
    // App TimeOff Routes
    Route::get('/timeoff', [TimeOffController::class, 'superadmin_timeoff'])->name('superadmin_timeoff');
    Route::get('/alltimeoff', [TimeOffController::class, 'superadmin_alltimeoff'])->name('superadmin_alltimeoff');
    // =========================================


    // =========================================
    // App TimeLog Routes
    Route::get('/timelog', [TimeLogController::class, 'superadmin_timelog'])->name('superadmin_timelog');
    // =========================================


    // =========================================
    // App Payslip Routes
    Route::get('/payslip', [PayslipController::class, 'superadmin_payslip'])->name('superadmin_payslip');
    Route::post('/payslip', [PayslipController::class, 'superadmin_payslip_upload'])->name('superadmin_payslip_upload');
    // =========================================

    //
    // End Superadmin App Management Routes
    // =========================================

});
// 
// ==========================================================================================================================================




// ==========================================================================================================================================
// Admin Routes
//
// Authenticated Admin Routes
Route::middleware('admin')->prefix('admin')->group(function () {

    // =========================================
    // Admin Dashboard Page Route
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin_dashboard');
    // =========================================


    // =========================================
    // Admin Profile Routes
    Route::get('/profile/{id}', [AdminController::class, 'editprofile'])->name('admin_profile');
    Route::put('/profile/{id}', [AdminController::class, 'editprofile_submit'])->name('admin_profile_submit');
    Route::put('/profile/{id}/uploadprofilepicture', [AdminController::class, 'uploadprofilepicture'])->name('admin_uploadprofilepicture');
    Route::post('/changepassword/{id}', [AdminController::class, 'changepassword'])->name('admin_changepassword');
    // =========================================

    // =========================================
    // Admin Employee Management Routes
    Route::get('/useremployee', [UserEmployeeController::class, 'useremployee'])->name('admin_useremployee');
    Route::patch('/useremployee/{id}/activate', [UserEmployeeController::class, 'activateemployee'])->name('admin_activateemployee');
    Route::patch('/useremployee/{id}/deactivate', [UserEmployeeController::class, 'deactivateemployee'])->name('admin_deactivateemployee');
    // =========================================

    // =========================================
    // Admin Client Management Routes
    Route::get('/clientmanagement', [ClientController::class, 'clientmanagement'])->name('admin_clientmanagement');
    Route::get('/clients', [ClientController::class, 'clients'])->name('admin_clients');
    Route::get('/clients/{id}/viewclients', [ClientController::class, 'viewclients'])->name('admin_viewclients');
    Route::get('/branches', [BranchController::class, 'branches'])->name('admin_branches');
    Route::get('/branches/{id}/viewbranch', [BranchController::class, 'viewbranch'])->name('admin_viewbranch');
    // =========================================



    // =========================================
    // Admin Apps Route
    // Apps Main
    Route::get('/apps', [AdminController::class, 'apps'])->name('admin_apps');

    // =========================================
    // People
    Route::get('/people', [PeopleController::class, 'admin_people'])->name('admin_people');
    Route::get('/people/search-users', [PeopleController::class, 'admin_searchUsers'])->name('admin_people_search_users');
    Route::get('/peopleview/{id}', [PeopleController::class, 'admin_peopleview'])->name('admin_peopleview');
    // =========================================

    // =========================================
    // Locator
    Route::get('/locator', [LocatorController::class, 'admin_locator'])->name('admin_locator');
    Route::get('/locatorclient', [LocatorController::class, 'admin_locatorclient'])->name('admin_locatorclient');
    Route::get('/locatorbranch', [LocatorController::class, 'admin_locatorbranch'])->name('admin_locatorbranch');
    Route::get('/locatordata', [LocatorController::class, 'admin_locatordata'])->name('admin_locatordata');
    Route::get('/locatoremployee', [LocatorController::class, 'admin_locatoremployee'])->name('admin_locatoremployee');
    Route::get('/locatoremployee/search-users', [LocatorController::class, 'admin_searchUsers'])->name('admin_locator_search_users');
    Route::get('/locatoremployeemap/{id}', [LocatorController::class, 'admin_locatoremployeemap'])->name('admin_locatoremployeemap');
    // =========================================

    // =========================================
    // App Watsons WorkForce Routes
    Route::get('/watsonsworkforce', [WatsonsWorkForceController::class, 'admin_watsonsworkforce'])->name('admin_watsonsworkforce');
    Route::get('/allwatsonsworkforce', [WatsonsWorkForceController::class, 'admin_watsons_allworkforce'])->name('admin_watsons_allworkforce');
    Route::get('/pendingwatsonsworkforce', [WatsonsWorkForceController::class, 'admin_watsons_pendingworkforce'])->name('admin_watsons_pendingworkforce');
    Route::get('/completedwatsonsworkforce', [WatsonsWorkForceController::class, 'admin_watsons_completedworkforce'])->name('admin_watsons_completedworkforce');
    Route::patch('/allwatsonsworkforce/{id}', [WatsonsWorkForceController::class, 'admin_watsons_acknowledgeworkforce'])->name('admin_watsons_acknowledgeworkforce');
    Route::put('/allwatsonsworkforce/{id}', [WatsonsWorkForceController::class, 'admin_watsons_assignworkforce'])->name('admin_watsons_assignworkforce');
    Route::put('/attendwatsonsworkforce/{id}', [WatsonsWorkForceController::class, 'admin_watsons_attendworkforce'])->name('admin_watsons_attendworkforce');
    Route::put('/areacoordinatorattendwatsonsworkforce/{id}', [WatsonsWorkForceController::class, 'areacoordinator_watsons_attendworkforce'])->name('areacoordinator_watsons_attendworkforce');
    Route::get('/pendingwatsonsworkforce', [WatsonsWorkForceController::class, 'admin_watsons_pendingworkforce'])->name('admin_watsons_pendingworkforce');
    Route::get('/completedwatsonsworkforce', [WatsonsWorkForceController::class, 'admin_watsons_completedworkforce'])->name('admin_watsons_completedworkforce');
    // =========================================


    // =========================================
    // App TimeOff Routes
    Route::get('/timeoff', [TimeOffController::class, 'admin_timeoff'])->name('admin_timeoff');
    Route::get('/alltimeoff', [TimeOffController::class, 'admin_alltimeoff'])->name('admin_alltimeoff');
    Route::get('/disapprovedtimeoff', [TimeOffController::class, 'admin_disapprovedtimeoff'])->name('admin_disapprovedtimeoff');
    Route::get('/timeoff/attachment/{id}', [TimeOffController::class, 'admin_viewAttachment'])->name('admin.timeoff.attachment');
    Route::post('/approvetimeoff/{id}', [TimeOffController::class, 'admin_approve_timeoff_submit'])->name('admin_approve_timeoff_submit');
    Route::post('/disapprovetimeoff/{id}', [TimeOffController::class, 'admin_disapprove_timeoff_submit'])->name('admin_disapprove_timeoff_submit');
    // =========================================

    // =========================================
    // App TimeLog Routes
    Route::get('/timelog', [TimeLogController::class, 'admin_timelog'])->name('admin_timelog');
    // =========================================

});
//
// ==========================================================================================================================================




// ==========================================================================================================================================
// Client Admin Routes
//
// Authenticated Client Admin Routes
Route::middleware('clientadmin')->prefix('clientadmin')->group(function () {

    // =========================================
    // Client Admin Dashboard Page Route
    Route::get('/dashboard', [ClientadminController::class, 'dashboard'])->name('clientadmin_dashboard');
    // =========================================

    // =========================================
    // Client Admin Profile Routes
    Route::get('/profile/{id}', [ClientadminController::class, 'editprofile'])->name('clientadmin_profile');
    Route::put('/profile/{id}', [ClientadminController::class, 'editprofile_submit'])->name('clientadmin_profile_submit');
    Route::put('/profile/{id}/uploadprofilepicture', [ClientadminController::class, 'uploadprofilepicture'])->name('clientadmin_uploadprofilepicture');
    Route::post('/changepassword/{id}', [ClientadminController::class, 'changepassword'])->name('clientadmin_changepassword');
    // =========================================

    // =========================================
    // Clientadmin Employee Management Routes
    Route::get('/useremployee', [UserEmployeeController::class, 'useremployee'])->name('clientadmin_useremployee');
    // =========================================

    // =========================================
    // Client admin  Branch Management Routes
    Route::get('/branches', [BranchController::class, 'branches'])->name('clientadmin_branches');
    Route::get('/branches/{id}/viewbranch', [BranchController::class, 'viewbranch'])->name('clientadmin_viewbranch');
    // =========================================

    // =========================================
    // Client Admin Apps Route
    // Apps Main
    Route::get('/apps', [ClientadminController::class, 'apps'])->name('clientadmin_apps');
    Route::get('/people', [PeopleController::class, 'clientadmin_people'])->name('clientadmin_people');
    // People
    Route::get('/people', [PeopleController::class, 'clientadmin_people'])->name('clientadmin_people');
    Route::get('/people/search-users', [PeopleController::class, 'clientadmin_searchUsers'])->name('clientadmin_people_search_users');
    Route::get('/peopleview/{id}', [PeopleController::class, 'clientadmin_peopleview'])->name('clientadmin_peopleview');
    // Locator
    Route::get('/locator', [LocatorController::class, 'clientadmin_locator'])->name('clientadmin_locator');
    Route::get('/locatorbranch', [LocatorController::class, 'clientadmin_locatorbranch'])->name('clientadmin_locatorbranch');
    Route::get('/locatordata', [LocatorController::class, 'clientadmin_locatordata'])->name('clientadmin_locatordata');
    Route::get('/locatoremployee', [LocatorController::class, 'clientadmin_locatoremployee'])->name('clientadmin_locatoremployee');
    Route::get('/locatoremployee/search-users', [LocatorController::class, 'clientadmin_searchUsers'])->name('clientadmin_locator_search_users');
    Route::get('/locatoremployeemap/{id}', [LocatorController::class, 'clientadmin_locatoremployeemap'])->name('clientadmin_locatoremployeemap');
    // =========================================

    // =========================================
    // App Watsons WorkForce Routes
    Route::get('/workforce', [WatsonsWorkForceController::class, 'clientadmin_watsons_workforce'])->name('clientadmin_watsons_workforce');
    Route::get('/addworkforce', [WatsonsWorkForceController::class, 'clientadmin_watsons_addworkforce'])->name('clientadmin_watsons_addworkforce');
    Route::post('/addworkforce', [WatsonsWorkForceController::class, 'clientadmin_watsons_workforce_submit'])->name('clientadmin_watsons_workforce_submit');
    Route::get('/allworkforce', [WatsonsWorkForceController::class, 'clientadmin_watsons_allworkforce'])->name('clientadmin_watsons_allworkforce');
    Route::get('/pendingworkforce', [WatsonsWorkForceController::class, 'clientadmin_watsons_pendingworkforce'])->name('clientadmin_watsons_pendingworkforce');
    Route::get('/completedworkforce', [WatsonsWorkForceController::class, 'clientadmin_watsons_completedworkforce'])->name('clientadmin_watsons_completedworkforce');
    Route::patch('/cancelworkforce/{id}', [WatsonsWorkForceController::class, 'clientadmin_watsons_cancelworkforce'])->name('clientadmin_watsons_cancelworkforce');
    Route::patch('/completedworkforce/{id}', [WatsonsWorkForceController::class, 'clientadmin_watsons_iscompletedworkforce'])->name('clientadmin_watsons_iscompletedworkforce');
    Route::patch('/updateworkforce/{id}', [WatsonsWorkForceController::class, 'clientadmin_watsons_updateworkforce'])->name('clientadmin_watsons_updateworkforce');
    // End App Watsons WorkForce Routes
    // =========================================


    // =========================================
    // App TimeOff Routes
    Route::get('/timeoff', [TimeOffController::class, 'clientadmin_timeoff'])->name('clientadmin_timeoff');
    Route::get('/alltimeoff', [TimeOffController::class, 'clientadmin_alltimeoff'])->name('clientadmin_alltimeoff');
    // =========================================


    // =========================================
    // App TimeLog Routes
    Route::get('/timelog', [TimeLogController::class, 'clientadmin_timelog'])->name('clientadmin_timelog');
    // =========================================

});
// ==========================================================================================================================================




// ==========================================================================================================================================
// User Routes
//
// Authenticated User Routes
Route::middleware('user')->prefix('user')->group(function () {

    // =========================================
    // User Dashboard Page Route
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user_dashboard');
    // =========================================


    // =========================================
    // User Profile Route
    Route::get('/profile/{id}', [UserController::class, 'profile'])->name('user_profile');
    Route::put('/profile/{id}/uploadprofilepicture', [UserController::class, 'uploadprofilepicture'])->name('user_uploadprofilepicture');
    Route::post('/changepassword/{id}', [UserController::class, 'changepassword'])->name('user_changepassword');
    // =========================================


    // =========================================
    // User Apps Route
    // Apps Main
    Route::get('/apps', [UserController::class, 'apps'])->name('user_apps');
    // =========================================


    // =========================================
    // App TimeOff Routes
    Route::get('/timeoff', [TimeOffController::class, 'user_timeoff'])->name('user_timeoff');
    Route::get('/alltimeoff', [TimeOffController::class, 'user_alltimeoff'])->name('user_alltimeoff');
    Route::get('/pendingtimeoff', [TimeOffController::class, 'user_pendingtimeoff'])->name('user_pendingtimeoff');
    Route::get('/approvedtimeoff', [TimeOffController::class, 'user_approvedtimeoff'])->name('user_approvedtimeoff');
    Route::get('/addtimeoff', [TimeOffController::class, 'user_addtimeoff'])->name('user_addtimeoff');
    Route::post('/addtimeoff', [TimeOffController::class, 'user_addtimeoff_submit'])->name('user_addtimeoff_submit');
    Route::get('/timeoff/attachment/{id}', [TimeOffController::class, 'user_viewAttachment'])->name('user.timeoff.attachment');
    Route::patch('/cancelworkforce/{id}', [TimeOffController::class, 'user_cancel_timeoff'])->name('user_cancel_timeoff');
    Route::post('/edit_timeoff/{id}', [TimeOffController::class, 'user_edit_timeoff_submit'])->name('user_edit_timeoff_submit');
    // =========================================

    // =========================================
    // App TimeLog Routes
    Route::get('/timelog', [TimeLogController::class, 'user_timelog'])->name('user_timelog');
    Route::post('/user/timelog/clock-in', [TimeLogController::class, 'user_clock_in'])->name('user_clock_in');
    Route::post('/user/timelog/clock-out', [TimeLogController::class, 'user_clock_out'])->name('user_clock_out');
    // =========================================

    // =========================================
    // App Payslip Routes
    Route::get('/payslip', [PayslipController::class, 'user_payslip'])->name('user_payslip');
    Route::get('/payslip/download/{filename}', [PayslipController::class, 'user_payslip_download'])->name('user_payslip_download');

    // =========================================



});

// User TimeLog routes
