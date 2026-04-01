@extends('layouts.app')

@section('page_title', 'Voting')
@section('page_subtitle', 'Check status, choose a candidate, and vote.')

@section('content')
<div class="page-shell">
    <section class="profile-hero" data-reveal>
        <div class="profile-hero__grid">
            <div>
                <div class="cluster mb-3">
                    <span class="status-pill bg-white text-primary">
                        <i class="bi bi-person-vcard"></i>
                        Voter card
                    </span>
                    <span class="status-pill {{ $voter->status === 'approved' ? 'status-pill--success' : ($voter->status === 'rejected' ? 'status-pill--danger' : 'status-pill--warning') }}">
                        <i class="bi {{ $voter->status === 'approved' ? 'bi-check2-circle' : ($voter->status === 'rejected' ? 'bi-slash-circle' : 'bi-hourglass-split') }}"></i>
                        {{ ucfirst($voter->status) }}
                    </span>
                </div>

                <h1 class="page-hero__title text-white">{{ $user->name }}</h1>
                <p class="mt-2 mb-0 text-white-50 fs-5">{{ $panchayat->name }}</p>

                <div class="profile-hero__meta">
                    <div class="profile-hero__item">
                        <span class="profile-hero__label">Voter ID</span>
                        <span class="profile-hero__value">{{ $voter->voter_id_number }}</span>
                    </div>
                    <div class="profile-hero__item">
                        <span class="profile-hero__label">Date of Birth</span>
                        <span class="profile-hero__value">{{ $voter->dob }}</span>
                    </div>
                    <div class="profile-hero__item">
                        <span class="profile-hero__label">Election Status</span>
                        <span class="profile-hero__value">{{ $isElectionActive ? 'Live' : 'Closed' }}</span>
                    </div>
                </div>
            </div>

            <div class="hero-sidecard d-grid gap-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="avatar avatar--lg">
                        @if($voter->captured_photo)
                            <img src="{{ Storage::url($voter->captured_photo) }}" alt="Verification photo" width="90" height="90" loading="lazy">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </span>
                    <div>
                        <div class="profile-hero__label mb-1">Verification snapshot</div>
                        <strong class="d-block">{{ $voter->captured_photo ? 'Photo on file' : 'Photo captured during voting' }}</strong>
                        <span class="text-white-50 small">Live capture happens only at final confirmation.</span>
                    </div>
                </div>

                <div class="candidate-list">
                    <div class="candidate-list__item bg-transparent border border-light border-opacity-10">
                        <span class="icon-badge bg-white bg-opacity-10 text-white"><i class="bi bi-check2-square"></i></span>
                        <div>
                            <strong class="d-block text-white">Choose exactly one candidate</strong>
                            <span class="text-white-50 small">Pick one candidate first.</span>
                        </div>
                    </div>
                    <div class="candidate-list__item bg-transparent border border-light border-opacity-10">
                        <span class="icon-badge bg-white bg-opacity-10 text-white"><i class="bi bi-camera-video"></i></span>
                        <div>
                            <strong class="d-block text-white">Verify identity live</strong>
                            <span class="text-white-50 small">Camera opens after selection.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($voter->status !== 'approved')
        <div class="status-banner" data-reveal>
            <i class="bi bi-hourglass-split text-warning"></i>
            <div>
                <strong class="d-block mb-1">Verification still pending</strong>
                <span class="helper-copy">Your registration is under review for <strong>{{ $panchayat->name }}</strong>.</span>
            </div>
        </div>
    @else
        @if(!$isElectionActive)
            <div class="status-banner" data-reveal>
                <i class="bi bi-calendar-event text-primary"></i>
                <div>
                    <strong class="d-block mb-1">Voting is currently closed</strong>
                    <span class="helper-copy">You can still view the final result below.</span>
                </div>
            </div>
        @elseif($voter->has_voted)
            <div class="status-banner" data-reveal>
                <i class="bi bi-check2-circle text-success"></i>
                <div>
                    <strong class="d-block mb-1">Your vote has been recorded successfully</strong>
                    <span class="helper-copy">Thanks for participating. You can now view results.</span>
                </div>
            </div>
        @else
            <div class="status-banner" data-reveal>
                <i class="bi bi-broadcast-pin text-primary"></i>
                <div>
                    <strong class="d-block mb-1">Election is live</strong>
                    <span class="helper-copy">Choose a candidate, then verify your identity.</span>
                </div>
            </div>
        @endif

        @if(!$isElectionActive && $winningCandidate)
            <section class="surface-card surface-card--padded" data-reveal>
                <div class="split-grid align-items-center">
                    <div class="d-flex align-items-center gap-4">
                        <span class="avatar avatar--lg">
                            @if($winningCandidate->photo)
                                <img src="{{ Storage::url($winningCandidate->photo) }}" alt="{{ $winningCandidate->user->name }}" width="90" height="90" loading="lazy">
                            @else
                                {{ strtoupper(substr($winningCandidate->user->name, 0, 1)) }}
                            @endif
                        </span>
                        <div>
                            <div class="muted-label">Declared winner</div>
                            <h2 class="mb-1">{{ $winningCandidate->user->name }}</h2>
                            <p class="helper-copy mb-0">Representative for {{ $panchayat->name }}</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">
                        <span class="status-pill status-pill--success">
                            <i class="bi bi-trophy"></i>
                            Winner
                        </span>
                        <div class="text-end">
                            <div class="muted-label mb-1">Votes received</div>
                            <strong class="fs-2">{{ $winningCandidate->votes_count }}</strong>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        <div class="split-grid">
            <section class="surface-card surface-card--padded content-auto" data-reveal>
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                    <div>
                        <div class="muted-label">Ballot options</div>
                        <h2 class="mb-1">Candidates in {{ $panchayat->name }}</h2>
                        <p class="helper-copy mb-0">Choose one candidate below.</p>
                    </div>
                    @if($isElectionActive && !$voter->has_voted)
                        <span class="status-pill status-pill--primary">
                            <i class="bi bi-hand-index-thumb"></i>
                            Select one candidate
                        </span>
                    @endif
                </div>

                <form action="{{ route('vote.post') }}" method="POST" id="voteForm">
                    @csrf
                    <input type="hidden" name="captured_photo" id="captured_photo_input">

                    <div class="vote-grid mb-4">
                        @forelse($candidates as $candidate)
                            @php $canVote = $isElectionActive && !$voter->has_voted; @endphp
                            <label class="vote-option">
                                @if($canVote)
                                    <input type="radio" name="candidate_id" value="{{ $candidate->id }}" class="vote-option__input" required>
                                @endif

                                <div class="vote-option__card">
                                    <div class="vote-option__meta">
                                        <div class="d-flex gap-3">
                                            <span class="avatar avatar--md">
                                                @if($candidate->photo)
                                                    <img src="{{ Storage::url($candidate->photo) }}" alt="{{ $candidate->user->name }}" width="64" height="64" loading="lazy">
                                                @else
                                                    {{ strtoupper(substr($candidate->user->name, 0, 1)) }}
                                                @endif
                                            </span>
                                            <div>
                                                <strong class="d-block fs-5">{{ $candidate->user->name }}</strong>
                                                <span class="helper-copy">{{ $candidate->qualification }}</span>
                                            </div>
                                        </div>
                                        @if($canVote)
                                            <span class="vote-option__check"><i class="bi bi-check-lg"></i></span>
                                        @endif
                                    </div>

                                    <div class="vote-option__footer">
                                        <span class="info-chip">Candidate profile available</span>
                                        <a href="{{ route('candidate.profile', $candidate->id) }}" class="btn btn-outline-primary btn-sm" onclick="event.stopPropagation();">
                                            <i class="bi bi-person-lines-fill"></i>
                                            View Profile
                                        </a>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="status-banner">
                                <i class="bi bi-inbox text-muted"></i>
                                <div>
                                    <strong class="d-block mb-1">No approved candidates yet</strong>
                                    <span class="helper-copy">This panchayat does not have an approved ballot to show right now.</span>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if($isElectionActive && !$voter->has_voted && $candidates->count() > 0)
                        <button type="button" class="btn btn-primary btn-lg w-100" id="btnCastVote">
                            <i class="bi bi-camera-video"></i>
                            Verify Identity and Cast Vote
                        </button>
                    @endif
                </form>
            </section>

            <aside class="surface-card surface-card--padded" data-reveal data-reveal-delay="80">
                <div class="muted-label">Voting checklist</div>
                <h2 class="mb-3">What happens next</h2>
                <div class="candidate-list">
                    <div class="candidate-list__item">
                        <span class="icon-badge"><i class="bi bi-1-circle"></i></span>
                        <div>
                            <strong class="d-block">Select one candidate</strong>
                            <span class="helper-copy">Pick one option.</span>
                        </div>
                    </div>
                    <div class="candidate-list__item">
                        <span class="icon-badge"><i class="bi bi-2-circle"></i></span>
                        <div>
                            <strong class="d-block">Open the camera</strong>
                            <span class="helper-copy">Take a live photo.</span>
                        </div>
                    </div>
                    <div class="candidate-list__item">
                        <span class="icon-badge"><i class="bi bi-3-circle"></i></span>
                        <div>
                            <strong class="d-block">Confirm the ballot</strong>
                            <span class="helper-copy">Confirm and submit your vote.</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>

        <div class="modal fade" id="cameraModal" tabindex="-1" data-bs-backdrop="static" aria-labelledby="cameraModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="cameraModalLabel">Identity verification before vote submission</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" id="btnCloseCamera" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="preparation-step" class="d-grid gap-3 text-center">
                            <span class="icon-badge mx-auto"><i class="bi bi-camera-video"></i></span>
                            <div>
                                <h3 class="mb-2">A live photo is required</h3>
                                <p class="helper-copy mb-0">A live photo is required before submission.</p>
                            </div>
                            <button type="button" id="btnReady" class="btn btn-primary">
                                <i class="bi bi-camera"></i>
                                Open Camera
                            </button>
                        </div>

                        <div id="camera-step" class="d-none">
                            <div class="camera-stage mb-3" id="camera-container">
                                <video id="video" autoplay playsinline></video>
                                <canvas id="canvas" class="d-none"></canvas>
                            </div>

                            <div id="photo-preview-container" class="camera-preview d-none mb-3">
                                <img id="photo-preview" alt="Captured verification preview">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="button" id="btnCapture" class="btn btn-primary">
                                    <i class="bi bi-camera-fill"></i>
                                    Capture Photo
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" id="btnRetake" class="btn btn-light d-none flex-fill">Retake</button>
                                    <button type="button" id="btnSubmitVote" class="btn btn-success d-none flex-fill">Confirm Vote</button>
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
                const modalElement = document.getElementById('cameraModal');
                const voteForm = document.getElementById('voteForm');
                const btnCastVote = document.getElementById('btnCastVote');
                const btnReady = document.getElementById('btnReady');
                const btnCapture = document.getElementById('btnCapture');
                const btnRetake = document.getElementById('btnRetake');
                const btnSubmitVote = document.getElementById('btnSubmitVote');
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const photoPreview = document.getElementById('photo-preview');
                const photoInput = document.getElementById('captured_photo_input');
                const preparationStep = document.getElementById('preparation-step');
                const cameraStep = document.getElementById('camera-step');
                const cameraContainer = document.getElementById('camera-container');
                const previewContainer = document.getElementById('photo-preview-container');

                if (!modalElement || !btnCastVote || !window.bootstrap) {
                    return;
                }

                const cameraModal = new bootstrap.Modal(modalElement);
                let stream = null;

                const stopCamera = () => {
                    if (stream) {
                        stream.getTracks().forEach((track) => track.stop());
                        stream = null;
                    }
                };

                const resetModal = () => {
                    stopCamera();
                    preparationStep.classList.remove('d-none');
                    cameraStep.classList.add('d-none');
                    cameraContainer.classList.remove('d-none');
                    previewContainer.classList.add('d-none');
                    btnCapture.classList.remove('d-none');
                    btnRetake.classList.add('d-none');
                    btnSubmitVote.classList.add('d-none');
                    btnSubmitVote.disabled = false;
                    btnSubmitVote.textContent = 'Confirm Vote';
                    photoInput.value = '';
                    photoPreview.removeAttribute('src');
                };

                const startCamera = async () => {
                    preparationStep.classList.add('d-none');
                    cameraStep.classList.remove('d-none');

                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                        video.srcObject = stream;
                        await video.play();
                    } catch (error) {
                        alert('Camera access is required to continue with identity verification.');
                        cameraModal.hide();
                    }
                };

                btnCastVote.addEventListener('click', function () {
                    const selection = document.querySelector('input[name="candidate_id"]:checked');

                    if (!selection) {
                        alert('Please select one candidate before continuing to verification.');
                        return;
                    }

                    cameraModal.show();
                });

                btnReady.addEventListener('click', startCamera);

                btnCapture.addEventListener('click', function () {
                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);

                    const capturedData = canvas.toDataURL('image/jpeg', 0.8);
                    photoPreview.src = capturedData;
                    photoInput.value = capturedData;

                    cameraContainer.classList.add('d-none');
                    previewContainer.classList.remove('d-none');
                    btnCapture.classList.add('d-none');
                    btnRetake.classList.remove('d-none');
                    btnSubmitVote.classList.remove('d-none');
                    stopCamera();
                });

                btnRetake.addEventListener('click', async function () {
                    cameraContainer.classList.remove('d-none');
                    previewContainer.classList.add('d-none');
                    btnCapture.classList.remove('d-none');
                    btnRetake.classList.add('d-none');
                    btnSubmitVote.classList.add('d-none');
                    await startCamera();
                });

                btnSubmitVote.addEventListener('click', function () {
                    btnSubmitVote.disabled = true;
                    btnSubmitVote.innerHTML = '<span class="button-spinner" aria-hidden="true"></span>Submitting vote...';
                    voteForm.submit();
                });

                modalElement.addEventListener('hidden.bs.modal', resetModal);
            });
        </script>
        @endpush
    @endif
</div>
@endsection
