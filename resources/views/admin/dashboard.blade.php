@extends('layouts.app')

@section('content')
<style>
    :root {
        --admin-blue-dark: #1e3a8a;
        --admin-blue-main: #2563eb;
        --admin-blue-light: #eff6ff;
        --admin-blue-border: #3b82f6;
        --admin-bg: #f8fafc;
        --admin-card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
    }

    body {
        background-color: var(--admin-bg);
        color: #334155;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .admin-header-title {
        color: var(--admin-blue-dark);
        border-left: 5px solid var(--admin-blue-main);
        padding-left: 1rem;
    }

    .stat-card {
        background: white;
        border: none;
        border-top: 4px solid var(--admin-blue-border);
        border-radius: 8px;
        box-shadow: var(--admin-card-shadow);
        transition: transform 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-3px);
    }

    .stat-icon {
        color: var(--admin-blue-main);
        background: var(--admin-blue-light);
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }

    .stat-value {
        color: var(--admin-blue-dark);
        font-size: 1.75rem;
        font-weight: 800;
        line-height: 1;
    }

    .stat-label {
        color: #64748b;
        font-weight: 500;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .admin-card {
        border: none;
        border-radius: 12px;
        box-shadow: var(--admin-card-shadow);
        overflow: hidden;
    }

    .admin-card .card-header {
        background-color: white;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem;
        font-weight: 700;
        color: var(--admin-blue-dark);
    }

    .nav-pills-admin .nav-link {
        color: #64748b;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        border-radius: 8px;
        transition: all 0.2s ease;
    }

    .nav-pills-admin .nav-link.active {
        background-color: var(--admin-blue-main) !important;
        color: white !important;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.4);
    }

    .nav-pills-admin .nav-link:not(.active):hover {
        background-color: var(--admin-blue-light);
        color: var(--admin-blue-main);
    }

    .table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table td {
        padding: 1rem;
        vertical-align: middle;
        color: #334155;
    }

    .btn-admin-primary {
        background-color: var(--admin-blue-main);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.625rem 1.25rem;
        transition: all 0.2s;
    }

    .btn-admin-primary:hover {
        background-color: var(--admin-blue-dark);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .badge-admin-success {
        background-color: #dcfce7;
        color: #166534;
        font-weight: 600;
    }

    .badge-admin-danger {
        background-color: #fee2e2;
        color: #991b1b;
        font-weight: 600;
    }
</style>

<div class="row align-items-center mb-5">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="admin-header-title fw-bold">Admin Dashboard</h2>
            <p class="text-muted ms-3 mb-0">Institutional management of BLOs, Candidates, and Election Controls.</p>
        </div>
        <div>
            <a href="{{ route('admin.voters.index') }}" class="btn btn-admin-primary shadow-sm hover-up">
                <i class="bi bi-person-video2 me-2"></i> Voter Verification Logs
            </a>
        </div>
    </div>
</div>

<!-- ✅ Unified Blue Stats Grid (1 Mobile, 2 Tablet, 3 Desktop) -->
<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
    <!-- Panchayats -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon mb-3"><i class="bi bi-geo-alt-fill"></i></div>
                <div class="stat-label mb-1">Panchayats</div>
                <div class="stat-value">{{ $stats['total_panchayats'] }}</div>
            </div>
        </div>
    </div>
    <!-- Approved Voters -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon mb-3"><i class="bi bi-person-check-fill"></i></div>
                <div class="stat-label mb-1">Approved Voters</div>
                <div class="stat-value">{{ $stats['total_approved_voters'] }}</div>
            </div>
        </div>
    </div>
    <!-- Pending Voters -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon mb-3"><i class="bi bi-hourglass-split"></i></div>
                <div class="stat-label mb-1">Pending Voters</div>
                <div class="stat-value text-warning">{{ $stats['total_pending_voters'] }}</div>
            </div>
        </div>
    </div>
    <!-- Candidates -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon mb-3"><i class="bi bi-person-badge-fill"></i></div>
                <div class="stat-label mb-1">Candidates</div>
                <div class="stat-value">{{ $stats['total_approved_candidates'] }}</div>
            </div>
        </div>
    </div>
    <!-- Votes Cast -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon mb-3"><i class="bi bi-box-fill"></i></div>
                <div class="stat-label mb-1">Votes Cast</div>
                <div class="stat-value">{{ $stats['total_votes_cast'] }}</div>
            </div>
        </div>
    </div>
    <!-- BLO Officers -->
    <div class="col">
        <div class="card stat-card h-100">
            <div class="card-body">
                <div class="stat-icon mb-3"><i class="bi bi-shield-check"></i></div>
                <div class="stat-label mb-1">BLO Officers</div>
                <div class="stat-value">{{ $stats['total_blos'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <ul class="nav nav-pills nav-pills-admin mb-4 bg-white p-2 rounded shadow-sm gap-2" id="adminTab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active" id="blos-tab" data-bs-toggle="tab" data-bs-target="#blos" type="button">
                    <i class="bi bi-people-fill me-2"></i>BLO Management
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="candidates-tab" data-bs-toggle="tab" data-bs-target="#candidates" type="button">
                    <i class="bi bi-person-badge-fill me-2"></i>Candidate Applications
                    @if($pendingCandidates->count() > 0)
                        <span class="badge rounded-pill bg-danger ms-2">{{ $pendingCandidates->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" id="election-tab" data-bs-toggle="tab" data-bs-target="#election" type="button">
                    <i class="bi bi-gear-fill me-2"></i>Election Control
                </button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabContent">
            
            <!-- BLO Management Tab -->
            <div class="tab-pane fade show active" id="blos" role="tabpanel">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="card admin-card">
                            <div class="card-header"><i class="bi bi-plus-circle me-2"></i>Provision New BLO</div>
                            <div class="card-body p-4">
                                <form action="{{ route('admin.blo.create') }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Full Name</label>
                                        <input type="text" name="name" class="form-control form-control-lg fs-6 capitalize-input" placeholder="Registration Officer Name" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Official Email</label>
                                        <input type="email" name="email" class="form-control form-control-lg fs-6" placeholder="blo@election.gov.in" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Access Password</label>
                                        <input type="text" name="password" class="form-control form-control-lg fs-6" placeholder="Secure token" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">Assigned Jurisdiction</label>
                                        <select name="panchayat_id" class="form-select form-select-lg fs-6" required>
                                            <option value="" disabled selected>Select Panchayat</option>
                                            @foreach($panchayats as $p)
                                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-admin-primary w-100 py-3">
                                        Authorize Officer <i class="bi bi-check2-circle ms-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card admin-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-list-stars me-2"></i>Authorized Personnel</span>
                                <span class="badge bg-light text-dark border fw-normal">{{ $blos->count() }} Registered</span>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Officer Details</th>
                                                <th>Jurisdiction</th>
                                                <th>Authorization</th>
                                                <th class="text-end">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($blos as $blo)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold">{{ $blo->user->name }}</div>
                                                        <div class="small text-muted">{{ $blo->user->email }}</div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-light text-primary border">{{ $blo->user->panchayat->name ?? 'Unassigned' }}</span>
                                                    </td>
                                                    <td>
                                                        @if($blo->is_active)
                                                            <span class="badge badge-admin-success px-3 py-2 rounded-pill">Active</span>
                                                        @else
                                                            <span class="badge badge-admin-danger px-3 py-2 rounded-pill">Suspended</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <form action="{{ route('admin.blo.toggle', $blo->id) }}" method="POST">
                                                            @csrf
                                                            <button class="btn btn-sm {{ $blo->is_active ? 'btn-outline-danger' : 'btn-outline-success' }} px-3 fw-bold">
                                                                {{ $blo->is_active ? 'Suspend' : 'Reinstate' }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="text-center py-5 text-muted">No officers found in system database.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Candidates Tab -->
            <div class="tab-pane fade" id="candidates" role="tabpanel">
                <div class="card admin-card">
                    <div class="card-header bg-white">
                        <ul class="nav nav-tabs card-header-tabs" id="candidateSubTab" role="tablist">
                            <li class="nav-item">
                                <button class="nav-link active fw-bold" id="pending-candidates-tab" data-bs-toggle="tab" data-bs-target="#pending-candidates" type="button">
                                    Pending Review ({{ $pendingCandidates->count() }})
                                </button>
                            </li>
                            <li class="nav-item">
                                <button class="nav-link fw-bold" id="approved-candidates-tab" data-bs-toggle="tab" data-bs-target="#approved-candidates" type="button">
                                    Official Candidates ({{ $approvedCandidates->count() }})
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body p-4">
                        <div class="tab-content">
                            <!-- Pending Candidates -->
                            <div class="tab-pane fade show active" id="pending-candidates">
                                <div class="row row-cols-1 row-cols-md-2 g-4">
                                @forelse($pendingCandidates as $candidate)
                                    <div class="col">
                                        <div class="card h-100 border border-light shadow-sm">
                                            <div class="card-header bg-light bg-opacity-50 d-flex justify-content-between align-items-center py-3">
                                                <h6 class="mb-0 fw-bold text-admin-primary" style="cursor:pointer; text-decoration:underline;" data-bs-toggle="modal" data-bs-target="#adminCandidateModal{{ $candidate->id }}">
                                                    {{ $candidate->user->name }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.8rem;"></i>
                                                </h6>
                                                <span class="badge bg-white text-dark border shadow-sm">{{ $candidate->user->panchayat->name }}</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-2 mb-3 small">
                                                    <div class="col-6"><strong>Edu:</strong> {{ $candidate->qualification }}</div>
                                                    <div class="col-6"><strong>Gender:</strong> {{ $candidate->gender }}</div>
                                                    <div class="col-6"><strong>Voter ID:</strong> {{ $candidate->voter_id }}</div>
                                                    <div class="col-6"><strong>DOB:</strong> {{ $candidate->dob }}</div>
                                                </div>
                                                <div class="bg-light p-3 rounded mb-3">
                                                    <small class="text-muted d-block mb-1 fw-bold text-uppercase">Public Statement</small>
                                                    <div class="fst-italic small">"{{ Str::limit($candidate->manifesto, 120) }}"</div>
                                                </div>
                                                <div class="d-flex justify-content-end gap-2">
                                                    <form action="{{ route('admin.candidate.approve', $candidate->id) }}" method="POST">
                                                        @csrf
                                                        <button class="btn btn-success btn-sm px-3 fw-bold">Approve</button>
                                                    </form>
                                                    <form action="{{ route('admin.candidate.reject', $candidate->id) }}" method="POST">
                                                        @csrf
                                                        <button class="btn btn-outline-danger btn-sm px-3 fw-bold">Reject</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12 text-center py-5 text-muted">No pending applications discovered.</div>
                                @endforelse
                                </div>
                            </div>

                            <!-- Approved Candidates -->
                            <div class="tab-pane fade" id="approved-candidates">
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle">
                                        <thead>
                                            <tr>
                                                <th>Nominee</th>
                                                <th>Jurisdiction</th>
                                                <th>Identifier</th>
                                                <th class="text-end">Command</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($approvedCandidates as $candidate)
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-admin-primary" style="cursor:pointer; text-decoration:underline;" data-bs-toggle="modal" data-bs-target="#adminCandidateModal{{ $candidate->id }}">
                                                            {{ $candidate->user->name }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size:0.8rem;"></i>
                                                        </div>
                                                        <div class="small text-muted">{{ $candidate->user->email }}</div>
                                                    </td>
                                                    <td>{{ $candidate->user->panchayat->name }}</td>
                                                    <td><code>{{ $candidate->voter_id }}</code></td>
                                                    <td class="text-end">
                                                        <form action="{{ route('admin.candidate.remove', $candidate->id) }}" method="POST" onsubmit="return confirm('CRITICAL: Remove this candidate nominee permanentely?')">
                                                            @csrf
                                                            <button class="btn btn-outline-danger btn-sm px-3 fw-bold">
                                                                Depanel
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="text-center py-5 text-muted">No authorized candidates found.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Election Tab -->
            <div class="tab-pane fade" id="election" role="tabpanel">
                <div class="row justify-content-center py-4">
                    <div class="col-lg-7">
                        <div class="card admin-card text-center">
                            <div class="card-header border-0 pb-0">System Control Status</div>
                            <div class="card-body py-5 px-lg-5">
                                <div class="mb-5">
                                    @if($electionConfig->is_active)
                                        <div class="display-1 text-success mb-2"><i class="bi bi-broadcast"></i></div>
                                        <h2 class="fw-bold text-success mb-1">ELECTION LIVE</h2>
                                        <p class="text-muted">Broadcast in progress. Voting modules are active across all panchayats.</p>
                                    @else
                                        <div class="display-1 text-secondary mb-2"><i class="bi bi-slash-circle"></i></div>
                                        <h2 class="fw-bold text-secondary mb-1">ELECTION HALTED</h2>
                                        <p class="text-muted">Modules deactivated. No voting allowed until manual override.</p>
                                    @endif
                                </div>
                                
                                <form action="{{ route('admin.election.update') }}" method="POST" class="border-top pt-5">
                                    @csrf
                                    @if($electionConfig->is_active)
                                        <button type="submit" class="btn btn-danger btn-lg px-5 py-3 fw-bold shadow">
                                            EMERGENCY STOP ELECTION
                                        </button>
                                    @else
                                        <input type="hidden" name="is_active" value="1">
                                        <button type="submit" class="btn btn-admin-primary btn-lg px-5 py-3 fw-bold shadow mb-4">
                                            COMMENCE ELECTION PHASE
                                        </button>
                                        <div class="pt-2">
                                            <a href="{{ route('results') }}" class="text-decoration-none fw-bold text-primary">
                                                <i class="bi bi-graph-up-arrow me-1"></i> Audit Real-time Statistics
                                            </a>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<!-- Candidate Details Modals -->
@foreach($pendingCandidates->merge($approvedCandidates) as $modalCandidate)
<div class="modal fade" id="adminCandidateModal{{ $modalCandidate->id }}" tabindex="-1" aria-labelledby="adminCandidateModalLabel{{ $modalCandidate->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header text-white" style="background: linear-gradient(135deg, var(--admin-blue-dark), var(--admin-blue-main));">
                <h5 class="modal-title fw-bold" id="adminCandidateModalLabel{{ $modalCandidate->id }}">
                    <i class="bi bi-person-lines-fill me-2"></i>Candidate Profile Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row align-items-center mb-4 text-center text-md-start bg-white p-3 rounded shadow-sm border">
                    <div class="col-md-3 mb-3 mb-md-0 d-flex justify-content-center">
                        @if($modalCandidate->photo)
                            <img src="{{ asset('storage/' . $modalCandidate->photo) }}" class="rounded shadow border border-3 border-white" width="120" height="150" style="object-fit:cover;" alt="Candidate Photo">
                        @else
                            <div class="rounded shadow d-flex align-items-center justify-content-center bg-secondary text-white" style="width:120px; height:150px;">No Photo</div>
                        @endif
                    </div>
                    <div class="col-md-9">
                        <h3 class="fw-bold text-dark mb-1">{{ $modalCandidate->user->name }}</h3>
                        <p class="text-muted mb-2"><i class="bi bi-envelope-fill me-1"></i> {{ $modalCandidate->user->email }}</p>
                        <span class="badge {{ $modalCandidate->status == 'approved' ? 'badge-admin-success' : 'badge-admin-danger' }} px-3 py-2 rounded-pill shadow-sm">
                            Status: {{ ucfirst($modalCandidate->status) }}
                        </span>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="bg-white p-3 rounded shadow-sm border h-100">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-info-square me-2"></i>Personal Information</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2"><strong>DOB:</strong> {{ $modalCandidate->dob }}</li>
                                <li class="mb-2"><strong>Gender:</strong> {{ $modalCandidate->gender }}</li>
                                <li class="mb-2"><strong>Mobile:</strong> {{ $modalCandidate->mobile }}</li>
                                <li class=""><strong>Qualification:</strong> {{ $modalCandidate->qualification }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-white p-3 rounded shadow-sm border h-100">
                            <h6 class="fw-bold text-primary mb-3"><i class="bi bi-card-checklist me-2"></i>Identity & Eligibility</h6>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-2"><strong>Voter ID:</strong> <span class="text-dark fw-semibold">{{ $modalCandidate->voter_id }}</span></li>
                                <li class="mb-2"><strong>Aadhaar:</strong> {{ $modalCandidate->aadhaar }}</li>
                                <li class="mb-2"><strong>Panchayat:</strong> {{ $modalCandidate->user->panchayat->name ?? 'N/A' }}</li>
                                <li class="text-truncate" title="{{ $modalCandidate->address }}"><strong>Address:</strong> {{ $modalCandidate->address }}</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="bg-white p-3 rounded shadow-sm border">
                            <h6 class="fw-bold text-primary mb-2"><i class="bi bi-megaphone-fill me-2"></i>Manifesto / Vision</h6>
                            <p class="small text-muted mb-0 fst-italic border-start border-3 border-primary ps-3 bg-light p-2 rounded">
                                "{{ $modalCandidate->manifesto }}"
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white">
                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
