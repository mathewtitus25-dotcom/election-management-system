@extends('layouts.app')

@section('page_title', 'Register')
@section('page_subtitle', 'Register and continue to OTP verification.')

@section('content')
<div class="page-shell">
    @include('layouts.navtabs')

    <div class="split-grid">
        <section class="page-hero" data-reveal>
            <div class="page-hero__eyebrow">Voter Onboarding</div>
            <h1 class="page-hero__title">Register, verify by OTP, then wait for BLO approval.</h1>
            <p class="page-hero__subtitle">
                Fill the form once. The next step is OTP verification.
            </p>

            <div class="hero-kpis">
                <span class="info-chip"><i class="bi bi-envelope-check"></i> OTP goes to your email</span>
                <span class="info-chip"><i class="bi bi-shield-check"></i> BLO approval is required</span>
                <span class="info-chip"><i class="bi bi-clock-history"></i> 2-step flow</span>
            </div>
        </section>

        <section class="surface-card surface-card--padded content-auto" data-reveal data-reveal-delay="80">
            <div class="form-intro mb-4">
                <div class="muted-label">Progress</div>
                <div class="form-progress">
                    <span class="form-progress__step">
                        <span class="step-marker">1</span>
                        Registration
                    </span>
                    <span class="form-progress__line" style="--progress-width: 52%;"></span>
                    <span class="helper-copy fw-semibold">OTP Verification</span>
                </div>
            </div>

            <form action="{{ route('register.post') }}" method="POST" class="form-shell prevent-double" data-pending-text="Sending OTP...">
                @csrf

                <section class="form-section">
                    <div class="form-section__header">
                        <span class="step-marker">1</span>
                        <div>
                            <h2 class="form-section__title">Personal details</h2>
                            <p class="form-section__copy">Basic identity details.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control capitalize-input @error('first_name') is-invalid @enderror" required>
                            @error('first_name') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="form-control capitalize-input @error('middle_name') is-invalid @enderror" placeholder="Optional">
                            @error('middle_name') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control capitalize-input @error('last_name') is-invalid @enderror" required>
                            @error('last_name') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" class="form-control @error('dob') is-invalid @enderror" required>
                            @error('dob') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section__header">
                        <span class="step-marker">2</span>
                        <div>
                            <h2 class="form-section__title">Voter information</h2>
                            <p class="form-section__copy">Official voter details.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Voter ID Number <span class="text-danger">*</span></label>
                            <input type="text" name="voter_id_number" value="{{ old('voter_id_number') }}" class="form-control @error('voter_id_number') is-invalid @enderror" required>
                            <div class="field-hint mt-2">Use the official voter card number.</div>
                            @error('voter_id_number') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Aadhaar Number <span class="text-danger">*</span></label>
                            <input type="text" name="aadhaar_number" value="{{ old('aadhaar_number') }}" class="form-control @error('aadhaar_number') is-invalid @enderror" required maxlength="16" minlength="16" pattern="[0-9]{16}" title="Please enter exactly 16 digits">
                            @error('aadhaar_number') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel" name="mobile" value="{{ old('mobile') }}" class="form-control @error('mobile') is-invalid @enderror" required maxlength="10" minlength="10" pattern="[0-9]{10}" title="Please enter exactly 10 digits">
                            @error('mobile') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                            <div class="field-hint mt-2">Your OTP will be sent here.</div>
                            @error('email') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Select Panchayat</label>
                            <select name="panchayat_id" class="form-select @error('panchayat_id') is-invalid @enderror" required>
                                <option value="">Choose Panchayat</option>
                                @foreach($panchayats as $panchayat)
                                    <option value="{{ $panchayat->id }}" {{ old('panchayat_id') == $panchayat->id ? 'selected' : '' }}>
                                        {{ $panchayat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('panchayat_id') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section__header">
                        <span class="step-marker">3</span>
                        <div>
                            <h2 class="form-section__title">Account security</h2>
                            <p class="form-section__copy">Set your login password.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            <div class="field-hint mt-2">Use at least 8 characters.</div>
                            @error('password') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                </section>

                <button type="submit" class="btn btn-primary btn-lg w-100">
                    <i class="bi bi-envelope-paper"></i>
                    Continue to OTP Verification
                </button>
            </form>
        </section>
    </div>
</div>
@endsection
