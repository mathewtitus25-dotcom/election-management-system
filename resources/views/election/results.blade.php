@extends('layouts.app')

@section('content')
<div class="result-page-container pb-5">
    
    <!-- Hero Section -->
    <div class="text-center mb-5 py-4 position-relative">
        <h1 class="display-4 fw-bold text-gradient mb-3">Election Results {{ date('Y') }}</h1>
        @if($isActive)
            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill bg-white shadow-sm border border-danger border-opacity-25 pulse-container">
                <span class="badge bg-danger rounded-circle p-2 pulse-dot"></span>
                <span class="text-danger fw-bold text-uppercase tracking-wider">Live & Counting</span>
            </div>
            <p class="text-muted mt-3">Votes are coming in! These are preliminary results.</p>
        @else
            <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill bg-white shadow-sm border border-success border-opacity-25">
                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                <span class="text-success fw-bold text-uppercase tracking-wider">Official Final Results</span>
            </div>
            <p class="text-muted mt-3">The election has successfully concluded. Congratulations to all winners!</p>
        @endif
    </div>

    <div class="row g-4 justify-content-center">
        @forelse($results as $panchayat)
            @php
                $totalVotes = $panchayat->candidates->sum('votes_count');
                $winner = $panchayat->candidates->first();
                $hasCandidates = $panchayat->candidates->count() > 0;
            @endphp
            
            <div class="col-lg-4 col-md-6">
                <!-- Panchayat Card -->
                <div class="card border-0 shadow-lg h-100 overflow-hidden result-card">
                    <div class="card-header border-0 py-3 d-flex justify-content-between align-items-center {{ $hasCandidates && $totalVotes > 0 && !$isActive ? 'bg-gradient-custom-blue text-white' : 'bg-light text-dark' }}">
                        <h4 class="mb-0 fw-bold">{{ $panchayat->name }}</h4>
                        @if($isActive || $panchayat->is_result_published)
                            <span class="badge bg-white text-primary bg-opacity-90 backdrop-blur shadow-sm">
                                <i class="bi bi-people-fill me-1"></i> {{ $totalVotes }} Votes
                            </span>
                        @endif
                    </div>

                    <div class="card-body p-0">
                        @if(!$isActive && !$panchayat->is_result_published)
                            <!-- Results Hidden State -->
                            <div class="text-center py-5 px-4">
                                <div class="mb-3">
                                    <i class="bi bi-hourglass-split fs-1 text-warning"></i>
                                </div>
                                <h5 class="fw-bold">Results Awaiting Publication</h5>
                                <p class="text-muted small">The results for this Panchayat have not been published by the Election Officer yet.</p>
                            </div>
                        @elseif($hasCandidates)
                            <!-- Winner Spotlight (Only if final or leading) -->
                            @if($totalVotes > 0 && $winner)
                            <div class="winner-section text-center p-4 bg-light bg-opacity-50 border-bottom">
                                <div class="position-relative d-inline-block mb-3">
                                    <div class="winner-avatar rounded-circle shadow p-1 bg-white">
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fs-2 fw-bold" style="width: 80px; height: 80px;">
                                            {{ substr($winner->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                </div>
                                <h3 class="fw-bold mb-1">{{ $winner->user->name }}</h3>
                                <p class="text-muted small mb-2">{{ $winner->candidate_id }}</p>
                                <h2 class="display-6 fw-bold text-primary mb-0">{{ $winner->votes_count }} <small class="fs-6 text-muted">Votes</small></h2>
                                 @if(!$isActive)
                                    <span class="badge bg-success mt-2 px-3 py-2 rounded-pill">ELECTED WINNER</span>
                                @else
                                    <span class="badge bg-info text-dark mt-2 px-3 py-2 rounded-pill">CURRENTLY LEADING</span>
                                @endif
                            </div>
                            @endif

                            <!-- Candidates List -->
                            <div class="candidates-list p-3">
                                <h6 class="text-muted text-uppercase small fw-bold mb-3 px-2">Vote Breakdown</h6>
                                <div class="vstack gap-3">
                                    @foreach($panchayat->candidates as $candidate)
                                        @php
                                            $percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                                            $isWinner = $loop->first && $totalVotes > 0;
                                        @endphp
                                        <div class="candidate-row p-2 rounded {{ $isWinner ? 'bg-indigo-light' : '' }}">
                                            <div class="d-flex align-items-center justify-content-between mb-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="fw-bold text-dark">{{ $candidate->user->name }}</span>
                                                    @if($isWinner && !$isActive)
                                                        <i class="bi bi-check-circle-fill text-success small"></i>
                                                    @endif
                                                </div>
                                                <div class="text-end">
                                                    <span class="fw-bold">{{ $candidate->votes_count }}</span> <span class="text-muted small">({{ $percentage }}%)</span>
                                                </div>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar {{ $isWinner ? 'bg-primary' : 'bg-secondary' }}" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%" 
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                                <p class="text-muted mt-2">No candidates contested.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="empty-state">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" alt="No Results" class="img-fluid opacity-50 mb-3" style="max-height: 200px; filter: grayscale(1);">
                    <h3>No Results Available Yet</h3>
                    <p class="text-muted">Once elections are set up, results will appear here.</p>
                </div>
            </div>
        @endforelse
    </div>
</div>

<style>
    .bg-gradient-custom-blue {
        background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); /* Example cyan-blue professional gradient */
    }
    .text-gradient {
        background: linear-gradient(to right, #2c3e50, #4ca1af);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    .result-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .result-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .bg-indigo-light {
        background-color: #f0f7ff;
    }
    
    .pulse-dot {
        animation: pulse-animation 1.5s infinite;
    }
    @keyframes pulse-animation {
        0% { transform: scale(0.95); opacity: 1; box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
        70% { transform: scale(1); opacity: 1; box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { transform: scale(0.95); opacity: 1; box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
</style>

{{-- ✅ Chart.js Result Charts (only for published panchayats) --}}
@php
    $publishedResults = $results->filter(fn($p) => !$isActive && $p->is_result_published && $p->candidates->count() > 0);
@endphp

@if($publishedResults->count() > 0)
<div class="mt-5">
    <h3 class="fw-bold mb-4 text-center">
        <i class="bi bi-bar-chart-line-fill me-2 text-primary"></i>Vote Distribution Charts
    </h3>
    <div class="row g-4 justify-content-center">
        @foreach($publishedResults as $panchayat)
        <div class="col-lg-6">
            <div class="card shadow border-0 p-3">
                <h6 class="fw-bold text-center mb-3">{{ $panchayat->name }}</h6>
                <canvas id="chart-{{ $panchayat->id }}" height="180"></canvas>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartColors = [
        'rgba(0,114,255,0.85)',
        'rgba(40,167,69,0.85)',
        'rgba(255,193,7,0.85)',
        'rgba(220,53,69,0.85)',
        'rgba(111,66,193,0.85)',
        'rgba(23,162,184,0.85)',
    ];

    @foreach($publishedResults as $panchayat)
    (function() {
        const ctx = document.getElementById('chart-{{ $panchayat->id }}').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($panchayat->candidates as $candidate)
                        '{{ addslashes($candidate->user->name) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Votes',
                    data: [
                        @foreach($panchayat->candidates as $candidate)
                            {{ $candidate->votes_count }},
                        @endforeach
                    ],
                    backgroundColor: chartColors.slice(0, {{ $panchayat->candidates->count() }}),
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => ` ${ctx.raw} votes`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0 },
                        grid: { color: 'rgba(0,0,0,0.05)' }
                    },
                    x: { grid: { display: false } }
                }
            }
        });
    })();
    @endforeach
});
</script>
@endif

@endsection

