@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="bi bi-key-fill text-success fs-1"></i>
                    <h3 class="fw-bold mt-2">Reset Password</h3>
                    <p class="text-muted small">Enter the 6-digit OTP sent to your email to set a new password.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('password.reset') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">OTP Code</label>
                        <input type="text" name="otp" class="form-control @error('otp') is-invalid @enderror"
                            placeholder="6-digit OTP" maxlength="6" required>
                        @error('otp')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Minimum 8 characters" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Repeat password" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success btn-lg">Reset Password</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}" class="text-muted small">
                        <i class="bi bi-arrow-left me-1"></i>Request new OTP
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
