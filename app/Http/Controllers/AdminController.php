<?php

namespace App\Http\Controllers;

use App\Mail\CandidateApprovedMail;
use App\Models\BLO;
use App\Models\Candidate;
use App\Models\ElectionConfig;
use App\Models\Panchayat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        $panchayats = Panchayat::all();
        $blos = BLO::with(['user.panchayat'])->get();

        // Candidates management
        $pendingCandidates = Candidate::where('status', 'pending')->with('user.panchayat')->get();
        $approvedCandidates = Candidate::where('status', 'approved')->with('user.panchayat')->get();

        $electionConfig = ElectionConfig::first();
        if (! $electionConfig) {
            $electionConfig = ElectionConfig::create(['is_active' => false]);
        }

        // ✅ Admin Analytics Dashboard Stats
        $totalApprovedVoters = \App\Models\Voter::where('status', 'approved')->count();
        $totalVotesCast = \App\Models\Voter::where('has_voted', true)->count();
        $turnoutPercent = $totalApprovedVoters > 0 ? round(($totalVotesCast / $totalApprovedVoters) * 100, 1) : 0;

        $topCandidate = Candidate::where('status', 'approved')
            ->orderByDesc('votes_count')
            ->with('user.panchayat')
            ->first();

        $votesPerPanchayat = Panchayat::withCount(['users as votes_count' => function ($q) {
            $q->whereHas('voter', function ($q2) {
                $q2->where('has_voted', true);
            });
        }])->get()->pluck('votes_count', 'name')->toArray();

        $stats = [
            'total_panchayats' => Panchayat::count(),
            'total_approved_voters' => $totalApprovedVoters,
            'total_approved_candidates' => Candidate::where('status', 'approved')->count(),
            'total_pending_voters' => \App\Models\Voter::where('status', 'pending')->count(),
            'total_votes_cast' => $totalVotesCast,
            'total_blos' => BLO::count(),
            'turnout_percentage' => $turnoutPercent,
            'top_candidate' => $topCandidate ? $topCandidate->user->name . ' (' . $topCandidate->votes_count . ' votes)' : 'N/A',
            'votes_per_panchayat' => $votesPerPanchayat,
        ];

        return view('admin.dashboard', compact('panchayats', 'blos', 'pendingCandidates', 'approvedCandidates', 'electionConfig', 'stats'));
    }

    public function createBLO(Request $request)
    {
        Log::info('Attempting to create BLO', $request->all());

        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
                'panchayat_id' => 'required|exists:panchayats,id',
            ]);

            $user = User::create([
                'name' => ucwords(strtolower($request->name)),
                'email' => strtolower($request->email),
                'password' => $request->password,
                'role' => 'blo',
                'panchayat_id' => $request->panchayat_id,
                'is_verified' => true,
            ]);

            BLO::create([
                'user_id' => $user->id,
                'is_active' => true,
            ]);

            Log::info('BLO created successfully', ['user_id' => $user->id]);

            return back()->with('success', 'BLO created successfully.');
        } catch (\Exception $e) {
            Log::error('BLO creation failed', ['error' => $e->getMessage()]);

            return back()->withErrors(['error' => 'Failed to create BLO: '.$e->getMessage()])->withInput();
        }
    }

    public function toggleBLO($id)
    {
        $blo = BLO::findOrFail($id);
        $blo->is_active = ! $blo->is_active;
        $blo->save();

        $status = $blo->is_active ? 'activated' : 'deactivated';

        return back()->with('success', "BLO {$status} successfully.");
    }

    public function updateElection(Request $request)
    {
        $config = ElectionConfig::first();
        $isActive = $request->has('is_active');

        $config->update([
            'is_active' => $isActive,
            'start_date' => $isActive ? now() : $config->start_date,
            'end_date' => ! $isActive ? now() : null,
        ]);

        if ($isActive) {
            // Reset all panchayats for a new election cycle
            Panchayat::query()->update([
                'is_result_published' => false,
                'was_published' => false,
            ]);

            // Clear all cast votes from previous election
            \App\Models\Vote::truncate();

            // Reset candidate vote counts
            \App\Models\Candidate::query()->update(['votes_count' => 0]);

            // Reset voter voting status so they can vote again
            \App\Models\Voter::query()->update(['has_voted' => false]);
        }

        $status = $isActive ? 'Started' : 'Stopped';

        return back()->with('success', "Election {$status} successfully.");
    }

    public function approveCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);

        // Generate a unique Candidate ID if not already set
        if (! $candidate->candidate_id) {
            $candidate->candidate_id = 'CAND-'.strtoupper(Str::random(6));
            // Ensure uniqueness
            while (Candidate::where('candidate_id', $candidate->candidate_id)->exists()) {
                $candidate->candidate_id = 'CAND-'.strtoupper(Str::random(6));
            }
        }

        $candidate->status = 'approved';
        $candidate->save();

        // Update the User's role to 'candidate' and mark as verified
        $user = $candidate->user;
        $user->update([
            'role' => 'candidate',
            'is_verified' => true,
        ]);

        Log::info('Notification: Candidate Approved', [
            'candidate_name' => $user->name,
            'candidate_id' => $candidate->candidate_id,
            'email' => $user->email,
            'panchayat' => $user->panchayat->name ?? 'N/A',
        ]);

        // Send Email to candidate
        try {
            Mail::to($user->email)->send(new CandidateApprovedMail(
                $user->name,
                $candidate->candidate_id,
                $user->panchayat->name ?? 'N/A'
            ));
        } catch (\Exception $e) {
            Log::error("Failed to send approval email to candidate '{$user->name}': ".$e->getMessage());
            // Optionally, we could continue since the approval itself succeeded
        }

        return back()->with('success', "Candidate '{$user->name}' approved! Candidate ID: {$candidate->candidate_id}");
    }

    public function rejectCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->update(['status' => 'rejected']);

        return back()->with('success', 'Candidate rejected.');
    }

    public function votersList(Request $request)
    {
        $panchayats = Panchayat::with(['users' => function ($q) {
            $q->whereHas('voter')->with('voter');
        }])->get();

        return view('admin.voters', compact('panchayats'));
    }

    public function removeCandidate($id)
    {
        $candidate = Candidate::findOrFail($id);
        $user = $candidate->user;

        // If user has NO voter profile: Delete User (cascades to Candidate)
        if (! $user->voter) {
            $user->delete();

            return back()->with('success', "Candidate '{$user->name}' and their account removed.");
        }

        // If user HAS voter profile: Delete only Candidate record
        $candidate->delete();

        // Also reset user role to voter if it was candidate
        if ($user->role === 'candidate') {
            $user->update(['role' => 'voter']);
        }

        return back()->with('success', "Candidate profile of '{$user->name}' removed. User account preserved for Voter role.");
    }

    public function deleteAllVoters($panchayatId)
    {
        $users = User::where('panchayat_id', $panchayatId)->whereHas('voter')->get();

        foreach ($users as $user) {
            // Delete the associated Voter profile
            if ($user->voter) {
                // Remove captured photo if exists
                if ($user->voter->captured_photo && \Illuminate\Support\Facades\Storage::exists('public/'.$user->voter->captured_photo)) {
                    \Illuminate\Support\Facades\Storage::delete('public/'.$user->voter->captured_photo);
                }
                $user->voter()->delete();
            }
            // Delete the user account if they only have the 'voter' role
            if ($user->role === 'voter') {
                $user->delete();
            }
        }

        return back()->with('success', 'All voters in the selected panchayat have been deleted successfully.');
    }
}
