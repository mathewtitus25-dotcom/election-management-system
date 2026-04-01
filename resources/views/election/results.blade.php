@extends('layouts.app')

@section('page_title', 'Results')
@section('page_subtitle', 'View live or final results by panchayat.')

@section('content')
<div class="page-shell">
    <x-ui.page-header eyebrow="Public Reporting" title="{{ $isActive ? 'Real-time election tracker' : 'Official election outcome' }}" subtitle="{{ $isActive ? 'Live standings by panchayat.' : 'Final published standings by panchayat.' }}">
        <span class="status-pill {{ $isActive ? 'status-pill--primary' : 'status-pill--success' }}">
            <i class="bi {{ $isActive ? 'bi-broadcast-pin' : 'bi-check2-circle' }}"></i>
            {{ $isActive ? 'Live election tracker' : 'Final published results' }}
        </span>
    </x-ui.page-header>

    <section class="results-grid">
        @foreach($results as $panchayat)
            @php
                $totalVotes = $panchayat->candidates->sum('votes_count');
                $labels = [];
                $data = [];
                $colors = ['rgba(20, 89, 217, 0.82)', 'rgba(15, 157, 143, 0.78)', 'rgba(245, 159, 0, 0.78)', 'rgba(192, 54, 76, 0.76)', 'rgba(88, 112, 134, 0.76)'];

                foreach ($panchayat->candidates as $index => $candidate) {
                    $labels[] = $candidate->user->name;
                    $data[] = $candidate->votes_count;
                }

                $backgroundColors = [];
                foreach ($data as $index => $unused) {
                    $backgroundColors[] = $colors[$index % count($colors)];
                }

                $chartConfig = [
                    'type' => 'bar',
                    'data' => [
                        'labels' => $labels,
                        'datasets' => [[
                            'data' => $data,
                            'backgroundColor' => $backgroundColors,
                            'borderRadius' => 10,
                            'borderSkipped' => false,
                        ]],
                    ],
                    'options' => [
                        'responsive' => true,
                        'maintainAspectRatio' => false,
                        'indexAxis' => 'y',
                        'plugins' => [
                            'legend' => ['display' => false],
                        ],
                        'scales' => [
                            'x' => [
                                'beginAtZero' => true,
                                'grid' => ['color' => 'rgba(88, 112, 134, 0.14)'],
                                'ticks' => ['precision' => 0],
                            ],
                            'y' => [
                                'grid' => ['display' => false],
                            ],
                        ],
                    ],
                ];
            @endphp

            <article class="surface-card results-card content-auto interactive-card" data-reveal>
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                    <div>
                        <div class="muted-label">{{ $totalVotes }} total votes cast</div>
                        <h2 class="mb-1">{{ $panchayat->name }}</h2>
                        <p class="helper-copy mb-0">{{ $panchayat->candidates->count() }} candidate{{ $panchayat->candidates->count() === 1 ? '' : 's' }} listed.</p>
                    </div>
                    @if($panchayat->candidates->count() > 0)
                        <span class="status-pill {{ $isActive ? 'status-pill--primary' : 'status-pill--success' }}">
                            <i class="bi {{ $isActive ? 'bi-lightning-charge' : 'bi-trophy' }}"></i>
                            {{ $panchayat->candidates->first()->user->name }}
                        </span>
                    @endif
                </div>

                @if($panchayat->candidates->count() > 0)
                    <div class="chart-shell px-0 pb-0">
                        <canvas data-chart-config='@json($chartConfig)'></canvas>
                    </div>

                    <div>
                        <div class="muted-label mb-3">Standings</div>
                        <div class="leaderboard">
                            @foreach($panchayat->candidates as $index => $candidate)
                                @php
                                    $percentage = $totalVotes > 0 ? round(($candidate->votes_count / $totalVotes) * 100, 1) : 0;
                                    $isLeader = $index === 0 && $candidate->votes_count > 0;
                                @endphp
                                <div class="leaderboard__item {{ $isLeader ? 'is-leading' : '' }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="avatar avatar--sm">
                                            @if($candidate->photo)
                                                <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->user->name }}" width="44" height="44" loading="lazy">
                                            @else
                                                {{ strtoupper(substr($candidate->user->name, 0, 1)) }}
                                            @endif
                                        </span>
                                        <div>
                                            <strong class="d-block">{{ $candidate->user->name }}</strong>
                                            <span class="helper-copy">{{ $percentage }}% of vote</span>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <strong class="d-block fs-5">{{ $candidate->votes_count }}</strong>
                                        <span class="helper-copy">votes</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="status-banner">
                        <i class="bi bi-inbox text-muted"></i>
                        <div>
                            <strong class="d-block mb-1">No approved candidates found</strong>
                            <span class="helper-copy">This panchayat currently has no candidate data to visualize.</span>
                        </div>
                    </div>
                @endif
            </article>
        @endforeach
    </section>
</div>
@endsection
