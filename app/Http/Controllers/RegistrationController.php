<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Voter;
use App\Models\Panchayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class RegistrationController extends Controller
{
    public function showRegister()
    {
        $panchayats = Panchayat::all();
        return view('auth.register', compact('panchayats'));
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
                'panchayat_id' => 'required|exists:panchayats,id',
                'voter_id_number' => 'required|string',
                'aadhaar_number' => 'required|string|digits:16',
                'mobile' => 'required|string|digits:10',
                'dob' => 'required|date|before_or_equal:-18 years',
            ], [
                'dob.before_or_equal' => 'You must be at least 18 years old to register as a voter.',
            ]);

            $fullName = trim($request->first_name . ' ' . ($request->middle_name ?? '') . ' ' . $request->last_name);
            $fullName = ucwords(strtolower(preg_replace('/\s+/', ' ', $fullName)));

            DB::beginTransaction();

            $email = strtolower($request->email);
            $user = User::where('email', $email)->first();
            $requiresOtp = true;

            $existingVoterId = Voter::where('voter_id_number', $request->voter_id_number)
                ->whereIn('status', ['pending', 'approved'])
                ->first();

            if ($existingVoterId && (!$user || $existingVoterId->user_id !== $user->id)) {
                return back()->withErrors(['voter_id_number' => 'This Voter ID is already registered and verified/pending.'])->withInput();
            }

            if ($user) {
                if (!Hash::check($request->password, $user->password)) {
                    return back()->withErrors(['email' => 'This email is already in our system. Please provide the correct password to link your registration.'])->withInput();
                }

                $user->update([
                    'name' => $fullName,
                    'panchayat_id' => $request->panchayat_id,
                ]);

                if ($user->is_verified) {
                    $requiresOtp = false;
                }
            } else {
                $user = User::create([
                    'name' => $fullName,
                    'email' => $email,
                    'password' => $request->password,
                    'role' => 'voter',
                    'panchayat_id' => $request->panchayat_id,
                    'is_verified' => false,
                ]);
            }

            Voter::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'voter_id_number' => $request->voter_id_number,
                    'aadhaar_number' => $request->aadhaar_number,
                    'mobile' => $request->mobile,
                    'dob' => $request->dob,
                    'status' => 'pending',
                    'has_voted' => false,
                ]
            );

            DB::commit();

            if (!$requiresOtp) {
                return redirect()->route('login')->with('success', 'Voter registration submitted and sent to BLO for approval.');
            }

            $otp = (string) rand(100000, 999999);
            Log::info("OTP for {$user->email}: {$otp}");

            $user->update([
                'otp' => Hash::make($otp),
                'otp_expires_at' => now()->addMinutes(5),
            ]);

            try {
                Mail::to($user->email)->send(new OtpMail($otp));
            } catch (\Exception $e) {
                Log::warning("Real Email failed to send to {$user->email}, but OTP is in logs: " . $e->getMessage());
            }

            session(['register_email' => $user->email]);

            return redirect()->route('otp.verify')->with('success', 'OTP sent! Please check your email inbox (and spam folder).');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Registration Error: " . $e->getMessage());
            return back()->with('error', 'Registration Failed: ' . $e->getMessage())->withInput();
        }
    }

    public function showOtp()
    {
        if (!session('register_email')) {
            return redirect()->route('register');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $email = strtolower(session('register_email'));
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('register')->withErrors(['email' => 'Session expired. Register again.']);
        }

        if (!Hash::check($request->otp, $user->otp)) {
            return back()->withErrors(['otp' => 'Invalid OTP']);
        }

        if (now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'OTP Expired.']);
        }

        $user->update([
            'is_verified' => true,
            'otp' => null,
            'otp_expires_at' => null,
        ]);

        session()->forget('register_email');

        return redirect()->route('login')->with('success', 'Email verified! Your registration is now pending official approval.');
    }
}
