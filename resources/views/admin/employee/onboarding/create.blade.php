@extends('layouts.master')

@section('title')
    Add Employee | Onboarding
@endsection

@section('content')
<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Onboarding</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Add Employee</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Onboarding - New Employee</h4>
            <p class="text-muted">
                Enter the basic details to begin the onboarding process.
            </p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('onboarding.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <!-- Left Column: Basic Information -->
                <div class="col-lg-7 mb-4 pe-lg-5">
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fs-12 fw-semibold">Employee Name <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="first_name" value="{{ old('first_name') }}" required placeholder="First Name">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="middle_name" value="{{ old('middle_name') }}" placeholder="Middle Name">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="last_name" value="{{ old('last_name') }}" placeholder="Last Name">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted fs-12 fw-semibold">Joining Date <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="joining_date" value="{{ old('joining_date', now()->format('Y-m-d')) }}" required>
                                <span class="input-group-text bg-white"><i class="ri-calendar-event-line"></i></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fs-12 fw-semibold">Confirmation Date</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="confirmation_date" value="{{ old('confirmation_date') }}">
                                <span class="input-group-text bg-white"><i class="ri-calendar-event-line"></i></span>
                            </div>
                            <small class="text-muted" style="font-size: 10px;"><i class="ri-information-line"></i> If not set, default probation period will be used to calculate confirmation date.</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fs-12 fw-semibold">Date of Birth</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="dob" value="{{ old('dob') }}">
                                <span class="input-group-text bg-white"><i class="ri-calendar-event-line"></i></span>
                            </div>
                            <small class="text-muted" style="font-size: 10px;">Optional but recommended</small>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fs-12 fw-semibold d-block">Gender <span class="text-danger">*</span></label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" {{ old('gender') == 'Male' ? 'checked' : '' }} required>
                            <label class="form-check-label fs-13" for="genderMale">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female" {{ old('gender') == 'Female' ? 'checked' : '' }}>
                            <label class="form-check-label fs-13" for="genderFemale">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderTrans" value="Transgender" {{ old('gender') == 'Transgender' ? 'checked' : '' }}>
                            <label class="form-check-label fs-13" for="genderTrans">Transgender</label>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-12 fw-semibold">Employee Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="employee_code" value="{{ old('employee_code') }}" required>
                            <small class="text-muted" style="font-size: 10px;">Will be auto-generated</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-12 fw-semibold">Biometric Code <i class="ri-information-line text-muted"></i></label>
                            <input type="text" class="form-control" name="biometric_code" value="{{ old('biometric_code') }}">
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-12 fw-semibold">Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                            <small class="text-muted" style="font-size: 10px;">Enter 10-digits only</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fs-12 fw-semibold">Official E-Mail</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label text-dark fs-14 fw-bold d-block">Employee Self-Service Access</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="sendMobileLogin" name="send_mobile_login" checked>
                            <label class="form-check-label text-primary fs-13 fw-semibold" for="sendMobileLogin">Send Mobile Login</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="sendWebLogin" name="send_web_login" checked>
                            <label class="form-check-label text-primary fs-13 fw-semibold" for="sendWebLogin">Send Web Login</label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn text-white px-4 py-2 shadow-sm rounded-1" style="background-color: #162d50;"><i class="ri-save-line me-1"></i> Save</button>
                    </div>
                </div>

                <!-- Right Column: Work Profile & Policies -->
                <div class="col-lg-5 mb-4">
                    <div class="border rounded-2 p-3 mb-4 bg-white shadow-none">
                        <h6 class="mb-1 text-dark fs-13 fw-bold">Work Profile (Optional)</h6>
                        <p class="text-muted fs-11 mb-3">Select work profile for this employee. If you do not select these values now, system will assign default values, which you can edit later.</p>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted fs-12 fw-semibold">Business Unit</label>
                            <select name="business_unit" class="form-select text-muted">
                                <option value="">- Select -</option>
                                @if(isset($businessUnits))
                                    @foreach($businessUnits as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->unit_name ?? $unit->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted fs-12 fw-semibold mb-1">Location</label>
                            <select class="form-select form-select-sm" name="location">
                                <option value="">- Select -</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ old('location') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted fs-12 fw-semibold mb-1">Cost Center</label>
                            <select class="form-select form-select-sm" name="cost_center">
                                <option value="">- Select -</option>
                                @foreach($costCenters as $cc)
                                    <option value="{{ $cc->id }}" {{ old('cost_center') == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted fs-12 fw-semibold mb-1">Department</label>
                            <select class="form-select form-select-sm" name="department">
                                <option value="">- Select -</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ old('department') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted fs-12 fw-semibold mb-1">Grade</label>
                            <select class="form-select form-select-sm" name="grade">
                                <option value="">- Select -</option>
                                @foreach($grades as $grade)
                                    <option value="{{ $grade->id }}" {{ old('grade') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-1">
                            <label class="form-label text-muted fs-12 fw-semibold mb-1">Designation</label>
                            <select class="form-select form-select-sm" name="designation">
                                <option value="">- Select -</option>
                                @foreach($designations as $designation)
                                    <option value="{{ $designation->id }}" {{ old('designation') == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="border rounded-2 p-3 bg-white shadow-none">
                        <h6 class="mb-1 text-dark fs-13 fw-bold">Policies (Optional)</h6>
                        <p class="text-muted fs-11 mb-3">Select policies applicable to this employee. If you do not select these values now, system will assign default values, which you can edit later.</p>

                        <div class="mb-3">
                            <label class="form-label text-muted fs-12 fw-semibold mb-1">Shift Policy</label>
                            <select class="form-select form-select-sm" name="shift_policy">
                                <option value="">- Select -</option>
                                @foreach($shiftPolicies as $shift)
                                    <option value="{{ $shift->id }}" {{ old('shift_policy') == $shift->id ? 'selected' : '' }}>{{ $shift->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-1">
                            <label class="form-label text-muted fs-12 fw-semibold mb-1">Week Off Policy</label>
                            <select class="form-select form-select-sm" name="week_off_policy">
                                <option value="">- Select -</option>
                                @foreach($weekOffPolicies as $wo)
                                    <option value="{{ $wo->id }}" {{ old('week_off_policy') == $wo->id ? 'selected' : '' }}>{{ $wo->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
