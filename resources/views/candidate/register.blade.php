@extends('layouts.app')

@section('content')
@include('layouts.navtabs')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-primary text-white p-4 text-center">
                <h3 class="mb-0 fw-bold"><i class="bi bi-person-badge me-2"></i>Candidate Application Form</h3>
                <p class="mb-0 opacity-75">Submit your details to contest in the upcoming Panchayat Elections.</p>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('candidate.register.post') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Section 1: Basic Personal Information -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">1</div>
                            <h5 class="text-primary fw-bold mb-0">Basic Personal Information</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">First Name <span class="text-danger">*</span></label>
                                <input type="text" name="first_name" class="form-control capitalize-input" placeholder="First name" required value="{{ old('first_name') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control capitalize-input" placeholder="Middle name (optional)" value="{{ old('middle_name') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Last Name <span class="text-danger">*</span></label>
                                <input type="text" name="last_name" class="form-control capitalize-input" placeholder="Last name" required value="{{ old('last_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Date of Birth</label>
                                <input type="date" name="dob" class="form-control" required value="{{ old('dob') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Gender</label>
                                <select name="gender" class="form-select" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                    <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Mobile Number</label>
                                <input type="tel" name="mobile" class="form-control" placeholder="+91 XXXXX XXXXX" required value="{{ old('mobile') }}" pattern="[0-9]{10}" maxlength="10" minlength="10" title="Please enter exactly 10 digits">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Educational Qualification</label>
                                <input type="text" name="qualification" class="form-control capitalize-input" placeholder="e.g. Graduate, Post Graduate" required value="{{ old('qualification') }}">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Candidate Photo <span class="text-danger">*</span></label>
                                <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/jpg" required>
                                <small class="text-muted" style="font-size: 0.7rem;">Please upload a clear passport size photo (JPG, PNG).</small>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Identity & Eligibility -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">2</div>
                            <h5 class="text-primary fw-bold mb-0">Identity & Eligibility Details</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Voter ID Number</label>
                                <input type="text" name="voter_id" class="form-control" placeholder="ABC1234567" required value="{{ old('voter_id') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Aadhaar Number</label>
                                <input type="text" name="aadhaar" class="form-control" placeholder="16 Digit Aadhaar Number" required value="{{ old('aadhaar') }}" pattern="[0-9]{16}" maxlength="16" title="Please enter exactly 16 digits">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Residential Address</label>
                                <textarea name="address" class="form-control" rows="2" placeholder="Enter your full address" required>{{ old('address') }}</textarea>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold small">Panchayat Selection</label>
                                <select name="panchayat_id" class="form-select" required>
                                    <option value="">Select a Panchayat</option>
                                    @foreach($panchayats as $p)
                                        <option value="{{ $p->id }}" {{ old('panchayat_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Account Credentials -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">3</div>
                            <h5 class="text-primary fw-bold mb-0">Account Setup</h5>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="name@example.com" required value="{{ old('email') }}">
                                <small class="text-muted" style="font-size: 0.7rem;">Used as username for login.</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold small">Create Password</label>
                                <input type="password" name="password" class="form-control" required placeholder="Minimum 8 characters">
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Campaign Details -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 24px; height: 24px;">4</div>
                            <h5 class="text-primary fw-bold mb-0">Election Manifesto</h5>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold small">My Manifesto / Vision</label>
                            <textarea name="manifesto" class="form-control" rows="4" placeholder="Explain your vision for the Panchayat..." required>{{ old('manifesto') }}</textarea>
                            <small class="text-muted" style="font-size: 0.7rem;">Minimum 10 characters.</small>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold py-3 shadow-sm border-0">
                            <i class="bi bi-send-check-fill me-2"></i> Submit Candidate Application
                        </button>
                        <a href="{{ route('login') }}" class="btn btn-link text-muted">Already have an account? Login here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
