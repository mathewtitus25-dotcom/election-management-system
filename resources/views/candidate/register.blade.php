@extends('layouts.app')

@section('page_title', 'Candidate Apply')
@section('page_subtitle', 'Submit your nomination details.')

@section('content')
<div class="page-shell">
    @include('layouts.navtabs')

    <div class="split-grid">
        <section class="page-hero" data-reveal>
            <div class="page-hero__eyebrow">Nomination Flow</div>
            <h1 class="page-hero__title">Apply to contest in your panchayat.</h1>
            <p class="page-hero__subtitle">
                Fill in your details, eligibility, and manifesto, then submit for review.
            </p>

            <div class="hero-kpis">
                <span class="info-chip"><i class="bi bi-person-vcard"></i> Identity and eligibility</span>
                <span class="info-chip"><i class="bi bi-card-image"></i> Photo upload</span>
                <span class="info-chip"><i class="bi bi-send-check"></i> Approval email</span>
            </div>
        </section>

        <section class="surface-card surface-card--padded content-auto" data-reveal data-reveal-delay="80">
            <form action="{{ route('candidate.register.post') }}" method="POST" enctype="multipart/form-data" class="form-shell prevent-double" data-pending-text="Submitting application...">
                @csrf

                <section class="form-section">
                    <div class="form-section__header">
                        <span class="step-marker">1</span>
                        <div>
                            <h2 class="form-section__title">Basic personal information</h2>
                            <p class="form-section__copy">Basic personal details.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control capitalize-input" placeholder="First name" required value="{{ old('first_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Middle Name</label>
                            <input type="text" name="middle_name" class="form-control capitalize-input" placeholder="Optional" value="{{ old('middle_name') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control capitalize-input" placeholder="Last name" required value="{{ old('last_name') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Date of Birth</label>
                            <input type="date" name="dob" class="form-control" required value="{{ old('dob') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Mobile Number</label>
                            <input type="tel" name="mobile" class="form-control" placeholder="10 digit mobile number" required value="{{ old('mobile') }}" pattern="[0-9]{10}" maxlength="10" minlength="10" title="Please enter exactly 10 digits">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Educational Qualification</label>
                            <input type="text" name="qualification" class="form-control capitalize-input" placeholder="Graduate, Post Graduate, etc." required value="{{ old('qualification') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Candidate Photo <span class="text-danger">*</span></label>
                            <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                            <div class="field-hint mt-2">Upload a clear passport-style photo in JPG or PNG format.</div>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section__header">
                        <span class="step-marker">2</span>
                        <div>
                            <h2 class="form-section__title">Identity and eligibility</h2>
                            <p class="form-section__copy">Eligibility details.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Voter ID Number</label>
                            <input type="text" name="voter_id" class="form-control" placeholder="ABC1234567" required value="{{ old('voter_id') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Aadhaar Number</label>
                            <input type="text" name="aadhaar" class="form-control" placeholder="16 digit Aadhaar number" required value="{{ old('aadhaar') }}" pattern="[0-9]{16}" maxlength="16" title="Please enter exactly 16 digits">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Residential Address</label>
                            <textarea name="address" class="form-control" rows="3" placeholder="Enter your full address" required>{{ old('address') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold">Panchayat Selection</label>
                            <select name="panchayat_id" class="form-select" required>
                                <option value="">Select a Panchayat</option>
                                @foreach($panchayats as $panchayat)
                                    <option value="{{ $panchayat->id }}" {{ old('panchayat_id') == $panchayat->id ? 'selected' : '' }}>{{ $panchayat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section__header">
                        <span class="step-marker">3</span>
                        <div>
                            <h2 class="form-section__title">Account setup</h2>
                            <p class="form-section__copy">Set your login details.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="{{ old('email') }}">
                            <div class="field-hint mt-2">This becomes your candidate login username.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Create Password</label>
                            <input type="password" name="password" class="form-control" required placeholder="Minimum 8 characters">
                        </div>
                    </div>
                </section>

                <section class="form-section">
                    <div class="form-section__header">
                        <span class="step-marker">4</span>
                        <div>
                            <h2 class="form-section__title">Election manifesto</h2>
                            <p class="form-section__copy">Share your campaign message.</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">My Manifesto / Vision</label>
                            <textarea name="manifesto" class="form-control" rows="4" placeholder="Explain your vision for the Panchayat..." required>{{ old('manifesto') }}</textarea>
                            <div class="field-hint mt-2">Keep it specific and actionable. Minimum 10 characters.</div>
                        </div>
                    </div>
                </section>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-send-check-fill"></i>
                        Submit Candidate Application
                    </button>
                    <a href="{{ route('login') }}" class="btn btn-light">Already approved? Login here</a>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
