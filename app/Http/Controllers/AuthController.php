<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
            if ($user->role === 'blo') {
                return redirect()->route('blo.dashboard');
            }

            $activeDashboard = session('active_dashboard', 'voter');
            if ($activeDashboard === 'candidate') {
                return redirect()->route('candidate.dashboard');
            }

            return redirect()->route('voter.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        // We use the tab input instead of database roles to determine login flow
        $tab = $request->input('role', 'voter');
        $remember = $request->has('remember');

        $email = '';
        $password = $request->input('password');
        $candidateKey = trim($request->input('candidate_key')); // using candidate_key parameter

        // 1. Gather credentials based on the selected tab
        if ($tab === 'voter') {
            $loginInput = trim($request->input('login_identifier'));

            if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
                $email = strtolower($loginInput);
            } else {
                $voter = \App\Models\Voter::where('voter_id_number', $loginInput)->first();
                if (! $voter) {
                    return back()->withErrors(['login_identifier' => 'Invalid Voter ID or Email.'])->withInput();
                }
                $email = strtolower($voter->user->email ?? '');
            }
        } elseif ($tab === 'candidate') {
            $email = trim(strtolower($request->input('candidate_email')));
            if (empty($candidateKey)) {
                return back()->withErrors(['candidate_key' => 'Candidate Key is required.'])->withInput();
            }
        } else {
            // Admin/BLO
            $email = trim(strtolower($request->input('admin_email')));
        }

        if (empty($email) || empty($password)) {
            return back()->withErrors(['password' => 'Email and Password are required.'])->withInput();
        }

        // 2. Authentication should first validate ONLY email + password using Auth::attempt()
        $credentials = ['email' => $email, 'password' => $password];

        if (! Auth::attempt($credentials, $remember)) {
            if ($tab === 'voter') {
                return back()->withErrors(['login_identifier' => 'Invalid email/voter ID or password.'])->withInput();
            } elseif ($tab === 'candidate') {
                return back()->withErrors(['password' => 'Invalid email or password.'])->withInput();
            } else {
                return back()->withErrors(['admin_email' => 'The provided credentials do not match our records.'])->withInput();
            }
        }

        // 3. After successful authentication, behavior depends on the selected tab
        $user = Auth::user();

        // Check if email is verified (OTP) except for admin/blo
        if (! $user->is_verified && $user->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->with('error', 'Your email is not verified. Please register again to receive a new OTP.');
        }

        // Clear previous session completely to avoid session confusion, then regenerate
        $request->session()->flush();
        Auth::login($user, $remember);
        $request->session()->regenerate();

        if ($tab === 'voter') {
            // Voter Login Tab:
            // - Requires email + password only.
            // - Should redirect to voter.dashboard.
            // - No role column validation required.
            // - No candidate_key required.
            if (! $user->voter) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['login_identifier' => 'Not registered as voter or invalid credentials.'])->withInput();
            }

            $request->session()->put('active_dashboard', 'voter');

            return redirect()->route('voter.dashboard');

        } elseif ($tab === 'candidate') {
            // Candidate Login Tab:
            // Verify: a) User exists in candidates table b) candidate_key matches
            $candidate = \App\Models\Candidate::where('user_id', $user->id)->first();

            // Accommodate schema where column might be candidate_key or candidate_id
            $dbCandidateKey = $candidate->candidate_key ?? ($candidate->candidate_id ?? null);

            if (! $candidate || $dbCandidateKey !== $candidateKey) {
                // If candidate record does not exist OR key is invalid:
                // Immediately logout and return validation error.
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['candidate_key' => 'Invalid Candidate Key or you are not registered as a candidate.'])->withInput();
            }

            // Optional: Block unapproved candidates
            if ($candidate->status !== 'approved') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->with('error', 'Your candidate account is pending admin approval. Please wait for verification.');
            }

            $request->session()->put('active_dashboard', 'candidate');

            return redirect()->route('candidate.dashboard');

        } else {
            // Admin/BLO logic
            if ($user->role !== 'admin' && $user->role !== 'blo') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors(['admin_email' => 'Please use the correct tab for your role.'])->withInput();
            }

            $request->session()->put('active_dashboard', $user->role);
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('blo.dashboard');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
