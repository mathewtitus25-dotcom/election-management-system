<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Panchayat;
use App\Models\Candidate;

class ElectionController extends Controller
{
    public function index()
    {
        $isActive = \App\Models\ElectionConfig::first()->is_active ?? false;

        $results = Panchayat::with(['candidates' => function($q) {
            $q->where('status', 'approved')->with('user')->orderBy('votes_count', 'desc');
        }])->get();

        return view('election.results', compact('results', 'isActive'));
    }

}
