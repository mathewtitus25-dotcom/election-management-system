<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\Candidate;
use App\Models\Panchayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CandidateController extends Controller
{
    public function showRegistrationForm()
    {
        $panchayats = Panchayat::all();

        return view('candidate.register', compact('panchayats'));
    }

    public function dashboard()
    {
        $user = Auth::user();
        $candidate = $user->candidate;

        if (! $candidate || $candidate->status !== 'approved') {
            return redirect()->route('welcome')->with('error', 'Your candidate application is not yet approved.');
        }

        $panchayat = $user->panchayat;

        $totalVoters = \App\Models\Voter::whereHas('user', function ($q) use ($panchayat) {
            $q->where('panchayat_id', $panchayat->id);
        })->where('status', 'approved')->count();

        $votedCount = \App\Models\Voter::whereHas('user', function ($q) use ($panchayat) {
            $q->where('panchayat_id', $panchayat->id);
        })->where('status', 'approved')->where('has_voted', true)->count();

        $turnoutPercent = $totalVoters > 0 ? round(($votedCount / $totalVoters) * 100, 1) : 0;

        $opponents = Candidate::whereHas('user', function ($q) use ($panchayat, $user) {
            $q->where('panchayat_id', $panchayat->id)
                ->where('id', '!=', $user->id);
        })->where('status', 'approved')->with('user')->get();

        $votersList = \App\Models\Voter::whereHas('user', function ($q) use ($panchayat) {
            $q->where('panchayat_id', $panchayat->id);
        })->where('status', 'approved')->with('user')->get();

        return view('candidate.dashboard', compact('user', 'candidate', 'panchayat', 'totalVoters', 'votedCount', 'turnoutPercent', 'opponents', 'votersList'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'dob' => 'required|date|before_or_equal:-21 years',
            'gender' => 'required|string',
            'mobile' => 'required|string|digits:10',
            'voter_id' => 'required|string',
            'aadhaar' => 'required|string|digits:16',
            'qualification' => 'required|string|max:255',
            'address' => 'required|string',
            'panchayat_id' => 'required|exists:panchayats,id',
            'manifesto' => 'required|string|min:10',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'dob.before_or_equal' => 'You must be at least 21 years old to apply as a candidate.',
        ]);

        $fullName = trim($request->first_name.' '.($request->middle_name ?? '').' '.$request->last_name);
        $fullName = ucwords(strtolower(preg_replace('/\s+/', ' ', $fullName)));

        $email = strtolower($request->email);
        $user = \App\Models\User::where('email', $email)->first();
        $isExistingUser = (bool) $user;

        if ($user) {
            if (! Hash::check($request->password, $user->password)) {
                return back()->withErrors([
                    'password' => 'This email already exists. Please enter the correct account password.',
                ])->withInput();
            }

            $existingCandidate = $user->candidate;
            if ($existingCandidate && in_array($existingCandidate->status, ['pending', 'approved'])) {
                return back()->with('error', 'A candidate application is already pending/approved for this email address.')->withInput();
            }

            $user->update([
                'name' => $fullName,
                'panchayat_id' => $request->panchayat_id,
            ]);
        } else {
            $user = \App\Models\User::create([
                'name' => $fullName,
                'email' => $email,
                'password' => $request->password,
                'role' => 'voter',
                'panchayat_id' => $request->panchayat_id,
                'is_verified' => false,
            ]);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('candidate_photos', 'public');
        }

        $candidateData = [
            'user_id' => $user->id,
            'email' => $request->email,
            'manifesto' => $request->manifesto,
            'status' => 'pending',
            'votes_count' => 0,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'mobile' => $request->mobile,
            'voter_id' => $request->voter_id,
            'aadhaar' => $request->aadhaar,
            'address' => $request->address,
            'qualification' => ucwords(strtolower($request->qualification)),
            'photo' => $photoPath,
        ];

        $existingCandidate = $user->candidate;
        if ($existingCandidate && $existingCandidate->status === 'rejected') {
            $existingCandidate->update(array_merge($candidateData, ['candidate_id' => null]));
        } else {
            Candidate::create($candidateData);
        }

        if ($isExistingUser) {
            return redirect()->route('login')->with('success', 'Candidate application submitted and sent for admin review.');
        }

        $otp = (string) rand(100000, 999999);
        Log::info("Candidate Registration OTP for {$user->email}: {$otp}");

        $user->update([
            'otp' => Hash::make($otp),
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otp));
        } catch (\Exception $e) {
            Log::warning("Real Email failed to send to {$user->email}, but OTP is in logs: ".$e->getMessage());
        }

        session(['register_email' => $user->email]);

        return redirect()->route('otp.verify')->with('success', 'Application submitted! Please check your email inbox (and spam folder).');
    }
}
