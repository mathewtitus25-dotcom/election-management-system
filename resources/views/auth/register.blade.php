@extends('layouts.app')

@section('content')

@include('layouts.navtabs')

<style>
    /* Progress bar */
    .progress-step {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        font-weight: 600;
        color: #4f46e5;
    }

    .progress-line {
        flex: 1;
        height: 3px;
        background: #e5e7eb;
        border-radius: 999px;
        position: relative;
    }

    .progress-line::after {
        content: '';
        position: absolute;
        width: 40%;
        height: 100%;
        background: #4f46e5;
        border-radius: 999px;
    }

    /* Error highlight */
    .is-error {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 0.15rem rgba(220,38,38,0.25);
    }

    /* Input focus */
    .form-control:focus,
    .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 0.2rem rgba(79,70,229,0.25);
    }
</style>

<div class="row justify-content-center mt-4 mb-5">
    <div class="col-lg-8 col-md-10">

        <div class="card shadow rounded-3">

            <!-- Header -->
            <div class="card-header"
                 style="background: linear-gradient(90deg,#4a90e2,#6fb1fc); color:#fff;">
                <h4 class="mb-1">Voter Registration</h4>
                <small class="opacity-75">
                    Complete the form to register and verify your email
                </small>
            </div>

            <!-- Progress Indicator -->
            <div class="card-body pb-0">
                <div class="progress-step mb-4">
                    <span>Step 1: Registration</span>
                    <div class="progress-line"></div>
                    <span class="text-muted">Step 2: OTP Verification</span>
                </div>
            </div>

            <!-- Form Body -->
            <div class="card-body pt-0 px-4">

                <form action="{{ route('register.post') }}" method="POST">
                    @csrf

                    <!-- SECTION 1: PERSONAL DETAILS -->
                    <h6 class="fw-bold text-primary mb-3 mt-2">
                         Personal Details
                    </h6>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">First Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="first_name"
                                   value="{{ old('first_name') }}"
                                   class="form-control capitalize-input @error('first_name') is-error @enderror"
                                   required>
                            @error('first_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Middle Name</label>
                            <input type="text"
                                   name="middle_name"
                                   value="{{ old('middle_name') }}"
                                   class="form-control capitalize-input @error('middle_name') is-error @enderror"
                                   placeholder="Optional">
                            @error('middle_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Last Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="last_name"
                                   value="{{ old('last_name') }}"
                                   class="form-control capitalize-input @error('last_name') is-error @enderror"
                                   required>
                            @error('last_name')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Date of Birth</label>
                            <input type="date"
                                   name="dob"
                                   value="{{ old('dob') }}"
                                   class="form-control @error('dob') is-error @enderror"
                                   required>
                            @error('dob')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- SECTION 2: VOTER INFORMATION -->
                    <h6 class="fw-bold text-primary mt-4 mb-3">
                         Voter Information
                    </h6>

                    <div class="mb-3">
                        <label class="form-label">Voter ID Number <span class="text-danger">*</span></label>
                        <input type="text"
                               name="voter_id_number"
                               value="{{ old('voter_id_number') }}"
                               class="form-control @error('voter_id_number') is-error @enderror"
                               required>
                        <small class="text-muted">
                            As per your official voter identification
                        </small>
                        @error('voter_id_number')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Aadhaar Number <span class="text-danger">*</span></label>
                            <input type="text"
                                   name="aadhaar_number"
                                   value="{{ old('aadhaar_number') }}"
                                   class="form-control @error('aadhaar_number') is-error @enderror"
                                   required
                                   maxlength="16"
                                   minlength="16"
                                   pattern="[0-9]{16}"
                                   title="Please enter exactly 16 digits">
                            @error('aadhaar_number')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                            <input type="tel"
                                   name="mobile"
                                   value="{{ old('mobile') }}"
                                   class="form-control @error('mobile') is-error @enderror"
                                   required
                                   maxlength="10"
                                   minlength="10"
                                   pattern="[0-9]{10}"
                                   title="Please enter exactly 10 digits">
                            @error('mobile')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email Address</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               class="form-control @error('email') is-error @enderror"
                               required>
                        <small class="text-muted">
                            An OTP will be sent to this email for verification
                        </small>
                        @error('email')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Panchayat</label>
                        <select name="panchayat_id"
                                class="form-select @error('panchayat_id') is-error @enderror"
                                required>
                            <option value="">-- Select Panchayat --</option>
                            @foreach($panchayats as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('panchayat_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('panchayat_id')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- SECTION 3: SECURITY -->
                    <h6 class="fw-bold text-primary mt-4 mb-3">
                         Security
                    </h6>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
                                   name="password"
                                   class="form-control @error('password') is-error @enderror"
                                   required>
                            <small class="text-muted">
                                Minimum 8 characters recommended
                            </small>
                            @error('password')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   required>
                        </div>
                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button type="submit"
                            class="w-100 mt-2"
                            style="
                                background: linear-gradient(90deg,#4f46e5,#818cf8);
                                color:#fff;
                                border:none;
                                padding:14px;
                                border-radius:10px;
                                font-weight:600;
                                transition:0.25s;
                            "
                            onmouseover="this.style.opacity='0.9'"
                            onmouseout="this.style.opacity='1'">
                        Continue to OTP Verification
                    </button>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
