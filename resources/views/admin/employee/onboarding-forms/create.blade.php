@extends('layouts.master')

@section('title')
    New Onboarding Form
@endsection

@section('css')
<style>
    .page-header-title {
        font-size: 16px;
        font-weight: 600;
        color: #343a40;
    }
    .page-header-subtitle {
        font-size: 12px;
        color: #6c757d;
    }
    .breadcrumb-text {
        font-size: 11px;
        color: #162d50;
    }
    .coming-soon-badge {
        font-size: 10px;
        background-color: #f3f6f9;
        color: #878a99;
        padding: 2px 6px;
        border-radius: 4px;
        border: 1px solid #e9ebec;
        margin-left: 6px;
    }
    .verification-options .form-check-input:checked {
        background-color: #162d50;
        border-color: #162d50;
    }
    .part-header {
        font-size: 13px;
        font-weight: 600;
        color: #162d50;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .candidate-name {
        font-size: 14px;
        font-weight: 600;
        color: #343a40;
    }
    .candidate-contact {
        font-size: 12px;
        color: #878a99;
    }
    .btn-edit-part {
        background-color: #fbecec;
        color: #f06548;
        border: 1px solid #fad2d2;
        font-size: 12px;
        font-weight: 500;
        padding: 4px 16px;
        border-radius: 4px;
    }
    .btn-edit-part:hover {
        background-color: #f06548;
        color: white;
    }
    .form-section-title {
        font-size: 11px;
        font-weight: 500;
        color: #878a99;
        margin-bottom: 4px;
        display: block;
    }
    .time-rule-label {
        font-size: 11px;
        color: #495057;
        font-weight: 500;
    }
    .time-rule-sub {
        font-size: 10px;
        color: #f06548;
        margin-left: 20px;
        display: block;
        margin-top: -2px;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('content')

<div class="mb-4">
    <!-- Breadcrumb -->
    <div class="d-flex align-items-center mb-2">
        <i class="ri-arrow-left-s-line text-muted"></i>
        <a href="{{ route('onboarding.index') }}" class="text-decoration-none breadcrumb-text">Onboarding</a>
        <span class="mx-1 text-muted fs-12">/</span>
        <a href="{{ route('onboarding.forms.index') }}" class="text-decoration-none breadcrumb-text">Onboarding Forms</a>
        <span class="mx-1 text-muted fs-12">/</span>
        <span class="breadcrumb-text">New Onboarding Form</span>
    </div>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h4 class="page-header-title mb-1">New Onboarding Form</h4>
            <p class="page-header-subtitle mb-0">Generate and send self-onboarding form to a candidate</p>
        </div>
        
        <div>
            <button class="btn btn-sm text-white px-3 rounded-pill" style="background-color: #0ab39c; border-color: #0ab39c;">
                <i class="ri-question-line align-middle me-1"></i> Read Help
            </button>
        </div>
    </div>
</div>

@if(!$form || !isset($form->part_a_data))
<!-- PART A (Form Mode) -->
<div class="card border border-light shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="mb-3 text-dark fs-14 fw-semibold">
            <i class="ri-subtract-line text-danger align-middle fs-16 me-1"></i> Part A - Candidate Details
        </h6>
        <p class="text-muted fs-12 mb-4">Enter Candidate's details and verify PAN / Aadhaar and Mobile Number instantly.</p>
        
        <form action="{{ route('onboarding.forms.store') }}" method="POST">
            @csrf
            <input type="hidden" name="step" value="part_a">
            
            <div class="row">
                <!-- Left Column: Form Fields -->
                <div class="col-lg-7 pe-lg-4">
                    <div class="mb-3">
                        <label class="form-label text-dark fs-12 mb-1">Candidate Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" name="name" value="{{ $employee->first_name ?? '' }} {{ $employee->last_name ?? '' }}" required>
                    </div>

                    <div class="row mb-1">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fs-12 mb-1">E-Mail Address</label>
                            <input type="email" class="form-control form-control-sm" name="email" value="{{ $employee->email ?? '' }}">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fs-12 mb-1">Mobile Number</label>
                            <input type="text" class="form-control form-control-sm" name="mobile" value="{{ $employee->phone ?? '' }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted fs-11"><i class="ri-information-line"></i> Either of mobile or email is required. Great to have both.</small>
                    </div>
                    
                    <div class="row mb-1">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fs-12 mb-1">Joining Date</label>
                            <div class="input-group input-group-sm">
                                <input type="date" class="form-control" name="joining_date">
                                <span class="input-group-text bg-white"><i class="ri-calendar-event-line"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-dark fs-12 mb-1">Confirmation Date</label>
                            <div class="input-group input-group-sm">
                                <input type="date" class="form-control" name="confirmation_date">
                                <span class="input-group-text bg-white"><i class="ri-calendar-event-line"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted fs-11"><i class="ri-information-line"></i> You can set or change these dates at the time of approving the form also.</small>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-dark fs-12 mb-1">Notice Period</label>
                            <div class="input-group input-group-sm">
                                <input type="number" class="form-control" name="notice_period" value="30">
                                <span class="input-group-text bg-light text-muted border-0">days</span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label text-dark fs-12 mb-1">Date of Birth</label>
                            <div class="input-group input-group-sm">
                                <input type="date" class="form-control" name="dob">
                                <span class="input-group-text bg-white"><i class="ri-calendar-event-line"></i></span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-dark fs-12 mb-1 d-block">Gender</label>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderMale" value="Male" checked>
                            <label class="form-check-label fs-12" for="genderMale">Male</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderFemale" value="Female">
                            <label class="form-check-label fs-12" for="genderFemale">Female</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="gender" id="genderTrans" value="Transgender">
                            <label class="form-check-label fs-12" for="genderTrans">Transgender</label>
                        </div>
                    </div>

                    <div class="mt-4 pt-2">
                        <button type="submit" class="btn text-white px-4 py-1 rounded-1 shadow-sm fs-13" style="background-color: #162d50;">
                            Continue <i class="ri-arrow-right-line align-middle ms-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Right Column: Verification Options -->
                <div class="col-lg-5 ps-lg-4 border-start">
                    <div class="verification-options">
                        <h6 class="mb-3 text-dark fs-13 fw-semibold">
                            Select Verification Options <i class="ri-information-fill text-muted align-middle" style="color: #7b2745 !important;"></i>
                        </h6>
                        
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="mobile_verified" id="mobileCheck" checked>
                            <label class="form-check-label fs-12" for="mobileCheck">Mobile Verification - <span class="text-muted">FREE</span></label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="pan_verified" id="panCheck" checked>
                            <label class="form-check-label fs-12" for="panCheck" style="color: #0d6efd;">PAN Verification - 5 Credits</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="bank_verified" id="bankCheck" checked>
                            <label class="form-check-label fs-12" for="bankCheck" style="color: #0d6efd;">Bank Verification - 5 Credits</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="aadhaar_verified" id="aadhaarCheck">
                            <label class="form-check-label fs-12" for="aadhaarCheck">Aadhaar Verification - 10 Credits</label>
                        </div>
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="digitalSigCheck" disabled>
                            <label class="form-check-label fs-12 text-muted" for="digitalSigCheck">
                                Require Digital Signature <span class="coming-soon-badge">Coming Soon</span>
                            </label>
                        </div>

                        <div class="mt-5 pt-3 border-top">
                            <div class="d-flex align-items-center">
                                <span class="text-dark fs-13 me-3">Credit Balance: <strong>' 0</strong></span>
                                <a href="#" class="text-decoration-none fs-13" style="color: #162d50;">
                                    Buy Credits <i class="ri-external-link-line align-middle"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@else
<!-- PART A (Collapsed Mode) -->
<div class="card border border-light shadow-sm mb-3">
    <div class="card-body p-3 d-flex justify-content-between align-items-center">
        <div>
            <div class="part-header mb-1 text-success">
                <i class="ri-checkbox-circle-fill fs-16"></i> Part A - Candidate Details
            </div>
            <div class="ms-4 ps-1">
                <div class="candidate-name">{{ $form->part_a_data['name'] ?? 'Candidate Name' }}</div>
                <div class="candidate-contact mt-1">{{ $form->part_a_data['email'] ?? '' }} | {{ $form->part_a_data['mobile'] ?? '' }}</div>
            </div>
        </div>
        <div>
            <!-- Not functional yet, just UI -->
            <button class="btn btn-edit-part text-decoration-none">
                <i class="ri-pencil-fill align-middle me-1"></i> Edit
            </button>
        </div>
    </div>
</div>

<!-- PART B (Expanded Mode) -->
<div class="card border border-light shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="mb-2 text-dark fs-14 fw-semibold" style="color: #63779e !important;">
            <i class="ri-subtract-line align-middle fs-16 me-1 text-danger"></i> Part B - Work Profile
        </h6>
        <p class="text-muted fs-12 mb-4 ms-4">Set department, designation and other work profile options for this candidate. You can edit these details at the time of approving this form.</p>
        
        <form action="{{ route('onboarding.forms.store') }}" method="POST" class="ms-4">
            @csrf
            <input type="hidden" name="step" value="part_b">
            <input type="hidden" name="form_id" value="{{ $form->id }}">
            
            <div class="row">
                <!-- Column 1: Organization Structure -->
                <div class="col-lg-4 pe-lg-4">
                    <div class="mb-3">
                        <label class="form-section-title">Business Unit</label>
                        <select name="business_unit" class="form-select form-select-sm text-muted">
                            <option value="">- Select -</option>
                            @foreach($businessUnits as $bu)
                                <option value="{{ $bu->id }}" {{ (isset($form->part_b_data['business_unit']) && $form->part_b_data['business_unit'] == $bu->id) ? 'selected' : '' }}>
                                    {{ $bu->name ?? $bu->unit_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-section-title">Location</label>
                        <select name="location" class="form-select form-select-sm text-muted">
                            <option value="">- Select -</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc->id }}" {{ (isset($form->part_b_data['location']) && $form->part_b_data['location'] == $loc->id) ? 'selected' : '' }}>
                                    {{ $loc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-section-title">Cost Center</label>
                        <select name="cost_center" class="form-select form-select-sm text-muted">
                            <option value="">- Select -</option>
                            @foreach($costCenters as $cc)
                                <option value="{{ $cc->id }}" {{ (isset($form->part_b_data['cost_center']) && $form->part_b_data['cost_center'] == $cc->id) ? 'selected' : '' }}>
                                    {{ $cc->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-section-title">Department</label>
                        <select name="department" class="form-select form-select-sm text-muted">
                            <option value="">- Select -</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ (isset($form->part_b_data['department']) && $form->part_b_data['department'] == $dept->id) ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-section-title">Grade</label>
                        <select name="grade" class="form-select form-select-sm text-muted">
                            <option value="">- Select -</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}" {{ (isset($form->part_b_data['grade']) && $form->part_b_data['grade'] == $grade->id) ? 'selected' : '' }}>
                                    {{ $grade->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-section-title">Designation</label>
                        <select name="designation" class="form-select form-select-sm text-muted">
                            <option value="">- Select -</option>
                            @foreach($designations as $desig)
                                <option value="{{ $desig->id }}" {{ (isset($form->part_b_data['designation']) && $form->part_b_data['designation'] == $desig->id) ? 'selected' : '' }}>
                                    {{ $desig->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Column 2: Policies -->
                <div class="col-lg-4 pe-lg-4 border-start ps-lg-4">
                    <div class="mb-3">
                        <label class="form-section-title">Shift Policy</label>
                        <select name="shift_policy" class="form-select form-select-sm text-muted">
                            <option value="">- Select -</option>
                            @foreach($shiftPolicies as $shift)
                                <option value="{{ $shift->id }}" {{ (isset($form->part_b_data['shift_policy']) && $form->part_b_data['shift_policy'] == $shift->id) ? 'selected' : '' }}>
                                    {{ $shift->shift_name ?? $shift->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-section-title">Week Off Policy</label>
                        <select name="week_off_policy" class="form-select form-select-sm text-muted">
                            <option value="">- Select -</option>
                            @foreach($weekOffPolicies as $wo)
                                <option value="{{ $wo->id }}" {{ (isset($form->part_b_data['week_off_policy']) && $form->part_b_data['week_off_policy'] == $wo->id) ? 'selected' : '' }}>
                                    {{ $wo->policy_name ?? $wo->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-section-title">Overtime Policy</label>
                        <select name="overtime_policy" class="form-select form-select-sm text-muted">
                            <option value="">Not Applicable</option>
                            @foreach($overtimePolicies as $ot)
                                <option value="{{ $ot->id }}" {{ (isset($form->part_b_data['overtime_policy']) && $form->part_b_data['overtime_policy'] == $ot->id) ? 'selected' : '' }}>
                                    {{ $ot->policy_name ?? $ot->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-section-title mb-2">Leave Policies (select all that apply)</label>
                        @forelse($leavePolicies as $leave)
                        <div class="form-check mb-1">
                            <input class="form-check-input shadow-none" type="checkbox" name="leave_policies[]" value="{{ $leave->id }}" id="leave{{ $leave->id }}" {{ (isset($form->part_b_data['leave_policies']) && in_array($leave->id, $form->part_b_data['leave_policies'])) ? 'checked' : '' }}>
                            <label class="form-check-label fs-12 text-muted" for="leave{{ $leave->id }}">{{ $leave->name }}</label>
                        </div>
                        @empty
                        <div class="form-check mb-1">
                            <input class="form-check-input shadow-none" type="checkbox" id="leaveCasual">
                            <label class="form-check-label fs-12 text-muted" for="leaveCasual">Casual Leave</label>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Column 3: Time Rules -->
                <div class="col-lg-4 ps-lg-4 border-start">
                    <div class="mb-3 bg-light p-3 rounded border" style="border-color: #f3f3f9 !important;">
                        <label class="form-section-title mb-2 text-dark">Time Rules (select all that apply)</label>
                        
                        @forelse($timeRules as $rule)
                        <div class="form-check mb-2">
                            <input class="form-check-input shadow-none" type="checkbox" name="time_rules[]" value="{{ $rule->id }}" id="rule{{ $rule->id }}" {{ (isset($form->part_b_data['time_rules']) && in_array($rule->id, $form->part_b_data['time_rules'])) ? 'checked' : '' }}>
                            <label class="form-check-label time-rule-label" for="rule{{ $rule->id }}">
                                # {{ $rule->id }} {{ $rule->name ?? 'Time Rule' }}
                            </label>
                            @if(isset($rule->description))
                                <span class="time-rule-sub">- {{ $rule->description }}</span>
                            @endif
                        </div>
                        @empty
                        <!-- Mock Time Rules matching screenshot if none in DB -->
                        <div class="form-check mb-2">
                            <input class="form-check-input shadow-none" type="checkbox" id="rule1">
                            <label class="form-check-label time-rule-label" for="rule1"># 3172 Total Time between 00:00 to 04:29</label>
                            <span class="time-rule-sub">- Mark Absent on 1-30 occurrence(s)</span>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input shadow-none" type="checkbox" id="rule2">
                            <label class="form-check-label time-rule-label" for="rule2"># 3173 Total Time between 04:30 to 08:50</label>
                            <span class="time-rule-sub">- Mark Absent (Half Day) on 1-30 occurrence(s)</span>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input shadow-none" type="checkbox" id="rule3">
                            <label class="form-check-label time-rule-label" for="rule3"># 3176 Total Time between 00:00 to 03:55</label>
                            <span class="time-rule-sub">- Mark Absent on 1-30 occurrence(s)</span>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input shadow-none" type="checkbox" id="rule4">
                            <label class="form-check-label time-rule-label" for="rule4"># 3177 Total Time between 03:56 to 07:50</label>
                            <span class="time-rule-sub">- Mark Absent (Half Day) on 1-30 occurrence(s)</span>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input shadow-none" type="checkbox" id="rule5">
                            <label class="form-check-label time-rule-label" for="rule5"># 3252 Late Coming between 00:11 to 23:59</label>
                            <span class="time-rule-sub">- Send Warning on 4-31 occurrence(s)</span>
                        </div>
                        @endforelse
                        
                        <div class="mt-3 fs-10 text-muted">
                            Additional rules may apply based on selected shift policy.
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-2">
                <button type="submit" class="btn btn-danger px-4 py-1 rounded-1 shadow-sm fs-13" style="background-color: #f06548; border-color: #f06548;">
                    Continue <i class="ri-arrow-right-line align-middle ms-1"></i>
                </button>
            </div>
        </form>
    </div>
</div>

@endif

@endsection
