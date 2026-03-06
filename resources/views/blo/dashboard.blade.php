@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-md-12">
        <div class="card bg-white shadow-sm border-0">
            <div class="card-body p-4 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-0">Officer Dashboard</h2>
                    <p class="text-muted mb-0">Panchayat: <span class="fw-bold text-primary">{{ $panchayat->name }}</span></p>
                </div>
                <div class="text-end">
                    <span class="badge bg-light text-dark border p-2 mb-1 d-block">Officer: {{ $user->name }}</span>
                    <small class="text-muted">ID: BLO-{{ $user->id }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Pending Requests Column -->
    <div class="col-md-7">
        <div class="card shadow h-100">
            <div class="card-header bg-warning bg-opacity-10 text-warning-emphasis fw-bold py-3 d-flex justify-content-between align-items-center">
                <span><i class="bi bi-hourglass-split me-2"></i>Pending Voter Approvals</span>
                <span class="badge bg-warning text-dark">{{ $pendingVoters->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($pendingVoters->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Applicant Details</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingVoters as $voter)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="fw-bold">{{ $voter->user->name }}</div>
                                            <div class="small text-muted mb-1">{{ $voter->user->email }}</div>
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-light text-dark border">ID: {{ $voter->voter_id_number }}</span>
                                                <span class="badge bg-light text-dark border">DOB: {{ $voter->dob }}</span>
                                            </div>
                                        </td>
                                        <td style="width: 150px;">
                                            <div class="d-flex gap-2">
                                                <form action="{{ route('blo.approve', $voter->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-success btn-sm" title="Approve">
                                                        <i class="bi bi-check-lg"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('blo.reject', $voter->id) }}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-outline-danger btn-sm" title="Reject">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-clipboard-check fs-1 text-muted opacity-50 mb-2"></i>
                        <p class="text-muted">No pending approvals needed.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Stats and Approved List -->
    <div class="col-md-5">
        <div class="card shadow mb-4">
            <div class="card-body text-center py-4">
                <h6 class="text-muted text-uppercase small letter-spacing-1">Total Verified Voters</h6>
                <h1 class="display-4 fw-bold text-success mb-0">{{ $approvedVoters->count() }}</h1>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header bg-success bg-opacity-10 text-success-emphasis fw-bold py-3">
                <i class="bi bi-people-fill me-2"></i>Verified Voters List
            </div>
            <div class="card-body p-0">
                 @if($approvedVoters->count() > 0)
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-hover mb-0 align-middle">
                            <thead class="bg-light sticky-top">
                                <tr>
                                    <th class="ps-3">Voter Details</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($approvedVoters as $voter)
                                    <tr style="cursor: pointer;" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#voterDetailsModal"
                                        data-name="{{ $voter->user->name }}"
                                        data-email="{{ $voter->user->email }}"
                                        data-voterid="{{ $voter->voter_id_number }}"
                                        data-dob="{{ $voter->dob }}"
                                        data-panchayat="{{ $panchayat->name }}"
                                        data-photo="{{ $voter->captured_photo ? asset('storage/' . $voter->captured_photo) : '' }}">
                                        <td class="ps-3">
                                            <div class="fw-bold text-primary">{{ $voter->user->name }}</div>
                                            <div class="small text-muted">{{ $voter->voter_id_number }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-center text-muted py-4">No verified voters yet.</p>
                @endif
            </div>
        </div>
    </div>
    <!-- Result Management -->
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-primary bg-opacity-10 text-primary-emphasis fw-bold py-3">
                <i class="bi bi-megaphone-fill me-2"></i>Result Management
            </div>
            <div class="card-body d-flex align-items-center justify-content-between">
                <div>
                    <h5 class="fw-bold">Panchayat Election Result Status</h5>
                    @if($panchayat->is_result_published)
                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> PUBLISHED</span>
                        <p class="text-muted small mt-1 mb-0">Results are visible to the public.</p>
                    @else
                        <span class="badge bg-secondary"><i class="bi bi-eye-slash-fill me-1"></i> HIDDEN</span>
                        <p class="text-muted small mt-1 mb-0">Results are currently hidden from the public.</p>
                    @endif
                </div>
                
                <div>
                    @if($panchayat->is_result_published)
                         <form action="{{ route('blo.unpublish') }}" method="POST" onsubmit="return confirm('Are you sure? This will hide the results from the public.')">
                             @csrf
                             <button class="btn btn-danger">
                                  <i class="bi bi-eye-slash-fill me-1"></i> Unpublish Result
                             </button>
                         </form>
                    @elseif($isElectionActive)
                        <button class="btn btn-secondary" disabled title="Wait for election to end">
                            <i class="bi bi-lock-fill me-1"></i> Publish Result
                        </button>
                        <small class="d-block text-muted mt-1 text-center">Election must end first</small>
                    @elseif($panchayat->was_published)
                        <button class="btn btn-secondary" disabled>
                            <i class="bi bi-check-circle me-1"></i> Published & Removed
                        </button>
                        <small class="d-block text-muted mt-1 text-center">Cycle complete</small>
                    @else
                        <form action="{{ route('blo.publish') }}" method="POST" onsubmit="return confirm('Are you sure? Once published, results will be visible to everyone.')">
                            @csrf
                            <button class="btn btn-primary pulse">
                                <i class="bi bi-broadcast me-1"></i> Publish Result
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Voter Details Modal -->
<div class="modal fade" id="voterDetailsModal" tabindex="-1" aria-labelledby="voterDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="voterDetailsModalLabel">Voter Complete Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div id="modalVoterIconContainer" class="mb-3">
                        <i id="modalVoterIcon" class="bi bi-person-circle text-muted" style="font-size: 5rem;"></i>
                        <img id="modalVoterPhoto" src="" alt="Voter Photo" class="img-thumbnail rounded-circle shadow-sm" style="width: 150px; height: 150px; object-fit: cover; display: none;">
                    </div>
                    <h4 id="modalVoterNameDisplay" class="fw-bold mb-0"></h4>
                    <span id="modalVoterIDDisplay" class="badge bg-light text-dark border"></span>
                </div>
                
                <div class="row g-3">
                    <div class="col-6">
                        <label class="small text-muted text-uppercase fw-bold">Email Address</label>
                        <div id="modalVoterEmail" class="fw-bold text-dark"></div>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted text-uppercase fw-bold">Date of Birth</label>
                        <div id="modalVoterDOB" class="fw-bold text-dark"></div>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted text-uppercase fw-bold">Panchayat</label>
                        <div id="modalVoterPanchayat" class="fw-bold text-dark"></div>
                    </div>
                    <div class="col-6">
                        <label class="small text-muted text-uppercase fw-bold">Verification Status</label>
                        <div><span class="badge bg-success">Approved</span></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const voterModal = document.getElementById('voterDetailsModal');
    if (voterModal) {
        voterModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const name = button.getAttribute('data-name');
            const email = button.getAttribute('data-email');
            const voterId = button.getAttribute('data-voterid');
            const dob = button.getAttribute('data-dob');
            const panchayat = button.getAttribute('data-panchayat');
            const photo = button.getAttribute('data-photo');

            document.getElementById('modalVoterNameDisplay').textContent = name;
            document.getElementById('modalVoterEmail').textContent = email;
            document.getElementById('modalVoterIDDisplay').textContent = voterId;
            document.getElementById('modalVoterDOB').textContent = dob;
            document.getElementById('modalVoterPanchayat').textContent = panchayat;

            const photoImg = document.getElementById('modalVoterPhoto');
            const personIcon = document.getElementById('modalVoterIcon');
            
            if (photo && photo !== '' && !photo.includes('undefined')) {
                photoImg.src = photo;
                photoImg.style.display = 'inline-block';
                personIcon.style.display = 'none';
            } else {
                photoImg.style.display = 'none';
                personIcon.style.display = 'inline-block';
            }
        });
    }
});
</script>
@endpush
@endsection

