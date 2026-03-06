<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Candidate;
use App\Models\Vote;
use App\Models\ElectionConfig;

class VoterController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $voter = $user->voter;
        $panchayat = $user->panchayat;
        
        // Check if Election is Active
        $electionConfig = ElectionConfig::first();
        $isElectionActive = $electionConfig ? $electionConfig->is_active : false;

        // Get Candidates for this Panchayat
        $candidates = Candidate::whereHas('user', function($q) use ($user) {
            $q->where('panchayat_id', $user->panchayat_id);
        })->where('status', 'approved')->with('user')->get();

        // ✅ Election Results (if published)
        $winningCandidate = null;
        if (!$isElectionActive && $panchayat->is_result_published) {
            $candidatesByVotes = Candidate::whereHas('user', function($q) use ($user) {
                $q->where('panchayat_id', $user->panchayat_id);
            })->where('status', 'approved')->with('user')->orderBy('votes_count', 'desc')->get();
            
            $winningCandidate = $candidatesByVotes->first();
        }

        return view('voter.dashboard', compact('user', 'voter', 'panchayat', 'isElectionActive', 'candidates', 'winningCandidate'));
    }

    public function vote(Request $request)
    {
        $request->validate([
            'candidate_id'   => 'required|exists:candidates,id',
            'captured_photo' => 'required|string', // Base64 image
        ]);

        // ✅ Server-side: Verify election is active
        $electionConfig = ElectionConfig::first();
        if (!$electionConfig || !$electionConfig->is_active) {
            abort(403, 'The election is not currently active.');
        }

        $user = Auth::user();
        $voter = $user->voter;

        if (!$voter || $voter->status !== 'approved') {
            return back()->with('error', 'You must be an approved voter to vote.');
        }

        if ($voter->has_voted) {
            return back()->with('error', 'You have already voted.');
        }

        // Verify Candidate belongs to same Panchayat
        $candidate = Candidate::findOrFail($request->candidate_id);
        if ($candidate->user->panchayat_id !== $user->panchayat_id) {
            return back()->with('error', 'Invalid candidate selection.');
        }

        // ✅ Process and Save Captured Photo
        $photoData = $request->captured_photo;
        $photoName = 'voter_' . $user->id . '_' . time() . '.jpg';
        
        // Remove the "data:image/jpeg;base64," part
        $photoData = str_replace('data:image/jpeg;base64,', '', $photoData);
        $photoData = str_replace(' ', '+', $photoData);
        
        if (empty($photoData) || strlen($photoData) < 100) {
            return back()->with('error', 'Error capturing photo. Please try again.');
        }

        $photoImage = base64_decode($photoData);

        if (!$photoImage) {
            return back()->with('error', 'Invalid photo data received.');
        }

        $photoPath = 'voter_photos/' . $photoName;
        \Illuminate\Support\Facades\Storage::disk('public')->put($photoPath, $photoImage);

        // ✅ Wrapped in DB transaction to ensure integrity
        DB::transaction(function () use ($user, $voter, $candidate, $photoPath) {
            // 1. Create ANONYMOUS Vote (no voter_id)
            Vote::create([
                'candidate_id' => $candidate->id,
                'panchayat_id' => $user->panchayat_id,
            ]);

            // 2. Mark Voter as voted and store photo reference
            $voter->update([
                'has_voted'      => true,
                'captured_photo' => $photoPath,
            ]);

            // 3. Increment Candidate vote count
            $candidate->increment('votes_count');
        });

        return redirect()->route('voter.dashboard')->with('success', 'Your vote has been cast successfully! Your identity remains private.');
    }
}
