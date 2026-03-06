@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm bg-white">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Candidate Dashboard</h2>
                    <p class="text-muted mb-0">Running for Office: <span class="fw-bold text-primary">{{ $panchayat->name }}</span></p>
                </div>
                <div class="text-end d-flex align-items-center gap-2">
                    <!-- Removed Switch to Voter View button as requested -->
                    <span class="badge bg-success bg-opacity-10 text-success border border-success p-2">
                        <i class="bi bi-patch-check-fill me-1"></i>Approved Candidate
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Turnout Stats -->
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm text-center p-4">
            <h6 class="text-muted text-uppercase small ls-1 mb-3">Total Panchayat Voters</h6>
            <h2 class="display-5 fw-bold text-dark mb-0">{{ $totalVoters }}</h2>
            <div class="mt-2 small text-muted">Verified residents</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm text-center p-4">
            <h6 class="text-muted text-uppercase small ls-1 mb-3">Votes Cast So Far</h6>
            <h2 class="display-5 fw-bold text-primary mb-0">{{ $votedCount }}</h2>
            <div class="mt-2 small text-muted">Live turnout progress</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100 border-0 shadow-sm text-center p-4">
            <h6 class="text-muted text-uppercase small ls-1 mb-3">Turnout Percentage</h6>
            <h2 class="display-5 fw-bold text-success mb-0">{{ $turnoutPercent }}%</h2>
            <div class="mt-2">
                <div class="progress" style="height: 6px;">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $turnoutPercent }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Manifesto Preview -->
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold py-3">
                <i class="bi bi-file-text-fill text-primary me-2"></i>My Manifesto
            </div>
            <div class="card-body">
                <p class="text-muted fst-italic">"{{ $candidate->manifesto }}"</p>
                <hr>
                <div class="small">
                    <strong>Status:</strong> Approved by Admin<br>
                    <strong>Applied on:</strong> {{ $candidate->created_at->format('M d, Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Opponents Section -->
    <div class="col-md-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people-fill text-primary me-2"></i>My Opponents</span>
                <span class="badge bg-light text-dark border">{{ $opponents->count() }} Competitors</span>
            </div>
            <div class="card-body p-0">
                @if($opponents->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($opponents as $opponent)
                            <div class="list-group-item p-4">
                                <div class="d-flex align-items-center mb-2">
                                    @if($opponent->photo)
                                        <div class="rounded-circle me-3 overflow-hidden shadow-sm" style="width: 40px; height: 40px;">
                                            <img src="{{ asset('storage/' . $opponent->photo) }}" alt="Opponent Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="bg-light rounded-circle p-2 me-3 text-secondary" style="width: 40px; height: 40px; display:flex; align-items:center; justify-content:center;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    @endif
                                    <h6 class="mb-0 fw-bold">{{ $opponent->user->name }}</h6>
                                </div>
                                <div class="small text-muted ps-5">
                                    "{{ Str::limit($opponent->manifesto, 150) }}"
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-award fs-1 text-muted opacity-25 d-block mb-3"></i>
                        <p class="text-muted">You are the only approved candidate in this Panchayat so far.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Voter List Section -->
    <div class="col-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-people-fill text-primary me-2"></i>Panchayat Voter List</span>
                <span class="badge bg-light text-dark border">{{ $votersList->count() }} Total Voters</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Voter Name</th>
                                <th class="pe-4">Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($votersList as $voter)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light rounded-circle p-2 me-3 text-secondary" style="width: 35px; height: 35px; display:flex; align-items:center; justify-content:center; font-size: 0.8rem;">
                                                <i class="bi bi-person-fill"></i>
                                            </div>
                                            <span class="fw-bold">{{ $voter->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="pe-4">{{ $voter->user->email }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">No approved voters found in this Panchayat.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
