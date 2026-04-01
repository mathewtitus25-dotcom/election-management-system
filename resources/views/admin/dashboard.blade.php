@extends('layouts.app')

@section('page_title', 'Admin')
@section('page_subtitle', 'Manage officers, approvals, and election state.')

@section('content')
@php
    $combinedCandidates = $pendingCandidates->merge($approvedCandidates);
    $topCandidateLabel = str_contains($stats['top_candidate'], ' (') ? \Illuminate\Support\Str::before($stats['top_candidate'], ' (') : $stats['top_candidate'];
    $topCandidateMeta = str_contains($stats['top_candidate'], ' (') ? trim(\Illuminate\Support\Str::after($stats['top_candidate'], ' ('), ')') : 'Current front-runner by vote total.';
    $adminChartConfig = [
        'type' => 'bar',
        'data' => [
            'labels' => array_keys($stats['votes_per_panchayat']),
            'datasets' => [[
                'label' => 'Votes Cast',
                'data' => array_values($stats['votes_per_panchayat']),
                'backgroundColor' => ['rgba(20, 89, 217, 0.82)', 'rgba(15, 157, 143, 0.78)', 'rgba(79, 139, 244, 0.72)', 'rgba(25, 135, 84, 0.7)'],
                'borderRadius' => 12,
                'borderSkipped' => false,
                'barThickness' => 36,
            ]],
        ],
        'options' => [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => ['display' => false],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'grid' => ['color' => 'rgba(88, 112, 134, 0.14)'],
                    'ticks' => ['precision' => 0],
                ],
                'x' => [
                    'grid' => ['display' => false],
                ],
            ],
        ],
    ];
@endphp

