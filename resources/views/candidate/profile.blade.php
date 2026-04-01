@extends('layouts.app')

@section('page_title', 'Candidate')
@section('page_subtitle', 'A cleaner, more focused candidate profile that supports quick review before a voter returns to the ballot.')

@section('content')
<div class="page-shell">
    <section class="surface-card surface-card--padded" data-reveal>
        <div class="split-grid align-items-center">
            <div class="d-flex align-items-center gap-4">
                <span class="avatar avatar--lg">
                    @if($candidate->photo)
                        <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->user->name }}" width="90" height="90" loading="lazy">
                    @else
                        {{ strtoupper(substr($candidate->user->name, 0, 1)) }}
                    @endif
                </span>
                <div>
                    <div class="muted-label">Candidate overview</div>
                    <h1 class="mb-1">{{ $candidate->user->name }}</h1>
                    <p class="helper-copy mb-0">
                        <i class="bi bi-geo-alt-fill"></i>
                        Panchayat: {{ $candidate->user->panchayat->name }}
                    </p>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-3 justify-content-lg-end">
                <span class="info-chip">ID: {{ $candidate->candidate_id ?? 'Pending' }}</span>
                <span class="info-chip">Age: {{ \Carbon\Carbon::parse($candidate->dob)->age }} yrs</span>
                <span class="info-chip">Gender: {{ ucfirst($candidate->gender) }}</span>
                <span class="info-chip">Qualification: {{ $candidate->qualification }}</span>
            </div>
        </div>
    </section>

    <section class="surface-card surface-card--padded" data-reveal data-reveal-delay="80">
        <div class="muted-label">Manifesto</div>
        <h2 class="mb-3">Campaign statement</h2>
        <div class="manifesto-panel">
            @if($candidate->manifesto)
                {{ trim($candidate->manifesto) }}
            @else
                <em>No manifesto provided by this candidate.</em>
            @endif
        </div>
    </section>

    <div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left"></i>
            Go Back
        </a>
    </div>
</div>
@endsection
