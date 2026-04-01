@extends('layouts.app')

@section('page_title', 'Officer')
@section('page_subtitle', 'Approve voters and manage result visibility.')

@section('content')
<div class="page-shell">
    <x-ui.page-header eyebrow="Booth Level Operations" title="Officer dashboard" subtitle="Pending approvals first, result controls second.">
        <span class="info-chip">
            <i class="bi bi-geo-alt"></i>
            {{ $panchayat->name }}
        </span>
        <span class="status-pill {{ $isElectionActive ? 'status-pill--primary' : 'status-pill--warning' }}">
            <i class="bi {{ $isElectionActive ? 'bi-broadcast-pin' : 'bi-pause-circle' }}"></i>
            {{ $isElectionActive ? 'Election live' : 'Election paused' }}
        </span>
    </x-ui.page-header>

    <section class="stat-grid">
        <x-ui.stat-card label="Pending Approvals" value="{{ $pendingVoters->count() }}" icon="bi-hourglass-split" tone="warning" meta="Applicants awaiting BLO review in your panchayat." />
        <x-ui.stat-card label="Verified Voters" value="{{ $approvedVoters->count() }}" icon="bi-check2-circle" tone="success" meta="Approved voters currently ready to participate." />
        <x-ui.stat-card label="Officer ID" value="BLO-{{ $user->id }}" icon="bi-person-badge" meta="Current operator workspace for {{ $user->name }}." />
    </section>

    <div class="split-grid">
        <section class="surface-card content-auto" data-reveal>
            <div class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Pending voter approvals</h2>
                    <p class="panel-card__subtitle">Approve or reject pending requests.</p>
                </div>
                <span class="status-pill status-pill--warning">
                    <i class="bi bi-person-lines-fill"></i>
                    {{ $pendingVoters->count() }} waiting
                </span>
            </div>

            <div class="p-4 pt-3">
                <div class="table-shell">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Applicant</th>
                                    <th>Credentials</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingVoters as $voter)
                                    <tr>
                                        <td data-label="Applicant">
                                            <strong class="d-block">{{ $voter->user->name }}</strong>
                                            <span class="helper-copy">{{ $voter->user->email }}</span>
                                        </td>
                                        <td data-label="Credentials">
                                            <div class="d-flex flex-wrap gap-2">
                                                <span class="info-chip">ID: {{ $voter->voter_id_number }}</span>
                                                <span class="info-chip">DOB: {{ $voter->dob }}</span>
                                            </div>
                                        </td>
                                        <td data-label="Actions" class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <form action="{{ route('blo.approve', $voter->id) }}" method="POST" class="prevent-double" data-pending-text="Approving...">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm">
                                                        <i class="bi bi-check-lg"></i>
                                                        Approve
                                                    </button>
                                                </form>
                                                <form action="{{ route('blo.reject', $voter->id) }}" method="POST" class="prevent-double" data-pending-text="Rejecting...">
                                                    @csrf
                                                    <button class="btn btn-light btn-sm">
                                                        <i class="bi bi-x-lg"></i>
                                                        Reject
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-5 text-muted">No pending approvals right now.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="surface-card content-auto" data-reveal data-reveal-delay="80">
            <div class="panel-card__header">
                <div>
                    <h2 class="panel-card__title">Verified voters</h2>
                    <p class="panel-card__subtitle">Open a voter record when you need more detail.</p>
                </div>
                <span class="status-pill status-pill--success">
                    <i class="bi bi-people-fill"></i>
                    {{ $approvedVoters->count() }} verified
                </span>
            </div>

            <div class="p-4 pt-3">
                <div class="table-shell">
                    <div class="table-responsive table-scroll-y">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Voter</th>
                                    <th>Record</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvedVoters as $voter)
                                    <tr
                                        class="clickable-row"
                                        data-bs-toggle="modal"
                                        data-bs-target="#voterDetailsModal"
                                        data-name="{{ $voter->user->name }}"
                                        data-email="{{ $voter->user->email }}"
                                        data-voterid="{{ $voter->voter_id_number }}"
                                        data-dob="{{ $voter->dob }}"
                                        data-panchayat="{{ $panchayat->name }}"
                                        data-photo="{{ $voter->captured_photo ? Storage::url($voter->captured_photo) : '' }}"
                                    >
                                        <td data-label="Voter">
                                            <strong class="d-block text-primary">{{ $voter->user->name }}</strong>
                                            <span class="helper-copy">{{ $voter->voter_id_number }}</span>
                                        </td>
                                        <td data-label="Record">
                                            <span class="status-pill status-pill--success">Approved</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center py-5 text-muted">No verified voters yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <section class="surface-card surface-card--padded" data-reveal>
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="muted-label">Result management</div>
                <h2 class="mb-1">Panchayat result visibility</h2>
                <p class="helper-copy mb-0">
                    Publish only after voting ends.
                </p>
            </div>

            <div class="cluster">
                @if($panchayat->is_result_published)
                    <span class="status-pill status-pill--success"><i class="bi bi-check2-circle"></i> Published</span>
                    <form action="{{ route('blo.unpublish') }}" method="POST" class="prevent-double" data-pending-text="Unpublishing..." onsubmit="return confirm('Are you sure you want to hide the published result?');">
                        @csrf
                        <button class="btn btn-danger">
                            <i class="bi bi-eye-slash"></i>
                            Unpublish Result
                        </button>
                    </form>
                @elseif($isElectionActive)
                    <span class="status-pill status-pill--warning"><i class="bi bi-lock"></i> Wait until voting ends</span>
                    <button class="btn btn-light" disabled>
                        <i class="bi bi-megaphone"></i>
                        Publish Result
                    </button>
                @elseif($panchayat->was_published)
                    <span class="status-pill status-pill--primary"><i class="bi bi-arrow-repeat"></i> Cycle complete</span>
                    <button class="btn btn-light" disabled>
                        <i class="bi bi-check2-all"></i>
                        Published and removed
                    </button>
                @else
                    <span class="status-pill status-pill--primary"><i class="bi bi-eye"></i> Hidden from public</span>
                    <form action="{{ route('blo.publish') }}" method="POST" class="prevent-double" data-pending-text="Publishing..." onsubmit="return confirm('Once published, the result becomes visible to everyone. Continue?');">
                        @csrf
                        <button class="btn btn-primary">
                            <i class="bi bi-broadcast"></i>
                            Publish Result
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="voterDetailsModal" tabindex="-1" aria-labelledby="voterDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="voterDetailsModalLabel">Voter details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="avatar avatar--lg" id="modalVoterAvatar">?</span>
                    <div>
                        <strong class="d-block fs-5" id="modalVoterNameDisplay"></strong>
                        <span class="helper-copy" id="modalVoterIDDisplay"></span>
                    </div>
                </div>

                <div class="candidate-list">
                    <div class="candidate-list__item"><strong>Email:</strong> <span id="modalVoterEmail"></span></div>
                    <div class="candidate-list__item"><strong>Date of Birth:</strong> <span id="modalVoterDOB"></span></div>
                    <div class="candidate-list__item"><strong>Panchayat:</strong> <span id="modalVoterPanchayat"></span></div>
                    <div class="candidate-list__item"><strong>Status:</strong> <span class="status-pill status-pill--success">Approved</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const voterModal = document.getElementById('voterDetailsModal');

        if (!voterModal) {
            return;
        }

        voterModal.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            const photo = trigger.getAttribute('data-photo');
            const name = trigger.getAttribute('data-name');

            document.getElementById('modalVoterNameDisplay').textContent = name;
            document.getElementById('modalVoterEmail').textContent = trigger.getAttribute('data-email');
            document.getElementById('modalVoterIDDisplay').textContent = trigger.getAttribute('data-voterid');
            document.getElementById('modalVoterDOB').textContent = trigger.getAttribute('data-dob');
            document.getElementById('modalVoterPanchayat').textContent = trigger.getAttribute('data-panchayat');

            const avatar = document.getElementById('modalVoterAvatar');
            if (photo) {
                avatar.innerHTML = `<img src="${photo}" alt="${name}" width="90" height="90">`;
            } else {
                avatar.textContent = name.charAt(0).toUpperCase();
            }
        });
    });
</script>
@endpush
@endsection
