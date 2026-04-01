<?php

namespace App\Http\Controllers;

use App\Models\Panchayat;
use Illuminate\Support\Facades\Cache;

class ElectionController extends Controller
{
    public function index()
    {
        $isActive = \App\Models\ElectionConfig::first()->is_active ?? false;

        $results = Cache::remember('election.results', 60, function () {
            return Panchayat::with(['candidates' => function ($q) {
                $q->where('status', 'approved')->with('user')->orderBy('votes_count', 'desc');
            }])->get();
        });

        return view('election.results', compact('results', 'isActive'));
    }
}
