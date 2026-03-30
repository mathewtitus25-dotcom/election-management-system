@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-5">
    <div class="col-md-5">
        <div class="card border-0 shadow-lg">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold">Welcome Back</h3>
                    <p class="text-muted small">Please select your role to login</p>
                </div>

                <!-- Role Tabs -->
                <ul class="nav nav-pills nav-justified mb-4 bg-light p-1 rounded-pill" id="loginTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active rounded-pill py-2" id="voter-tab" data-bs-toggle="pill" data-bs-target="#voter" type="button" role="tab" onclick="setRole('voter')">Voter</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill py-2" id="candidate-tab" data-bs-toggle="pill" data-bs-target="#candidate" type="button" role="tab" onclick="setRole('candidate')">Candidate</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link rounded-pill py-2" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin" type="button" role="tab" onclick="setRole('admin')">Admin/BLO</button>
                    </li>
                </ul>

                {{-- Flash Messages --}}
                @if(session('error'))
                    <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
                @endif
                @if(session('success'))
                    <div class="alert alert-success py-2 small">{{ session('success') }}</div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                    @csrf
                    <input type="hidden" name="role" id="selectedRole" value="{{ old('role', 'voter') }}">
                    
                    <div class="tab-content" id="loginTabsContent">
                        <!-- Voter Shared Fields -->
                        <div class="tab-pane fade show active" id="voter" role="tabpanel">
                            <div class="mb-3">
                                <label for="login_identifier" class="form-label small fw-bold">Email or Voter ID</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control border-start-0" id="login_identifier" name="login_identifier" placeholder="Enter Email or Voter ID" required>
                                </div>
                                @error('login_identifier') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Candidate Fields -->
                        <div class="tab-pane fade" id="candidate" role="tabpanel">
                            <div class="mb-3">
                                <label for="candidate_email" class="form-label small fw-bold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control border-start-0" id="candidate_email" name="candidate_email" placeholder="Enter Email">
                                </div>
                                @error('candidate_email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Admin/BLO Fields -->
                        <div class="tab-pane fade" id="admin" role="tabpanel">
                            <div class="mb-3">
                                <label for="admin_email" class="form-label small fw-bold">Login Email</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-shield-lock"></i></span>
                                    <input type="email" class="form-control border-start-0" id="admin_email" name="admin_email" placeholder="Enter Admin/BLO Email">
                                </div>
                                @error('admin_email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Common Password Field -->
                    <div class="mb-3">
                        <label for="password" class="form-label small fw-bold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-key"></i></span>
                            <input type="password" class="form-control border-start-0 border-end-0" id="password" name="password" placeholder="Enter Password" required>
                            <span class="input-group-text bg-white border-start-0 cursor-pointer" onclick="togglePassword()" style="cursor: pointer;">
                                <i class="bi bi-eye text-muted" id="passwordToggleIcon"></i>
                            </span>
                        </div>
                        @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Remember me</label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fw-bold" id="submitBtn">
                        Sign In <i class="bi bi-arrow-right ms-1"></i>
                    </button>
                </form>

                <div class="text-center mt-4" id="registerPrompt">
                    <p class="text-muted small mb-0">Don't have an account? <a href="{{ route('register') }}" id="registerLink" class="text-primary fw-bold text-decoration-none">Register</a></p>
                    <hr class="my-4 opacity-10">
                    <a href="/" class="text-muted small text-decoration-none"><i class="bi bi-house me-1"></i> Back to Home</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setRole(role) {
        document.getElementById('selectedRole').value = role;
        
        // Update Registration Link based on role
        const registerLink = document.getElementById('registerLink');
        const registerPrompt = document.getElementById('registerPrompt');
        
        if (role === 'voter') {
            registerLink.href = "{{ route('register') }}";
            registerPrompt.style.display = 'block';
            registerLink.parentElement.style.display = 'block';
        } else if (role === 'candidate') {
            registerLink.href = "{{ route('candidate.register') }}";
            registerPrompt.style.display = 'block';
            registerLink.parentElement.style.display = 'block';
        } else {
            // Hide registration option for Admin/BLO as they are created by system
            registerPrompt.style.display = 'block'; // Keep prompt for home link
            registerLink.parentElement.style.display = 'none'; // Hide "Don't have an account" text
        }

        // Reset required attributes based on role
        const loginIdentifier = document.getElementById('login_identifier');
        const candidateEmail = document.getElementById('candidate_email');
        const adminEmail = document.getElementById('admin_email');

        if (role === 'voter') {
            loginIdentifier.required = true;
            candidateEmail.required = false;
            adminEmail.required = false;
        } else if (role === 'candidate') {
            loginIdentifier.required = false;
            candidateEmail.required = true;
            adminEmail.required = false;
        } else {
            loginIdentifier.required = false;
            candidateEmail.required = false;
            adminEmail.required = true;
        }
    }

    function togglePassword() {
        const passwordField = document.getElementById('password');
        const icon = document.getElementById('passwordToggleIcon');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    }

    // Handle initial state on page load — restore tab after validation error
    window.addEventListener('load', () => {
        const initialRole = "{{ old('role', 'voter') }}";

        // Activate the correct Bootstrap pill tab
        const tabMap = {
            'voter':     'voter-tab',
            'candidate': 'candidate-tab',
            'admin':     'admin-tab',
        };
        const tabId = tabMap[initialRole] || 'voter-tab';
        const tabEl = document.getElementById(tabId);
        if (tabEl) {
            // Use Bootstrap Tab API to activate the correct tab
            const bsTab = new bootstrap.Tab(tabEl);
            bsTab.show();
        }

        // Sync the hidden role field and required attributes
        setRole(initialRole);
    });

    // Add loading state to button on submit
    document.getElementById('loginForm').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Signing In...';
    });
</script>

<style>
    .cursor-pointer { cursor: pointer; }
    .nav-pills .nav-link { color: #6c757d; font-weight: 500; transition: all 0.3s; }
    .nav-pills .nav-link.active { background-color: #fff !important; color: #0d6efd !important; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    .bg-light { background-color: #f1f5f9 !important; }
    .input-group-text { border-color: #dee2e6; }
    .form-control:focus { box-shadow: none; border-color: #0d6efd; }
    .form-control:focus + .input-group-text { border-color: #0d6efd; }
</style>
@endsection
