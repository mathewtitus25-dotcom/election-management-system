@extends('layouts.app')

@section('page_title', 'Login')
@section('page_subtitle', 'Choose a role and sign in.')

@section('content')
<div class="page-shell">
    @include('layouts.navtabs')

    <section class="auth-card" data-reveal>
        <aside class="auth-card__aside surface-card">
            <div class="page-hero__eyebrow">Access Control</div>
            <h1 class="auth-card__title">Sign in by role.</h1>
            <p class="auth-card__copy">Pick the correct workspace and continue.</p>

            <div class="auth-card__benefits">
                <div class="auth-card__benefit">
                    <strong class="d-block mb-2">Voter login</strong>
                    <span class="helper-copy">Email or voter ID.</span>
                </div>
                <div class="auth-card__benefit">
                    <strong class="d-block mb-2">Candidate login</strong>
                    <span class="helper-copy">Email and candidate key.</span>
                </div>
                <div class="auth-card__benefit">
                    <strong class="d-block mb-2">Admin/BLO login</strong>
                    <span class="helper-copy">Official email access.</span>
                </div>
            </div>
        </aside>

        <div class="auth-card__form surface-card">
            <div>
                <div class="muted-label">Welcome Back</div>
                <h2 class="mb-2">Sign in</h2>
                <p class="helper-copy mb-0">Choose a role to continue.</p>
            </div>

            <ul class="nav segmented-control" id="loginTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="voter-tab" data-bs-toggle="pill" data-bs-target="#voter" data-role-option="voter" type="button" role="tab">Voter</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="candidate-tab" data-bs-toggle="pill" data-bs-target="#candidate" data-role-option="candidate" type="button" role="tab">Candidate</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="admin-tab" data-bs-toggle="pill" data-bs-target="#admin" data-role-option="admin" type="button" role="tab">Admin/BLO</button>
                </li>
            </ul>

            <form action="{{ route('login.post') }}" method="POST" id="loginForm" class="prevent-double" data-pending-text="Signing in...">
                @csrf
                <input type="hidden" name="role" id="selectedRole" value="{{ old('role', 'voter') }}">

                <div class="tab-content" id="loginTabsContent">
                    <div class="tab-pane fade show active" id="voter" role="tabpanel" tabindex="0">
                        <div class="mb-3">
                            <label for="login_identifier" class="form-label fw-semibold">Email or Voter ID</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="login_identifier" name="login_identifier" placeholder="Enter your registered email or voter ID" value="{{ old('login_identifier') }}">
                            </div>
                            @error('login_identifier') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="tab-pane fade" id="candidate" role="tabpanel" tabindex="0">
                        <div class="mb-3">
                            <label for="candidate_email" class="form-label fw-semibold">Candidate email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="candidate_email" name="candidate_email" placeholder="name@example.com" value="{{ old('candidate_email') }}">
                            </div>
                            @error('candidate_email') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="candidate_key" class="form-label fw-semibold">Candidate key</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="text" class="form-control" id="candidate_key" name="candidate_key" placeholder="Enter the key shared after approval" value="{{ old('candidate_key') }}">
                            </div>
                            @error('candidate_key') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="tab-pane fade" id="admin" role="tabpanel" tabindex="0">
                        <div class="mb-3">
                            <label for="admin_email" class="form-label fw-semibold">Admin or BLO email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" placeholder="Enter the official login email" value="{{ old('admin_email') }}">
                            </div>
                            @error('admin_email') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        <button type="button" class="input-group-text bg-white border-start-0" id="passwordToggle" aria-label="Toggle password visibility">
                            <i class="bi bi-eye" id="passwordToggleIcon"></i>
                        </button>
                    </div>
                    @error('password') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                </div>

                <div class="auth-form__links mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-primary fw-semibold">Forgot password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-arrow-right-circle"></i>
                    Sign In
                </button>
            </form>

            <div class="auth-form__footer">
                <div id="registerPrompt">
                    <p class="mb-2" id="registerText">
                        Need access first?
                        <a href="{{ route('register') }}" id="registerLink" class="text-decoration-none text-primary fw-semibold">Create a voter account</a>
                    </p>
                    <p class="mb-0 helper-copy" id="systemOnlyNotice" hidden>Admin and BLO accounts are created through the system administrator.</p>
                </div>
                <a href="{{ route('welcome') }}" class="text-decoration-none helper-copy">
                    <i class="bi bi-arrow-left"></i>
                    Back to home
                </a>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectedRole = document.getElementById('selectedRole');
        const registerLink = document.getElementById('registerLink');
        const registerText = document.getElementById('registerText');
        const systemOnlyNotice = document.getElementById('systemOnlyNotice');
        const passwordField = document.getElementById('password');
        const passwordToggle = document.getElementById('passwordToggle');
        const passwordToggleIcon = document.getElementById('passwordToggleIcon');
        const tabMap = {
            voter: 'voter-tab',
            candidate: 'candidate-tab',
            admin: 'admin-tab',
        };

        function setRole(role) {
            selectedRole.value = role;

            const loginIdentifier = document.getElementById('login_identifier');
            const candidateEmail = document.getElementById('candidate_email');
            const candidateKey = document.getElementById('candidate_key');
            const adminEmail = document.getElementById('admin_email');

            loginIdentifier.required = role === 'voter';
            candidateEmail.required = role === 'candidate';
            candidateKey.required = role === 'candidate';
            adminEmail.required = role === 'admin';

            if (role === 'candidate') {
                registerText.hidden = false;
                systemOnlyNotice.hidden = true;
                registerLink.href = "{{ route('candidate.register') }}";
                registerLink.textContent = 'Apply as a candidate';
            } else if (role === 'admin') {
                registerText.hidden = true;
                systemOnlyNotice.hidden = false;
            } else {
                registerText.hidden = false;
                systemOnlyNotice.hidden = true;
                registerLink.href = "{{ route('register') }}";
                registerLink.textContent = 'Create a voter account';
            }
        }

        document.querySelectorAll('[data-role-option]').forEach((button) => {
            button.addEventListener('shown.bs.tab', function () {
                setRole(button.dataset.roleOption);
            });
        });

        passwordToggle.addEventListener('click', function () {
            const revealPassword = passwordField.type === 'password';
            passwordField.type = revealPassword ? 'text' : 'password';
            passwordToggleIcon.classList.toggle('bi-eye', !revealPassword);
            passwordToggleIcon.classList.toggle('bi-eye-slash', revealPassword);
        });

        const initialRole = @json(old('role', 'voter'));
        const initialTab = document.getElementById(tabMap[initialRole] || 'voter-tab');

        if (initialTab && window.bootstrap) {
            new bootstrap.Tab(initialTab).show();
        }

        setRole(initialRole);
    });
</script>
@endpush
@endsection
