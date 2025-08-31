<?php

use App\Http\Controllers\Login\LoginController;
use App\Http\Controllers\Login\ResetPasswordController;
use App\Http\Controllers\Login\LogoutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadmin\SuperadminController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Clientadmin\ClientadminController;
use App\Http\Controllers\User\UserController;
use App\Http\Middleware\Admin;
use Illuminate\Support\Facades\Auth;

// =========================================
// Login Page Route
Route::get('/', function () {
    return view('index');
})->name('index');
Route::get('/mobile', function () {
    return view('mobilefirst.dashboard');
})->name('mobile');
// =========================================


// =========================================
// Login Multi-Authentication Route
Route::post('/index', [LoginController::class, 'login_multiauth'])->name('login_multiauth');
// =========================================


// =========================================
// Forget-Password Routes
Route::get('forget-password', [ResetPasswordController::class, 'forget_password'])->name('forget_password');
Route::post('forget-password', [ResetPasswordController::class, 'forget_password_submit'])->name('forget_password_submit');
Route::get('reset-password/{token}/{email}', [ResetPasswordController::class, 'reset_password'])->name('reset_password');
Route::post('reset-password/{token}/{email}', [ResetPasswordController::class, 'reset_password_submit'])->name('reset_password_submit');
// =========================================


// =========================================
// Logout Route
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
// =========================================



// =========================================
// User Dashboard Page Route
Route::middleware('user')->prefix('user')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('user_dashboard');
});
// =========================================


// =========================================
// Clientadmin Dashboard Page Route
Route::middleware('clientadmin')->prefix('clientadmin')->group(function () {
    Route::get('/dashboard', [ClientadminController::class, 'dashboard'])->name('clientadmin_dashboard');
});
// =========================================



