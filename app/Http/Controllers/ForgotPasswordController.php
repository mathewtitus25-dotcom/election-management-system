<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    // Step 1: Show email form
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // Step 2: Send OTP to email (simulated via Log)
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        $otp = (string) rand(100000, 999999);

        // ✅ Store hashed OTP
        $user->update([
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // Simulate email — check storage/logs/laravel.log
        Log::info("Password Reset OTP for {$user->email}: {$otp}");

        // ✅ Send Real Email with Log Fallback
        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::warning("Real Email failed to send to {$user->email}, but OTP is in logs: ".$e->getMessage());
        }

        session(['reset_email' => $user->email]);

        return redirect()->route('password.reset.form')
            ->with('success', 'Reset OTP sent! Please check your email inbox.');
    }

    // Step 3: Show OTP + new password form
    public function showReset()
    {
        if (! session('reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    // Step 4: Validate OTP + update password
    public function reset(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
            'password' => 'required|min:8|confirmed',
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();

        if (! $user) {
            return redirect()->route('password.request')->with('error', 'Session expired. Please try again.');
        }

        // ✅ Secure OTP check
        if (! Hash::check($request->otp, $user->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        if (now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP has expired. Please request a new one.']);
        }

        // Update password and clear OTP
        $user->update([
            'password' => Hash::make($request->password),
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        session()->forget('reset_email');

        return redirect()->route('login')->with('success', 'Password updated successfully! Please log in.');
    }
}
