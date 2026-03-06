@extends('layouts.app')

@section('content')
<div class="container pb-5">
    
    <!-- Profile Header -->
    <div class="card border-0 shadow-lg bg-gradient-primary text-dark mb-5 overflow-hidden position-relative">
        <div class="card-body p-4 p-md-5 position-relative z-1">
            <div class="d-flex align-items-center gap-3 mb-2">
                <span class="badge bg-dark text-white text-uppercase" style="letter-spacing: 1px;">Voter Card</span>
                <div class="ms-auto d-flex align-items-center gap-2">
                    @if($voter->status == 'approved')
                        <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i>Verified Voter</span>
                    @elseif($voter->status == 'rejected')
                        <span class="badge bg-danger">Rejected Voter</span>
                    @else
                        <span class="badge bg-warning text-dark">Pending Approval</span>
                    @endif
                </div>
            </div>
            <h1 class="display-5 fw-bold mb-1">{{ $user->name }}</h1>
            <p class="lead mb-4">{{ $panchayat->name }}</p>

            <div class="d-flex gap-4 text-muted align-items-center mb-3">
                @if($voter->captured_photo)
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $voter->captured_photo) }}" 
                             alt="Verification Photo" 
                             class="rounded-circle border border-4 border-white shadow-sm" 
                             style="width: 80px; height: 80px; object-fit: cover;">
                        <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-success p-1 border border-white">
                            <i class="bi bi-camera-fill small"></i>
                        </span>
                    </div>
                @endif
                <div>
                     <small class="d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Voter ID</small>
                     <span class="fw-bold text-dark">{{ $voter->voter_id_number }}</span>
                </div>
                <div>
                     <small class="d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Date of Birth</small>
                     <span class="fw-bold text-dark">{{ $voter->dob }}</span>
                </div>
            </div>
        </div>
        <!-- Decorative element -->
        <div class="position-absolute top-0 end-0 h-100 w-25 bg-white opacity-25" style="transform: skewX(-20deg) translateX(50%);"></div>
    </div>

    @if($voter->status !== 'approved')
        <div class="alert alert-warning d-flex align-items-center shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill fs-2 me-3"></i>
            <div>
                <h5 class="alert-heading">Verification Pending</h5>
                <p class="mb-0">Your registration is currently under review by the Booth Level Officer (BLO) of <strong>{{ $panchayat->name }}</strong>. You will be able to vote once approved.</p>
            </div>
        </div>
    @else
        <!-- Election Status Banner -->
        @if(!$isElectionActive)
            <div class="alert alert-secondary d-flex align-items-center shadow-sm mb-4" role="alert">
                <i class="bi bi-calendar-event fs-2 me-3"></i>
                <div>
                    <h5 class="alert-heading">Election Not Active</h5>
                    <p class="mb-0">Voting lines are currently closed. You can view candidate details below, or check the <a href="{{ route('results') }}" class="alert-link fw-bold text-decoration-underline">Election Results</a>.</p>
                </div>
            </div>

            {{-- ✅ Results Section (Prominent Winner Card) --}}
            @if($winningCandidate)
                <div class="card border-0 shadow mb-5 overflow-hidden">
                    <div class="card-header bg-gradient-success text-white py-3 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 fw-bold"><i class="bi bi-trophy-fill me-2"></i>Election Outcome</h4>
                            <span class="badge bg-white text-success px-3 py-2 rounded-pill fw-bold">PUBLISHED RESULT</span>
                        </div>
                    </div>
                    <div class="card-body p-4 p-md-5">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center mb-4 mb-md-0">
                                @if($winningCandidate->photo)
                                    <div class="rounded-circle d-inline-block shadow-sm border border-4 border-white mb-3" style="width: 140px; height: 140px; overflow: hidden; box-shadow: 0 0 20px rgba(0,0,0,0.1)!important;">
                                        <img src="{{ asset('storage/' . $winningCandidate->photo) }}" alt="Winner Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="bg-light rounded-circle p-4 d-inline-block text-success fw-bold display-3 shadow-sm border border-4 border-white mb-3" style="width: 140px; height: 140px; line-height: 80px; box-shadow: 0 0 20px rgba(0,0,0,0.1)!important;">
                                        {{ substr($winningCandidate->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="badge bg-success py-2 px-3 fw-bold"><i class="bi bi-star-fill me-1"></i> ELECTED WINNER</div>
                            </div>
                            <div class="col-md-9">
                                <h2 class="fw-bold mb-1">{{ $winningCandidate->user->name }}</h2>
                                <p class="lead text-muted mb-4">Elected as the Representative for {{ $panchayat->name }}</p>
                                
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="p-3 bg-light rounded text-center border">
                                            <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 0.7rem;">Votes Received</small>
                                            <h3 class="fw-bold mb-0">{{ $winningCandidate->votes_count }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                         <div class="p-3 bg-light rounded border h-100">
                                            <div class="d-flex justify-content-between mb-2">
                                                <small class="fw-bold">Vote Breakdown</small>
                                                <small class="text-muted">{{ $winningCandidate->votes_count }} / Total</small>
                                            </div>
                                            <div class="progress" style="height: 12px; border-radius: 10px;">
                                                <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @elseif($voter->has_voted)
            <div class="alert alert-success d-flex align-items-center shadow-sm mb-4" role="alert">
                <i class="bi bi-check-circle-fill fs-2 me-3"></i>
                <div>
                    <h5 class="alert-heading">Vote Cast Successfully!</h5>
                    <p class="mb-0">Thank you for voting. You can view the results <a href="{{ route('results') }}" class="alert-link">here</a>.</p>
                </div>
            </div>
        @else
            <!-- Active Voting Banner -->
             <div class="alert alert-primary d-flex align-items-center shadow-sm mb-4" role="alert">
                <i class="bi bi-box-seam fs-2 me-3"></i>
                <div>
                    <h5 class="alert-heading">Election Live!</h5>
                    <p class="mb-0">Please select your preferred candidate below and click "Cast My Vote".</p>
                </div>
            </div>
        @endif

        <!-- Candidate List Area -->
        <h3 class="fw-bold mb-4 ps-2 border-start border-primary border-5">Candidates</h3>
        
        <form action="{{ route('vote.post') }}" method="POST" id="voteForm">
            @csrf
            <input type="hidden" name="captured_photo" id="captured_photo_input">
            <div class="row g-4 mb-4">
                @forelse($candidates as $candidate)
                    <div class="col-md-6">
                        <!-- Only enable interaction if Active AND !Voted -->
                        @php $canVote = $isElectionActive && !$voter->has_voted; @endphp
                        
                        <label class="card h-100 border-2 vote-card position-relative selection-card {{ $canVote ? 'cursor-pointer' : 'opacity-75' }}">
                            @if($canVote)
                                <input type="radio" name="candidate_id" value="{{ $candidate->id }}" class="d-none peer" required>
                            @endif
                            
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    @if($candidate->photo)
                                        <div class="rounded-circle me-3 shadow-sm border overflow-hidden" style="width: 60px; height: 60px;">
                                            <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Candidate Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="bg-light rounded-circle p-3 me-3 text-primary fw-bold fs-4 shadow-sm border" style="width: 60px; height: 60px; display:flex; align-items:center; justify-content:center;">
                                            {{ substr($candidate->user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <h5 class="card-title fw-bold mb-0">{{ $candidate->user->name }}</h5>
                                        <span class="badge bg-secondary bg-opacity-10 text-primary border me-1">{{ $candidate->qualification }}</span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border">Independent Candidate</span>
                                    </div>
                                    <div class="ms-auto">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#candidateModal{{ $candidate->id }}" onclick="{{ $canVote ? 'event.stopPropagation();' : '' }}">
                                            <i class="bi bi-info-circle me-1"></i>View Profile
                                        </button>
                                    </div>
                                    @if($canVote)
                                        <div class="check-icon d-none ms-2">
                                            <i class="bi bi-check-circle-fill text-primary fs-3"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="bg-light p-3 rounded small text-muted fst-italic">
                                    "{{ Str::limit($candidate->manifesto, 100) }}"
                                </div>
                            </div>
                        </label>

                        <!-- Candidate Details Modal -->
                        <div class="modal fade" id="candidateModal{{ $candidate->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title fw-bold"><i class="bi bi-person-badge me-2"></i>Candidate Profile</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-4">
                                        <div class="text-center mb-4">
                                            @if($candidate->photo)
                                                <div class="rounded-circle d-inline-block shadow-sm border mb-3 overflow-hidden" style="width: 100px; height: 100px;">
                                                    <img src="{{ asset('storage/' . $candidate->photo) }}" alt="Candidate Photo" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            @else
                                                <div class="bg-light rounded-circle p-4 d-inline-block text-primary fw-bold fs-1 shadow-sm border mb-3" style="width: 100px; height: 100px; line-height: 50px;">
                                                    {{ substr($candidate->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <h4 class="fw-bold mb-0">{{ $candidate->user->name }}</h4>
                                            <p class="text-muted small">Candidate ID: <code class="bg-light px-2 rounded">{{ $candidate->candidate_id }}</code></p>
                                        </div>
                                        
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Education</small>
                                                <p class="fw-bold mb-0">{{ $candidate->qualification }}</p>
                                            </div>
                                            <div class="col-6">
                                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Gender</small>
                                                <p class="fw-bold mb-0">{{ $candidate->gender }}</p>
                                            </div>
                                            <div class="col-12 py-2">
                                                <hr class="my-0 opacity-10">
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Contact Information</small>
                                                <div class="d-flex align-items-center mb-2 mt-1">
                                                    <i class="bi bi-envelope text-primary me-2"></i> {{ $candidate->user->email }}
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="bi bi-telephone text-primary me-2"></i> {{ $candidate->mobile }}
                                                </div>
                                            </div>
                                            <div class="col-12 py-2">
                                                <hr class="my-0 opacity-10">
                                            </div>
                                            <div class="col-12">
                                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Full Manifesto</small>
                                                <div class="p-3 bg-light rounded border-start border-primary border-4 fst-italic small">
                                                    "{{ $candidate->manifesto }}"
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light border-0">
                                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-secondary">No candidates have been approved for this panchayat yet.</div>
                    </div>
                @endforelse
            </div>

            @if($isElectionActive && !$voter->has_voted && $candidates->count() > 0)
                <div class="d-grid col-md-6 mx-auto">
                    <button type="button" class="btn btn-primary btn-lg py-3 shadow-lg" id="btnCastVote">
                        <i class="bi bi-camera-fill me-2"></i> Verify & Cast Vote
                    </button>
                </div>
            @endif
        </form>

        <!-- Photo Capture Modal -->
        <div class="modal fade" id="cameraModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title fw-bold" id="cameraModalLabel"><i class="bi bi-person-check me-2"></i>Voter Verification</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" id="btnCloseCamera"></button>
                    </div>
                    <div class="modal-body p-4 text-center">
                        <!-- Step 1: Preparation Instructions -->
                        <div id="preparation-step">
                            <div class="mb-4">
                                <i class="bi bi-camera-fill text-primary" style="font-size: 3rem;"></i>
                                <h4 class="fw-bold mt-2">Almost Ready!</h4>
                                <p class="text-muted">A live photo is required to verify your identity before casting your vote.</p>
                            </div>
                            
                            <div class="bg-light p-3 rounded mb-4 text-start shadow-sm border border-primary border-opacity-25">
                                <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle-fill me-2"></i>Instructions for Voter:</h6>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-white rounded-circle p-2 shadow-sm me-3 flex-shrink-0">
                                        <i class="bi bi-person-square text-primary"></i>
                                    </div>
                                    <p class="mb-0 small"><strong>Sit properly and keep your posture straight.</strong> Ensure you are facing the camera directly.</p>
                                </div>
                                <div class="d-flex align-items-start mb-3">
                                    <div class="bg-white rounded-circle p-2 shadow-sm me-3 flex-shrink-0">
                                        <i class="bi bi-brightness-high text-primary"></i>
                                    </div>
                                    <p class="mb-0 small">Make sure you are in a <strong>well-lit area</strong>. Low light may result in verification failure.</p>
                                </div>
                                <div class="d-flex align-items-start">
                                    <div class="bg-white rounded-circle p-2 shadow-sm me-3 flex-shrink-0">
                                        <i class="bi bi-shield-check text-primary"></i>
                                    </div>
                                    <p class="mb-0 small text-muted">Privacy Note: This photo is securely stored with your record for verification but is <strong>never</strong> linked to your actual vote.</p>
                                </div>
                            </div>

                            <button type="button" id="btnReady" class="btn btn-primary btn-lg rounded-pill px-5 shadow-sm mb-2 w-100">
                                <i class="bi bi-camera-video me-2"></i>I am Ready - Open Camera
                            </button>
                            <p class="text-muted small mt-2">By clicking, you permit access to your camera for verification.</p>
                        </div>

                        <!-- Step 2: Camera & Preview -->
                        <div id="camera-step" class="d-none">
                            <div id="camera-alert" class="alert alert-info py-2 px-3 mb-3 small d-flex align-items-center">
                                <i class="bi bi-megaphone-fill me-2 fs-5"></i>
                                <span><strong>Reminder:</strong> Please sit straight and look at the camera.</span>
                            </div>
                            
                            <div id="camera-container" class="position-relative bg-light rounded shadow-inner overflow-hidden mb-3 border border-primary border-2" style="aspect-ratio: 4/3; max-width: 400px; margin: 0 auto;">
                                <video id="video" class="w-100 h-100" autoplay playsinline style="object-fit: cover;"></video>
                                <canvas id="canvas" class="d-none"></canvas>
                                <div id="camera-loading" class="position-absolute top-50 start-50 translate-middle text-primary">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div id="photo-overlay" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center opacity-25" style="pointer-events: none;">
                                    <i class="bi bi-person-bounding-box" style="font-size: 8rem; color: rgba(13, 110, 253, 0.5);"></i>
                                </div>
                            </div>

                            <div id="photo-preview-container" class="d-none mb-3">
                                <img id="photo-preview" class="img-fluid rounded shadow border border-success border-2 mx-auto d-block" style="max-width: 400px;" alt="Captured Photo">
                                <div class="alert alert-success mt-2 py-1 px-3 small d-inline-block">
                                    <i class="bi bi-check-circle-fill me-1"></i>Photo Captured Successfully!
                                </div>
                            </div>

                            <div class="d-flex flex-column gap-2" style="max-width: 400px; margin: 0 auto;">
                                <button type="button" id="btnCapture" class="btn btn-primary btn-lg rounded-pill px-4 shadow-sm w-100">
                                    <i class="bi bi-camera-fill me-2"></i>Capture Photo
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" id="btnRetake" class="btn btn-outline-secondary rounded-pill px-4 d-none shadow-sm flex-fill">
                                        <i class="bi bi-arrow-clockwise me-2"></i>Retake
                                    </button>
                                    <button type="button" id="btnSubmitVote" class="btn btn-success rounded-pill px-5 d-none shadow-lg fw-bold flex-fill">
                                        <i class="bi bi-check-circle-fill me-2"></i>Cast My Vote
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const btnCastVote = document.getElementById('btnCastVote');
                const btnReady = document.getElementById('btnReady');
                const btnCapture = document.getElementById('btnCapture');
                const btnRetake = document.getElementById('btnRetake');
                const btnSubmitVote = document.getElementById('btnSubmitVote');
                const btnCloseCamera = document.getElementById('btnCloseCamera');
                
                const preparationStep = document.getElementById('preparation-step');
                const cameraStep = document.getElementById('camera-step');
                const video = document.getElementById('video');
                const canvas = document.getElementById('canvas');
                const photoPreview = document.getElementById('photo-preview');
                const photoPreviewContainer = document.getElementById('photo-preview-container');
                const cameraContainer = document.getElementById('camera-container');
                const photoInput = document.getElementById('captured_photo_input');
                const cameraLoading = document.getElementById('camera-loading');
                const voteForm = document.getElementById('voteForm');
                
                const cameraModal = new bootstrap.Modal(document.getElementById('cameraModal'));
                let stream = null;

                if (btnCastVote) {
                    btnCastVote.addEventListener('click', function() {
                        // Check if candidate is selected
                        const selectedCandidate = document.querySelector('input[name="candidate_id"]:checked');
                        if (!selectedCandidate) {
                            alert('Please select a candidate first.');
                            return;
                        }
                        
                        // Reset Modal View
                        resetModal();
                        cameraModal.show();
                    });
                }

                if (btnReady) {
                    btnReady.addEventListener('click', function() {
                        preparationStep.classList.add('d-none');
                        cameraStep.classList.remove('d-none');
                        startCamera();
                    });
                }

                function resetModal() {
                    stopCamera();
                    preparationStep.classList.remove('d-none');
                    cameraStep.classList.add('d-none');
                    cameraContainer.classList.remove('d-none');
                    photoPreviewContainer.classList.add('d-none');
                    btnCapture.classList.remove('d-none');
                    btnRetake.classList.add('d-none');
                    btnSubmitVote.classList.add('d-none');
                    photoInput.value = '';
                }

                async function startCamera() {
                    cameraLoading.classList.remove('d-none');
                    try {
                        stream = await navigator.mediaDevices.getUserMedia({ 
                            video: { 
                                facingMode: "user",
                                width: { ideal: 640 },
                                height: { ideal: 480 }
                            } 
                        });
                        video.srcObject = stream;
                        
                        // Wait for video to be ready
                        video.onloadedmetadata = function() {
                            cameraLoading.classList.add('d-none');
                            video.play();
                        };
                    } catch (err) {
                        console.error('Error accessing camera:', err);
                        alert('Could not access camera. Please ensure you have given permission in your browser settings.');
                        cameraModal.hide();
                    }
                }

                function stopCamera() {
                    if (stream) {
                        stream.getTracks().forEach(track => track.stop());
                        stream = null;
                        video.srcObject = null;
                    }
                }

                btnCapture.addEventListener('click', function() {
                    if (video.readyState < 2) {
                        alert('Camera is not ready yet. Please wait a moment.');
                        return;
                    }

                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    
                    // Simple validation: check if video dimensions are captured
                    if (canvas.width === 0 || canvas.height === 0) {
                        alert('Error capturing photo. Please try again or refresh the page.');
                        return;
                    }

                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    const imageData = canvas.toDataURL('image/jpeg', 0.8); // 0.8 quality for smaller size
                    photoPreview.src = imageData;
                    photoInput.value = imageData;
                    
                    // Toggle UI
                    cameraContainer.classList.add('d-none');
                    photoPreviewContainer.classList.remove('d-none');
                    btnCapture.classList.add('d-none');
                    btnRetake.classList.remove('d-none');
                    btnSubmitVote.classList.remove('d-none');
                    
                    // Hide the reminder alert
                    document.getElementById('camera-alert').classList.add('d-none');
                    
                    stopCamera();
                });

                btnRetake.addEventListener('click', function() {
                    cameraContainer.classList.remove('d-none');
                    photoPreviewContainer.classList.add('d-none');
                    btnCapture.classList.remove('d-none');
                    btnRetake.classList.add('d-none');
                    btnSubmitVote.classList.add('d-none');
                    document.getElementById('camera-alert').classList.remove('d-none');
                    startCamera();
                });

                btnSubmitVote.addEventListener('click', function() {
                    if (!photoInput.value) {
                        alert('Error: No photo captured. Please retake the photo.');
                        btnRetake.click();
                        return;
                    }
                    
                    if (confirm('Are you sure you want to cast this vote? This action cannot be undone.')) {
                        // Change button text to show processing
                        btnSubmitVote.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Casting Vote...';
                        btnSubmitVote.disabled = true;
                        voteForm.submit();
                    }
                });

                document.getElementById('cameraModal').addEventListener('hidden.bs.modal', function() {
                    stopCamera();
                });

                if (btnCloseCamera) {
                    btnCloseCamera.addEventListener('click', stopCamera);
                }
            });
        </script>
        @endpush

        <style>
            .vote-card.cursor-pointer:hover {
                border-color: #dee2e6;
                background-color: #f8f9fa;
                cursor: pointer;
            }
            .peer:checked + .card-body {
                background-color: #f0f9ff;
            }
            .peer:checked ~ .card-body .check-icon {
                display: block !important;
            }
            .selection-card {
                transition: transform 0.2s;
            }
            .selection-card.cursor-pointer:hover {
                transform: translateY(-5px);
            }
            .peer:checked + div {
                border-color: #0d6efd !important;
                box-shadow: 0 0 0 2px #0d6efd;
            }
        </style>
    @endif
</div>
@endsection
