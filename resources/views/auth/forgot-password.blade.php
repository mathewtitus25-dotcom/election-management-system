@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow border-0">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <i class="bi bi-shield-lock-fill text-primary fs-1"></i>
                    <h3 class="fw-bold mt-2">Forgot Password</h3>
                    <p class="text-muted small">Enter your registered email to receive a reset OTP.</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('password.send') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email') }}" placeholder="you@example.com" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Send Reset OTP</button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-muted small">
                        <i class="bi bi-arrow-left me-1"></i>Back to Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