<div class="page-shell">
    <x-ui.page-header eyebrow="Election Operations" title="Admin control room" subtitle="Core status, officer management, and approvals in one place.">
        <x-slot:actions>
            <a href="{{ route('admin.voters.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-list-columns-reverse"></i>
                Verification Logs
            </a>
        </x-slot:actions>
        <span class="status-pill {{ $electionConfig->is_active ? 'status-pill--success' : 'status-pill--warning' }}">
            <i class="bi {{ $electionConfig->is_active ? 'bi-broadcast-pin' : 'bi-pause-circle' }}"></i>
            Election {{ $electionConfig->is_active ? 'Live' : 'Paused' }}
        </span>
        <span class="info-chip">
            <i class="bi bi-hourglass-split"></i>
            {{ $pendingCandidates->count() }} pending candidate review{{ $pendingCandidates->count() === 1 ? '' : 's' }}
        </span>
    </x-ui.page-header>

    <section class="surface-card surface-card--padded content-auto" data-reveal>
        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
            <div>
                <div class="muted-label">Officer Management</div>
                <h2 class="mb-1">Add BLO</h2>
                <p class="helper-copy mb-0">Create and assign an officer.</p>
            </div>
            <span class="info-chip">
                <i class="bi bi-shield-plus"></i>
                {{ $stats['total_blos'] }} BLOs
            </span>
        </div>

        <form action="{{ route('admin.blo.create') }}" method="POST" class="row g-3 prevent-double" data-pending-text="Creating BLO...">
            @csrf
            <div class="col-12 col-md-6 col-xl-3">
                <label for="blo_name" class="form-label fw-semibold">Name</label>
                <input type="text" id="blo_name" name="name" value="{{ old('name') }}" class="form-control" required>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <label for="blo_email" class="form-label fw-semibold">Email</label>
                <input type="email" id="blo_email" name="email" value="{{ old('email') }}" class="form-control" required>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <label for="blo_password" class="form-label fw-semibold">Password</label>
                <input type="password" id="blo_password" name="password" class="form-control" required>
            </div>
            <div class="col-12 col-md-6 col-xl-3">
                <label for="blo_panchayat" class="form-label fw-semibold">Panchayat</label>
                <select id="blo_panchayat" name="panchayat_id" class="form-select" required>
                    <option value="">Select Panchayat</option>
                    @foreach($panchayats as $panchayat)
                        <option value="{{ $panchayat->id }}" {{ old('panchayat_id') == $panchayat->id ? 'selected' : '' }}>
                            {{ $panchayat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-person-plus"></i>
                    Create BLO
                </button>
            </div>
        </form>
    </section>

    <section class="stat-grid">
        <x-ui.stat-card label="Approved Voters" value="{{ $stats['total_approved_voters'] }}" icon="bi-people" meta="Verified residents ready for the current election cycle." />
        <x-ui.stat-card label="Approved Candidates" value="{{ $stats['total_approved_candidates'] }}" icon="bi-megaphone" tone="accent" meta="Nominees that have passed the admin approval workflow." />
        <x-ui.stat-card label="Overall Turnout" value="{{ $stats['turnout_percentage'] }}%" icon="bi-graph-up-arrow" tone="success" meta="A quick scan of turnout performance across all approved voters." />
        <x-ui.stat-card label="Leading Candidate" value="{{ $topCandidateLabel }}" icon="bi-trophy" tone="warning" meta="{{ $topCandidateMeta }}" />
    </section>

    <section class="dashboard-grid">
        <article class="surface-card chart-card content-auto" data-reveal>
            <div class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Votes per panchayat</h2>
                    <p class="panel-card__subtitle">Votes cast by panchayat.</p>
                </div>
            </div>
            <div class="chart-shell">
                <canvas id="panchayatChart" data-chart-config='@json($adminChartConfig)'></canvas>
            </div>
        </article>

        <article class="surface-card surface-card--padded content-auto" data-reveal data-reveal-delay="90">
            <div class="d-grid gap-3">
                <div>
                    <div class="muted-label">Election State</div>
                    <h2 class="mb-2">{{ $electionConfig->is_active ? 'Voting is currently live.' : 'Voting is currently paused.' }}</h2>
                    <p class="helper-copy mb-0">Start or stop the election here.</p>
                </div>

                <div class="candidate-list">
                    <div class="candidate-list__item">
                        <span class="icon-badge"><i class="bi bi-building"></i></span>
                        <div>
                            <strong class="d-block">{{ $stats['total_panchayats'] }} Panchayats</strong>
                            <span class="helper-copy">Configured panchayats.</span>
                        </div>
                    </div>
                    <div class="candidate-list__item">
                        <span class="icon-badge"><i class="bi bi-shield-check"></i></span>
                        <div>
                            <strong class="d-block">{{ $stats['total_blos'] }} BLOs</strong>
                            <span class="helper-copy">Active officer accounts.</span>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.election.update') }}" method="POST" class="prevent-double" data-pending-text="{{ $electionConfig->is_active ? 'Stopping election...' : 'Starting election...' }}">
                    @csrf
                    @unless($electionConfig->is_active)
                        <input type="hidden" name="is_active" value="1">
                    @endunless

                    @if($electionConfig->is_active)
                        <button class="btn btn-danger btn-lg w-100">
                            <i class="bi bi-stop-circle"></i>
                            Emergency Stop
                        </button>
                    @else
                        <button class="btn btn-primary btn-lg w-100">
                            <i class="bi bi-play-circle"></i>
                            Commence Voting
                        </button>
                    @endif
                </form>
            </div>
        </article>
    </section>

    <section class="surface-card content-auto" data-reveal>
        <div class="panel-card__header">
            <div>
                <h2 class="panel-card__title">People and approvals</h2>
                <p class="panel-card__subtitle">Manage candidates and officers.</p>
            </div>

            <ul class="nav segmented-control" id="adminTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#candidates" type="button">Candidates ({{ $pendingCandidates->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#blos" type="button">Officers</button>
                </li>
            </ul>
        </div>

        <div class="tab-content p-4 pt-3">
            <div class="tab-pane fade show active" id="candidates" role="tabpanel">
                <div class="table-shell">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Panchayat</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($combinedCandidates as $candidate)
                                    <tr>
                                        <td data-label="Candidate">
                                            <button
                                                type="button"
                                                class="btn p-0 border-0 bg-transparent text-start d-flex align-items-center gap-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#candidateDetailsModal"
                                                data-name="{{ $candidate->user->name }}"
                                                data-email="{{ $candidate->user->email }}"
                                                data-panchayat="{{ $candidate->user->panchayat->name }}"
                                                data-status="{{ ucfirst($candidate->status) }}"
                                                data-gender="{{ $candidate->gender }}"
                                                data-qualification="{{ $candidate->qualification }}"
                                                data-manifesto="{{ $candidate->manifesto }}"
                                                data-photo="{{ $candidate->photo ? Storage::url($candidate->photo) : '' }}"
                                            >
                                                <span class="avatar avatar--sm">
                                                    @if($candidate->photo)
                                                        <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->user->name }}" width="44" height="44" loading="lazy">
                                                    @else
                                                        {{ strtoupper(substr($candidate->user->name, 0, 1)) }}
                                                    @endif
                                                </span>
                                                <span>
                                                    <strong class="d-block text-dark">{{ $candidate->user->name }}</strong>
                                                    <span class="helper-copy">{{ $candidate->user->email }}</span>
                                                </span>
                                            </button>
                                        </td>
                                        <td data-label="Panchayat">
                                            <span class="info-chip">{{ $candidate->user->panchayat->name }}</span>
                                        </td>
                                        <td data-label="Status">
                                            <span class="status-pill {{ $candidate->status === 'approved' ? 'status-pill--success' : 'status-pill--warning' }}">
                                                {{ ucfirst($candidate->status) }}
                                            </span>
                                        </td>
                                        <td data-label="Action" class="text-end">
                                            @if($candidate->status === 'pending')
                                                <form action="{{ route('admin.candidate.approve', $candidate->id) }}" method="POST" class="d-inline prevent-double" data-pending-text="Approving...">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm">
                                                        <i class="bi bi-check2-circle"></i>
                                                        Approve
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('admin.candidate.remove', $candidate->id) }}" method="POST" class="d-inline prevent-double" data-pending-text="Removing...">
                                                    @csrf
                                                    <button class="btn btn-outline-primary btn-sm">
                                                        <i class="bi bi-person-dash"></i>
                                                        Depanel
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No candidates exist in the system.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="blos" role="tabpanel">
                <div class="table-shell">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Officer</th>
                                    <th>Jurisdiction</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blos as $blo)
                                    <tr>
                                        <td data-label="Officer">
                                            <strong class="d-block">{{ $blo->user->name }}</strong>
                                            <span class="helper-copy">{{ $blo->user->email }}</span>
                                        </td>
                                        <td data-label="Jurisdiction">
                                            <span class="info-chip">{{ $blo->user->panchayat->name ?? 'Unassigned' }}</span>
                                        </td>
                                        <td data-label="Status">
                                            <span class="status-pill {{ $blo->is_active ? 'status-pill--success' : 'status-pill--danger' }}">
                                                {{ $blo->is_active ? 'Active' : 'Suspended' }}
                                            </span>
                                        </td>
                                        <td data-label="Action" class="text-end">
                                            <form action="{{ route('admin.blo.toggle', $blo->id) }}" method="POST" class="d-inline prevent-double" data-pending-text="Updating...">
                                                @csrf
                                                <button class="btn btn-outline-primary btn-sm">
                                                    <i class="bi bi-arrow-repeat"></i>
                                                    Toggle
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">No officers found yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="candidateDetailsModal" tabindex="-1" aria-labelledby="candidateDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="candidateDetailsModalLabel">Candidate details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="avatar avatar--md" id="candidateModalAvatar">?</span>
                    <div>
                        <strong class="d-block fs-5" id="candidateModalName"></strong>
                        <span class="helper-copy" id="candidateModalEmail"></span>
                    </div>
                </div>

                <div class="d-grid gap-3">
                    <div class="candidate-list__item">
                        <div>
                            <div class="muted-label mb-1">Panchayat</div>
                            <div id="candidateModalPanchayat"></div>
                        </div>
                    </div>
                    <div class="candidate-list__item">
                        <div>
                            <div class="muted-label mb-1">Status</div>
                            <div id="candidateModalStatus"></div>
                        </div>
                    </div>
                    <div class="candidate-list__item">
                        <div>
                            <div class="muted-label mb-1">Profile snapshot</div>
                            <div id="candidateModalProfile"></div>
                        </div>
                    </div>
                    <div class="candidate-list__item">
                        <div>
                            <div class="muted-label mb-1">Manifesto</div>
                            <p class="mb-0" id="candidateModalManifesto"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('candidateDetailsModal');

        if (!modal) {
            return;
        }

        modal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            const photo = trigger.getAttribute('data-photo');
            const name = trigger.getAttribute('data-name');
            const email = trigger.getAttribute('data-email');
            const panchayat = trigger.getAttribute('data-panchayat');
            const status = trigger.getAttribute('data-status');
            const gender = trigger.getAttribute('data-gender');
            const qualification = trigger.getAttribute('data-qualification');
            const manifesto = trigger.getAttribute('data-manifesto');

            const avatar = document.getElementById('candidateModalAvatar');
            const statusClass = status === 'Approved' ? 'status-pill status-pill--success' : 'status-pill status-pill--warning';

            document.getElementById('candidateModalName').textContent = name;
            document.getElementById('candidateModalEmail').textContent = email;
            document.getElementById('candidateModalPanchayat').textContent = panchayat;
            document.getElementById('candidateModalStatus').innerHTML = `<span class="${statusClass}">${status}</span>`;
            document.getElementById('candidateModalProfile').textContent = `${gender || 'Not set'} | ${qualification || 'Qualification not set'}`;
            document.getElementById('candidateModalManifesto').textContent = manifesto || 'No manifesto submitted.';

            if (photo) {
                avatar.innerHTML = `<img src="${photo}" alt="${name}" width="58" height="58">`;
            } else {
                avatar.textContent = name.charAt(0).toUpperCase();
            }
        });
    });
</script>
@endpush
@endsection
