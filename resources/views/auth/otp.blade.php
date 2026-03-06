@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h4 class="mb-0">Verify OTP</h4>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0 shadow-sm">
                    <p class="mb-1"><i class="bi bi-envelope-check me-2"></i>An OTP has been sent to <strong>{{ session('register_email') }}</strong>.</p>
                </div>

                <form action="{{ route('otp.post') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="otp" class="form-label">Enter 6-Digit OTP</label>
                        <input type="text" class="form-control text-center fs-4 font-monospace" id="otp" name="otp" maxlength="6" required placeholder="000000">
                        @error('otp') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-warning w-100">Verify Email</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
