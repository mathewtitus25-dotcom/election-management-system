<?php

namespace App\Http\Controllers;

use App\Models\Voter;
use Illuminate\Support\Facades\Auth;

class BLOController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        if ($user->role !== 'blo') {
            abort(403);
        }

        $panchayat = $user->panchayat;

        // Get Pending Voters for this Panchyat
        $pendingVoters = Voter::whereHas('user', function ($q) use ($user) {
            $q->where('panchayat_id', $user->panchayat_id);
        })->where('status', 'pending')->with('user')->get();

        // Get Approved Voters
        $approvedVoters = Voter::whereHas('user', function ($q) use ($user) {
            $q->where('panchayat_id', $user->panchayat_id);
        })->where('status', 'approved')->with('user')->get();

        $electionConfig = \App\Models\ElectionConfig::first();
        $isElectionActive = $electionConfig ? $electionConfig->is_active : false;

        return view('blo.dashboard', compact('user', 'panchayat', 'pendingVoters', 'approvedVoters', 'isElectionActive'));
    }

    public function approveVoter($id)
    {
        $voter = Voter::findOrFail($id);

        // Security Check: BLO can only approve voters in their panchayat
        if ($voter->user->panchayat_id !== Auth::user()->panchayat_id) {
            abort(403);
        }

        $voter->update(['status' => 'approved']);

        return back()->with('success', 'Voter approved successfully.');
    }

    public function rejectVoter($id)
    {
        $voter = Voter::findOrFail($id);

        // Security Check
        if ($voter->user->panchayat_id !== Auth::user()->panchayat_id) {
            abort(403);
        }

        $user = $voter->user;

        // If user has NO candidate profile: Delete User (cascades to Voter)
        if (! $user->candidate) {
            $user->delete();

            return back()->with('success', 'Voter application rejected and account removed to free up email.');
        }

        // If user HAS candidate profile: Delete only Voter record
        $voter->delete();

        return back()->with('success', 'Voter profile rejected and removed. User account preserved for Candidate role.');
    }

    public function publishResult()
    {
        $user = Auth::user();
        $panchayat = $user->panchayat;

        $electionConfig = \App\Models\ElectionConfig::first();
        if ($electionConfig && $electionConfig->is_active) {
            return back()->with('error', 'Cannot publish results while election is still active.');
        }

        $panchayat->update([
            'is_result_published' => true,
            'was_published' => true,
        ]);

        return back()->with('success', 'Election results for '.$panchayat->name.' published successfully.');
    }

    public function unpublishResult()
    {
        $user = Auth::user();
        $panchayat = $user->panchayat;

        $panchayat->update(['is_result_published' => false]);

        return back()->with('success', 'Election results for '.$panchayat->name.' unpublished (hidden from public).');
    }
}
