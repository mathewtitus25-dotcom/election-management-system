@extends('layouts.app')

@section('page_title', 'Campaign')
@section('page_subtitle', 'Track turnout, opponents, and voters.')

@section('content')
<div class="page-shell">
    <x-ui.page-header eyebrow="Campaign Workspace" title="Candidate dashboard" subtitle="Your key campaign numbers and lists.">
        <span class="info-chip">
            <i class="bi bi-geo-alt"></i>
            {{ $panchayat->name }}
        </span>
        <span class="status-pill status-pill--success">
            <i class="bi bi-patch-check"></i>
            Approved candidate
        </span>
    </x-ui.page-header>

    <section class="stat-grid">
        <x-ui.stat-card label="Verified Voters" value="{{ $totalVoters }}" icon="bi-people" meta="Approved residents in your panchayat." />
        <x-ui.stat-card label="Votes Cast" value="{{ $votedCount }}" icon="bi-check2-square" tone="accent" meta="Current turnout count across the panchayat." />
        <x-ui.stat-card label="Turnout" value="{{ $turnoutPercent }}%" icon="bi-graph-up-arrow" tone="success" meta="Your live turnout benchmark.">
            <div class="progress" style="height: 8px;">
                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $turnoutPercent }}%"></div>
            </div>
        </x-ui.stat-card>
    </section>

    <div class="split-grid">
        <section class="surface-card surface-card--padded content-auto" data-reveal>
            <div class="muted-label">Manifesto</div>
            <h2 class="mb-2">What voters will read about you</h2>
            <p class="helper-copy">Your campaign message.</p>
            <div class="candidate-list mt-4">
                <div class="candidate-list__item align-items-start">
                    <span class="icon-badge"><i class="bi bi-file-earmark-text"></i></span>
                    <div>
                        <p class="mb-2 fst-italic">"{{ $candidate->manifesto }}"</p>
                        <span class="helper-copy">Applied on {{ $candidate->created_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="surface-card surface-card--padded content-auto" data-reveal data-reveal-delay="80">
            <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <div class="muted-label">Opponents</div>
                    <h2 class="mb-1">Other approved candidates</h2>
                </div>
                <span class="status-pill status-pill--primary">
                    <i class="bi bi-people-fill"></i>
                    {{ $opponents->count() }} opponent{{ $opponents->count() === 1 ? '' : 's' }}
                </span>
            </div>

            @if($opponents->count() > 0)
                <div class="candidate-list">
                    @foreach($opponents as $opponent)
                        <div class="candidate-list__item">
                            <span class="avatar avatar--sm">
                                @if($opponent->photo)
                                    <img src="{{ Storage::url($opponent->photo) }}" alt="{{ $opponent->user->name }}" width="44" height="44" loading="lazy">
                                @else
                                    {{ strtoupper(substr($opponent->user->name, 0, 1)) }}
                                @endif
                            </span>
                            <div>
                                <strong class="d-block">{{ $opponent->user->name }}</strong>
                                <span class="helper-copy">"{{ Str::limit($opponent->manifesto, 150) }}"</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="status-banner">
                    <i class="bi bi-award text-muted"></i>
                    <div>
                        <strong class="d-block mb-1">No approved opponents yet</strong>
                        <span class="helper-copy">You are currently the only approved candidate in this panchayat.</span>
                    </div>
                </div>
            @endif
        </section>
    </div>

    <section class="surface-card content-auto" data-reveal>
        <div class="panel-card__header">
            <div>
                <h2 class="panel-card__title">Panchayat voter list</h2>
                <p class="panel-card__subtitle">Approved voters in your panchayat.</p>
            </div>
            <span class="info-chip">
                <i class="bi bi-person-lines-fill"></i>
                {{ $votersList->count() }} total voter{{ $votersList->count() === 1 ? '' : 's' }}
            </span>
        </div>

        <div class="p-4 pt-3">
            <div class="table-shell">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Voter</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($votersList as $voter)
                                <tr>
                                    <td data-label="Voter">
                                        <div class="d-flex align-items-center gap-3">
                                            <span class="avatar avatar--sm">{{ strtoupper(substr($voter->user->name, 0, 1)) }}</span>
                                            <strong>{{ $voter->user->name }}</strong>
                                        </div>
                                    </td>
                                    <td data-label="Email">{{ $voter->user->email }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-5 text-muted">No approved voters found in this panchayat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
