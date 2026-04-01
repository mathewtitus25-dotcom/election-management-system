@extends('layouts.app')

@section('page_title', 'VoteDesk')
@section('page_subtitle', 'Register, verify, vote, and view results.')

@section('content')
@php
    $dashboardRoute = match (Auth::user()?->role) {
        'admin' => route('admin.dashboard'),
        'blo' => route('blo.dashboard'),
        'candidate' => route('candidate.dashboard'),
        'voter' => route('voter.dashboard'),
        default => route('login'),
    };
@endphp

<div class="page-shell">
    <section class="page-hero landing-hero" data-reveal>
        <div class="d-grid gap-4 align-content-center">
            <div class="page-hero__eyebrow">Trusted Digital Election Operations</div>
            <div class="d-grid gap-3">
                <h1 class="landing-hero__title">Run the full election flow in one clear system.</h1>
                <p class="landing-hero__copy">
                    Registration, BLO approval, secure voting, and result tracking all stay in one place.
                </p>
            </div>

            <div class="hero-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Login to Continue
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-person-plus"></i>
                        Register as Voter
                    </a>
                    <a href="{{ route('candidate.register') }}" class="btn btn-light btn-lg">
                        <i class="bi bi-person-badge"></i>
                        Apply as Candidate
                    </a>
                @else
                    <a href="{{ $dashboardRoute }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-speedometer2"></i>
                        Open My Dashboard
                    </a>
                    <a href="{{ route('results') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-bar-chart-line"></i>
                        View Results
                    </a>
                @endguest
            </div>

            <div class="hero-proof">
                <span class="info-chip"><i class="bi bi-shield-check"></i> Verified one-person-one-vote flow</span>
                <span class="info-chip"><i class="bi bi-diagram-3"></i> Separate experiences for admins, BLOs, candidates, and voters</span>
                <span class="info-chip"><i class="bi bi-graph-up-arrow"></i> Live and published result visibility</span>
            </div>
        </div>

        <div class="surface-card landing-hero__panel">
            <div class="landing-hero__panel-card">
                <p class="muted-label mb-2">Why it works</p>
                <h2 class="landing-hero__panel-title">Cleaner actions, less noise</h2>
                <p class="landing-hero__panel-copy">Important tasks stay visible without crowding the screen.</p>
            </div>
            <div class="landing-hero__panel-card">
                <p class="muted-label mb-2">Core strengths</p>
                <div class="candidate-list">
                    <div class="candidate-list__item">
                        <span class="icon-badge"><i class="bi bi-person-vcard"></i></span>
                        <div>
                            <strong class="d-block">Safer onboarding</strong>
                            <span class="helper-copy">Clearer login and registration.</span>
                        </div>
                    </div>
                    <div class="candidate-list__item">
                        <span class="icon-badge"><i class="bi bi-camera-video"></i></span>
                        <div>
                            <strong class="d-block">Identity-aware voting</strong>
                            <span class="helper-copy">Live verification before vote submission.</span>
                        </div>
                    </div>
                    <div class="candidate-list__item">
                        <span class="icon-badge"><i class="bi bi-broadcast"></i></span>
                        <div>
                            <strong class="d-block">Transparent reporting</strong>
                            <span class="helper-copy">Faster charts and result pages.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="stat-grid">
        <x-ui.stat-card
            label="Core Promise"
            value="One verified voter, one secure vote"
            icon="bi-fingerprint"
            meta="Verification and voting stay connected."
        />
        <x-ui.stat-card
            label="Operator View"
            value="Role-aware control rooms"
            icon="bi-layout-text-sidebar-reverse"
            tone="accent"
            meta="Each role gets its own workspace."
        />
        <x-ui.stat-card
            label="Public Trust"
            value="Live to certified results"
            icon="bi-bar-chart"
            tone="success"
            meta="Live and final results are clearly separated."
        />
    </section>

    <section class="feature-grid">
        <article class="feature-card interactive-card" data-reveal data-reveal-delay="60">
            <span class="feature-card__icon"><i class="bi bi-person-check"></i></span>
            <div>
                <h2 class="feature-card__title">Faster onboarding with less confusion</h2>
                <p class="feature-card__copy">Registration and login are easier to scan.</p>
            </div>
        </article>
        <article class="feature-card interactive-card" data-reveal data-reveal-delay="120">
            <span class="feature-card__icon"><i class="bi bi-grid-1x2"></i></span>
            <div>
                <h2 class="feature-card__title">Cleaner hierarchy on every dashboard</h2>
                <p class="feature-card__copy">Important metrics and actions appear first.</p>
            </div>
        </article>
        <article class="feature-card interactive-card" data-reveal data-reveal-delay="180">
            <span class="feature-card__icon"><i class="bi bi-lightning-charge"></i></span>
            <div>
                <h2 class="feature-card__title">Less wasted rendering work</h2>
                <p class="feature-card__copy">Shared assets and lazy charts keep the UI lighter.</p>
            </div>
        </article>
        <article class="feature-card interactive-card" data-reveal data-reveal-delay="240">
            <span class="feature-card__icon"><i class="bi bi-universal-access"></i></span>
            <div>
                <h2 class="feature-card__title">Improved accessibility signals</h2>
                <p class="feature-card__copy">Better contrast, focus states, and navigation.</p>
            </div>
        </article>
    </section>

    <section class="process-grid" data-reveal>
        <article class="process-step interactive-card" data-reveal>
            <span class="step-marker">1</span>
            <div>
                <h2 class="process-step__title">Register</h2>
                <p class="process-step__copy">Simple forms for voters and candidates.</p>
            </div>
        </article>
        <article class="process-step interactive-card" data-reveal data-reveal-delay="90">
            <span class="step-marker">2</span>
            <div>
                <h2 class="process-step__title">Verify</h2>
                <p class="process-step__copy">BLOs and admins review from one place.</p>
            </div>
        </article>
        <article class="process-step interactive-card" data-reveal data-reveal-delay="180">
            <span class="step-marker">3</span>
            <div>
                <h2 class="process-step__title">Vote and Publish</h2>
                <p class="process-step__copy">Verified voting and clear results.</p>
            </div>
        </article>
    </section>
</div>
@endsection
