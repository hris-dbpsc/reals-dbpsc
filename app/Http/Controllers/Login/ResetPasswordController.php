<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Superadmin;
use App\Models\Admin;
use App\Models\Clientadmin;
use App\Models\User;
use App\Mail\Websitemail;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    // =========================================
    // Forget-Password Routes
    public function forget_password()
    {
        return view('forget_password');
    }
    // =========================================


    // =========================================
    // Reset-Password Route With Email and Token
    public function forget_password_submit(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $modelMap = [
            Superadmin::class  => 'Superadmin',
            Admin::class       => 'Admin',
            Clientadmin::class => 'Clientadmin',
            User::class        => 'User',
        ];

        $account = null;
        $accountType = null;

        foreach ($modelMap as $model => $type) {
            if ($account = $model::where('email', $request->email)->first()) {
            $accountType = $type;
            break;
            }
        }

        if (!$account) {
            // Return error and keep the old email in the input
            return back()->withInput()->withErrors(['email' => 'Email not found or invalid!']);
        }

        // Generate and save token
        $token = hash('sha256', uniqid((string)time(), true));
        $account->token = $token;
        $account->save();

        // Use account type in the email for clarity
        $link = route('reset_password', [$token, $request->email]);
        $subject = 'REALS - DBPSC[RESET-PASSWORD]';
        $message = '
        <div style="max-width:600px;margin:30px auto;padding:30px;border:1px solid #e3e3e3;border-radius:8px;font-family:sans-serif;background:#f8f9fa;">
            <div style="text-align:center;">
                <h2 style="color:#0d6efd;margin-bottom:20px;">REALS - DBPSC</h2>
            </div>
            <hr style="margin:20px 0;">
            <h3 style="color:#0d6efd;margin-bottom:20px;">Password Reset Request</h3>
            <p style="font-size:16px;color:#212529;">
                We received a request to reset your password for your ' . $accountType . ' account.<br>
                If you did not request this, you can safely ignore this email.
            </p>
            <p style="font-size:16px;color:#212529;">
                To reset your password, please click the button below:
            </p>
            <div style="text-align:center;margin:30px 0;">
                <a href="' . $link . '" style="display:inline-block;padding:12px 30px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:5px;font-size:16px;">
                    Reset Password
                </a>
            </div>
            <p style="font-size:14px;color:#6c757d;">
                Or copy and paste this link into your browser:<br>
                <span style="word-break:break-all;">' . $link . '</span>
            </p>
            <hr style="margin:20px 0;">
            <p style="font-size:13px;color:#adb5bd;text-align:center;">
                This link will expire after use or after a certain period for your security.<br>
                <span style="color:#dc3545;">This email is auto generated. Do not reply.</span>
            </p>
        </div>
        ';

        \Mail::to($request->email)->send(new Websitemail($subject, $message));

        return redirect()->back()->with('success', 'Check your email for the reset link.');
    }
    // =========================================



    // =========================================
    // Reset Superadmin password route
    public function reset_password($token, $email)
    {
        $modelMap = [
            Superadmin::class  => 'Superadmin',
            Admin::class       => 'Admin',
            Clientadmin::class => 'Clientadmin',
            User::class        => 'User',
        ];

        $account = null;

        foreach ($modelMap as $model => $type) {
            if ($account = $model::where('email', $email)->where('token', $token)->first()) {
                break;
            }
        }

        if (!$account) {
            return redirect()->route('index')->with('error', 'Invalid token or email!');
        }

        return view('reset_password', compact('token', 'email'));
    }
    // =========================================


    // =========================================
    // Reset Superadmin password submit route
    public function reset_password_submit(Request $request, $token, $email)
    {
        $request->validate([
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        $modelMap = [
            Superadmin::class  => 'Superadmin',
            Admin::class       => 'Admin',
            Clientadmin::class => 'Clientadmin',
            User::class        => 'User',
        ];

        $account = null;

        foreach ($modelMap as $model => $type) {
            if ($account = $model::where('email', $email)->where('token', $token)->first()) {
                break;
            }
        }

        if (!$account) {
            return redirect()->route('index')->with('error', 'Invalid token or email!');
        }

        $account->password = Hash::make($request->password);
        $account->token = ''; // Clear the token after password reset
        $account->save();

        return redirect()->route('index')->with('success', 'Password reset successfully. Please login.');
    }
    // =========================================
}