// =========================================
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
    Route::get('/editsuperadmin/{id}', [SuperadminController::class, 'editsuperadmin'])->name('superadmin_editsuperadmin');
    Route::put('/editsuperadmin/{id}', [SuperadminController::class, 'editsuperadmin_submit'])->name('superadmin_editsuperadmin_submit');
    Route::put('/editsuperadmin_uploadprofilepicture/{id}', [SuperadminController::class, 'editsuperadmin_uploadprofilepicture'])->name('superadmin_editsuperadmin_uploadprofilepicture');
    Route::post('/changepassword/{id}', [SuperadminController::class, 'changepassword'])->name('superadmin_changepassword');
    // =========================================
   
    // =========================================
    // Access Management Routes
    Route::get('/accessmanagement', [SuperadminController::class, 'accessmanagement'])->name('superadmin_accessmanagement');
    Route::get('/userpermissions', [SuperadminController::class, 'userpermissions'])->name('superadmin_userpermissions');
    Route::get('/apppermissions', [SuperadminController::class, 'apppermissions'])->name('superadmin_apppermissions');
    // End Access Management Routes
    // =========================================

    // =========================================
    // User Management Routes
    Route::get('/usermanagement', [SuperadminController::class, 'usermanagement'])->name('superadmin_usermanagement');

    // Superadmin User Management Routes
    Route::get('/usersuperadmin', [SuperadminController::class, 'usersuperadmin'])->name('superadmin_usersuperadmin');
    Route::get('/addsuperadmin', [SuperadminController::class, 'addsuperadmin'])->name('superadmin_addsuperadmin');
    Route::post('/addsuperadmin', [SuperadminController::class, 'addsuperadmin_submit'])->name('superadmin_addsuperadmin_submit');
    Route::get('/editusersuperadmin/{id}', [SuperadminController::class, 'editusersuperadmin'])->name('superadmin_editusersuperadmin');
    Route::put('/editusersuperadmin/{id}', [SuperadminController::class, 'editusersuperadmin_submit'])->name('superadmin_editusersuperadmin_submit');
    Route::patch('/softdelete/{id}', [SuperadminController::class, 'softdelete'])->name('superadmin_softdelete');
    Route::patch('/suspend/{id}', [SuperadminController::class, 'suspend'])->name('superadmin_suspend');
    Route::get('/usersuperadmin/export', [SuperadminController::class, 'exportusersuperadmin'])->name('superadmin_export_usersuperadmin');

    // Admin Management Routes
    Route::get('/useradmin', [SuperadminController::class, 'useradmin'])->name('superadmin_useradmin');
    Route::get('/addadmin', [SuperadminController::class, 'addadmin'])->name('superadmin_addadmin');
    Route::post('/addadmin', [SuperadminController::class, 'addadmin_submit'])->name('superadmin_addadmin_submit');
    Route::get('/edituseradmin/{id}', [SuperadminController::class, 'edituseradmin'])->name('superadmin_edituseradmin');
    Route::put('/edituseradmin/{id}', [SuperadminController::class, 'edituseradmin_submit'])->name('superadmin_edituseradmin_submit');
    Route::patch('/adminsoftdelete/{id}', [SuperadminController::class, 'adminsoftdelete'])->name('superadmin_adminsoftdelete');
    Route::patch('/adminsuspend/{id}', [SuperadminController::class, 'adminsuspend'])->name('superadmin_adminsuspend');
    Route::get('/useradmin/export', [SuperadminController::class, 'exportuseradmin'])->name('superadmin_export_useradmin');

    // Client Admin Management Routes
    Route::get('/userclientadmin', [SuperadminController::class, 'userclientadmin'])->name('superadmin_userclientadmin');
    Route::get('/addclientadmin', [SuperadminController::class, 'addclientadmin'])->name('superadmin_addclientadmin');
    Route::post('/addclientadmin', [SuperadminController::class, 'addclientadmin_submit'])->name('superadmin_addclientadmin_submit');
    Route::get('/edituserclientadmin/{id}', [SuperadminController::class, 'edituserclientadmin'])->name('superadmin_edituserclientadmin');
    Route::put('/edituserclientadmin/{id}', [SuperadminController::class, 'edituserclientadmin_submit'])->name('superadmin_edituserclientadmin_submit');
    Route::patch('/clientadminsoftdelete/{id}', [SuperadminController::class, 'clientadminadminsoftdelete'])->name('superadmin_clientadminsoftdelete');
    Route::patch('/clientadminsuspend/{id}', [SuperadminController::class, 'clientadminadminsuspend'])->name('superadmin_clientadminsuspend');
    Route::get('/userclientadmin/export', [SuperadminController::class, 'exportuserclientadmin'])->name('superadmin_export_userclientadmin');

    // User Employee Management Routes
    Route::get('/useremployee', [SuperadminController::class, 'useremployee'])->name('superadmin_useremployee');
    Route::post('/useremployee/import', [SuperadminController::class, 'importemployee'])->name('superadmin_useremployee_import');
    Route::get('/useremployee/export', [SuperadminController::class, 'exportemployee'])->name('superadmin_useremployee_export');

    // End User Management Routes
    // =========================================


    // ========================================= 
    // Client Management Routes
    Route::get('/clientmanagement', [SuperadminController::class, 'clientmanagement'])->name('superadmin_clientmanagement');
    Route::get('/clients', [SuperadminController::class, 'clients'])->name('superadmin_clients');
    Route::get('/clients/{id}/viewclients', [SuperadminController::class, 'viewclients'])->name('superadmin_viewclients');
    Route::get('/clients/{id}/editclient', [SuperadminController::class, 'editclient'])->name('superadmin_editclient');
    Route::put('/clients/{id}', [SuperadminController::class, 'editclient_submit'])->name('superadmin_editclient_submit');
    Route::patch('/clients/{id}/softdelete', [SuperadminController::class, 'softdeleteclient'])->name('superadmin_softdeleteclient');
    Route::get('/addclient', [SuperadminController::class, 'addclient'])->name('superadmin_addclient');
    Route::post('/addclient', [SuperadminController::class, 'addclient_submit'])->name('superadmin_addclient_submit');
    Route::put('/editclient_uploadprofilepicture/{id}', [SuperadminController::class, 'editclient_uploadprofilepicture'])->name('superadmin_editclient_uploadprofilepicture');
    Route::post('/clients/import', [SuperadminController::class, 'importclients'])->name('superadmin_clients_import');
    Route::get('/clients/export', [SuperadminController::class, 'exportclients'])->name('superadmin_clients_export');

    // Branch Management Routes
    Route::get('/branches', [SuperadminController::class, 'branches'])->name('superadmin_branches');
    Route::get('/addbranch', [SuperadminController::class, 'addbranch'])->name('superadmin_addbranch');
    Route::post('/addbranch', [SuperadminController::class, 'addbranch_submit'])->name('superadmin_addbranch_submit');
    Route::get('/branches/{id}/viewbranches', [SuperadminController::class, 'viewbranch'])->name('superadmin_viewbranch');
    Route::get('/branches/{id}/editbranch', [SuperadminController::class, 'editbranch'])->name('superadmin_editbranch');
    Route::put('/branches/{id}', [SuperadminController::class, 'editbranch_submit'])->name('superadmin_editbranch_submit');
    Route::patch('/branches/{id}/softdelete', [SuperadminController::class, 'softdeletebranch'])->name('superadmin_softdeletebranch');
    Route::post('/branches/import', [SuperadminController::class, 'importbranches'])->name('superadmin_branches_import');
    Route::get('/branches/export', [SuperadminController::class, 'exportbranches'])->name('superadmin_branches_export');
    // End Client Management Routes
    // =========================================


    // =========================================
    // App Management Routes Start
    //
    Route::get('/apps', [SuperadminController::class, 'apps'])->name('superadmin_apps');

    // =========================================
    // App Locator Routes
    Route::get('/applocator', [SuperadminController::class, 'applocator'])->name('superadmin_applocator');
    Route::get('/applocatorclient', [SuperadminController::class, 'applocatorclient'])->name('superadmin_applocatorclient');
    Route::get('/applocatorbranch', [SuperadminController::class, 'applocatorbranch'])->name('superadmin_applocatorbranch');
    Route::get('/applocatordata', [SuperadminController::class, 'applocatordata'])->name('superadmin_applocatordata');
    Route::get('/applocatoremployee', [SuperadminController::class, 'applocatoremployee'])->name('superadmin_applocatoremployee');
    Route::get('/applocatoremployee/search-users', [SuperadminController::class, 'searchUsers'])->name('superadmin_search_users');
    Route::get('/applocatoremployeemap/{id}', [SuperadminController::class, 'applocatoremployeemap'])->name('superadmin_applocatoremployeemap');
    // End App Locator Routes
    // =========================================

    // =========================================
    // App People Routes
    Route::get('/apppeople', [SuperadminController::class, 'apppeople'])->name('superadmin_apppeople');
    Route::get('/apppeopleview/{id}', [SuperadminController::class, 'apppeopleview'])->name('superadmin_apppeopleview');
    // End App People Routes
    // =========================================

    // =========================================
    // App WorkForce Routes
    Route::get('/appworkforce', [SuperadminController::class, 'appworkforce'])->name('superadmin_appworkforce');
    Route::post('/appworkforce', [SuperadminController::class, 'appworkforce_submit'])->name('superadmin_workforce_submit');
    // End App WorkForce Routes 
    // =========================================

    //
    // End App Management Routes
    // =========================================

});




// =========================================
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
    Route::put('/profile/{id}/uploadprofilepicture', [AdminController::class, 'uploadprofilepicture'])->name('uploadprofilepicture');
    Route::post('/changepassword/{id}', [AdminController::class, 'changepassword'])->name('admin_changepassword');

});
// =========================================