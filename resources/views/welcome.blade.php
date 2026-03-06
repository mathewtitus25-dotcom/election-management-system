@extends('layouts.app')

@section('content')
@include('layouts.navtabs')
<div class="row justify-content-center">
    <div class="col-lg-10">

        <div class="tab-content" id="landingTabContent">
            <!-- Home Tab -->
            <div class="tab-pane fade show active" id="home" role="tabpanel">
                
                <!-- Hero Section -->
                <div class="text-center hero-section">
                    <img src="{{ asset('images/logo.png') }}" alt="VoteDesk Logo" class="mb-4 shadow" style="width: 120px; height: 120px; object-fit: contain; border-radius: 20px;">
                    <h1 class="display-4 fw-bold mb-3">VoteDesk</h1>
                    <p class="lead text-muted mb-4">Transparent, secure, and efficient elections</p>
                    <div class="d-flex justify-content-center gap-3">
                         @guest
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4">Login to Vote</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">Register Now</a>
                        @else
                            <a href="{{ route('voter.dashboard') }}" class="btn btn-primary btn-lg px-4">Go to Dashboard</a>
                        @endguest
                    </div>
                </div>

                <!-- Features Grid -->
                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <div class="card h-100 p-3 text-center">
                            <div class="card-body">
                                <div class="feature-icon bg-info bg-opacity-10 text-info mx-auto">
                                    <i class="bi bi-box-seam fs-4"></i>
                                </div>
                                <h5 class="card-title">Secure Voting</h5>
                                <p class="card-text text-muted small">One person, one vote policy enforced with secure authentication.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 p-3 text-center">
                            <div class="card-body">
                                <div class="feature-icon bg-primary bg-opacity-10 text-primary mx-auto">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                                <h5 class="card-title">Role-Based Access</h5>
                                <p class="card-text text-muted small">Separate portals for Admin, BLO, and Voters.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 p-3 text-center">
                            <div class="card-body">
                                <div class="feature-icon bg-success bg-opacity-10 text-success mx-auto">
                                    <i class="bi bi-shield-check fs-4"></i>
                                </div>
                                <h5 class="card-title">Approval Workflow</h5>
                                <p class="card-text text-muted small">BLO verification ensures only eligible voters participate.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card h-100 p-3 text-center">
                            <div class="card-body">
                                <div class="feature-icon bg-warning bg-opacity-10 text-warning mx-auto">
                                    <i class="bi bi-graph-up-arrow fs-4"></i>
                                </div>
                                <h5 class="card-title">Real-Time Results</h5>
                                <p class="card-text text-muted small">Live vote tallies and transparent reporting.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- How It Works -->
                <div class="mb-5">
                    <h2 class="text-center fw-bold mb-4">How It Works</h2>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-transparent">
                                <div class="card-body text-center">
                                    <div class="step-circle bg-white text-primary shadow-sm">1</div>
                                    <h5>Register</h5>
                                    <p class="text-muted">Voters register with their details and select their panchayat.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-transparent">
                                <div class="card-body text-center">
                                    <div class="step-circle bg-white text-primary shadow-sm">2</div>
                                    <h5>Approval</h5>
                                    <p class="text-muted">BLOs verify and approve voter registrations.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-transparent">
                                <div class="card-body text-center">
                                    <div class="step-circle bg-white text-primary shadow-sm">3</div>
                                    <h5>Vote</h5>
                                    <p class="text-muted">Approved voters cast their vote securely.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
