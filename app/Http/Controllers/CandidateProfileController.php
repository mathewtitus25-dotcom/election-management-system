<?php

namespace App\Http\Controllers;

use App\Models\Candidate;

class CandidateProfileController extends Controller
{
    public function show($id)
    {
        $candidate = Candidate::with('user.panchayat')->findOrFail($id);

        if ($candidate->status !== 'approved') {
            abort(404, 'Candidate profile not found or not approved.');
        }

        return view('candidate.profile', compact('candidate'));
    }
}
