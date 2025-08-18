<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Superadmin\SuperadminController;

// Login Page Routes Start
// Main Landing Page Route
Route::get('/', function () {
    return view('users.index');
});
Route::get('/user', function () {
    return view('users.index');
});

// Superadmin Login Page Route
Route::get('/superadmin', function () {
    return view('superadmin.index');
})->name('superadmin.index');   

// Admin Login Page Route
Route::get('/admin', function () {
    return view('admin.index');
})->name('admin.index');    

// Client Admin Login Page Route
Route::get('/client', function () {
    return view('clientadmin.index');
})->name('clientadmin.index');



// Superadmin Routes
// Authenticated Superadmin Routes
Route::middleware('superadmin')->prefix('superadmin')->group(function(){
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin_dashboard');

    Route::get('/qrcode', [SuperadminController::class, 'qrcode'])->name('superadmin_qrcode');

    // User Management Routes Start
    Route::get('/usermanagement', [SuperadminController::class, 'usermanagement'])->name('superadmin_usermanagement');
    // Superadmin Management Routes
    Route::get('/usersuperadmin', [SuperadminController::class, 'usersuperadmin'])->name('superadmin_usersuperadmin');
    Route::get('/addsuperadmin', [SuperadminController::class, 'addsuperadmin'])->name('superadmin_addsuperadmin');
    Route::post('/addsuperadmin', [SuperadminController::class, 'addsuperadmin_submit'])->name('superadmin_addsuperadmin_submit');
    Route::get('/editsuperadmin/{id}', [SuperadminController::class, 'editsuperadmin'])->name('superadmin_editsuperadmin');
    Route::put('/editsuperadmin/{id}', [SuperadminController::class, 'editsuperadmin_submit'])->name('superadmin_editsuperadmin_submit');
    Route::post('/changepassword/{id}', [SuperadminController::class, 'changepassword'])->name('superadmin_changepassword');
    Route::put('/editsuperadmin_uploadprofilepicture/{id}', [SuperadminController::class, 'editsuperadmin_uploadprofilepicture'])->name('superadmin_editsuperadmin_uploadprofilepicture');
    Route::get('/editusersuperadmin/{id}', [SuperadminController::class, 'editusersuperadmin'])->name('superadmin_editusersuperadmin');
    Route::put('/editusersuperadmin/{id}', [SuperadminController::class, 'editusersuperadmin_submit'])->name('superadmin_editusersuperadmin_submit');
    Route::patch('/softdelete/{id}', [SuperadminController::class, 'softdelete'])->name('superadmin_softdelete');
    Route::patch('/suspend/{id}', [SuperadminController::class, 'suspend'])->name('superadmin_suspend');    
    Route::get('/usersuperadmin/export', [SuperadminController::class, 'exportusersuperadmin'])->name('superadmin_export_usersuperadmin');

    // Admin Management Routes
    Route::get('/useradmin', [SuperadminController::class, 'useradmin'])->name('superadmin_useradmin');
    Route::get('/addadmin', [SuperadminController:: class, 'addadmin'])->name('superadmin_addadmin');
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


    // User Management Routes
    Route::get('/useremployee', [SuperadminController::class, 'useremployee'])->name('superadmin_useremployee');
    // Import Employee Route
    Route::post('/useremployee/import', [SuperadminController::class, 'importemployee'])->name('superadmin_useremployee_import');
    // Export Employee Route
    Route::get('/useremployee/export', [SuperadminController::class, 'exportemployee'])->name('superadmin_useremployee_export');

    // Client Management Routes
    Route::get('/clientmanagement', [SuperadminController::class, 'clientmanagement'])->name('superadmin_clientmanagement');
    Route::get('/clients', [SuperadminController::class, 'clients'])->name('superadmin_clients');
    Route::get('/clients/{id}/viewclients', [SuperadminController::class, 'viewclients'])->name('superadmin_viewclients');
    Route::get('/clients/{id}/editclients', [SuperadminController::class, 'editclients'])->name('superadmin_editclients');
    Route::put('/clients/{id}', [SuperadminController::class, 'editclients_submit'])->name('superadmin_editclients_submit');
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

    
    // App Management Routes Start
    Route::get('/apps', [SuperadminController::class, 'apps'])->name('superadmin_apps');
            // App Locator Routes
    Route::get('/applocator', [SuperadminController::class, 'applocator'])->name('superadmin_applocator');
    Route::get('/applocatorclient', [SuperadminController::class, 'applocatorclient'])->name('superadmin_applocatorclient');
    Route::get('/applocatorbranch', [SuperadminController::class, 'applocatorbranch'])->name('superadmin_applocatorbranch');
    Route::get('/applocatoremployee', [SuperadminController::class, 'applocatoremployee'])->name('superadmin_applocatoremployee');
    // App Management Routes End

});

// Prefix for Superadmin Routes
Route::prefix('superadmin')->group(function(){
    Route::get('/index', [SuperadminController::class, 'index'])->name('superadmin_index');
    Route::post('/index',[SuperadminController::class, 'login_submit'])->name('superadmin_login_submit');

    Route::get('/forget-password', [SuperadminController::class, 'forget_password'])->name('superadmin_forget_password');
    Route::post('/forget-password',[SuperadminController::class, 'forget_password_submit'])->name('superadmin_forget_password_submit');

    Route::get('/reset-password/{token}/{email}',[SuperadminController::class, 'reset_password'])->name('superadmin_reset_password');
    Route::post('/reset-password/{token}/{email}',[SuperadminController::class, 'reset_password_submit'])->name('superadmin_reset_password_submit');

    Route::get('/logout',[SuperadminController::class, 'logout'])->name('superadmin_logout');

});

