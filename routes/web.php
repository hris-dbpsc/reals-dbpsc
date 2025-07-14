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
// Login Page Routes End

// Superadmin Routes
// Authenticated Superadmin Routes
Route::middleware('superadmin')->prefix('superadmin')->group(function(){
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin_dashboard');
});

// Prefix for Superadmin Routes
Route::prefix('superadmin')->group(function(){
    Route::get('/index', [SuperadminController::class, 'index'])->name('superadmin_index');
    Route::post('/index',[SuperadminController::class, 'login_submit'])->name('superadmin_login_submit');
    Route::get('/forget-password', [SuperadminController::class, 'forget_password'])->name('superadmin_forget_password');
    Route::post('/forget-password',[SuperadminController::class, 'forget_password_submit'])->name('superadmin_forget_password_submit');
    Route::get('/logout',[SuperadminController::class, 'logout'])->name('superadmin_logout');
});

