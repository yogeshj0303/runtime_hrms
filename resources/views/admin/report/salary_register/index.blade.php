@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/admin/css/salary-register.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">



<div class="salary-summary-page">

    <div class="breadcrumb-text">
        Reports / Salary Reports / Salary Register
    </div>

    <div class="page-top">
        <div>
            <h4 class="page-title">Salary Register</h4>
            <p class="page-subtitle">
                View or download salary register for organization or for selected cost center, location and/or department
            </p>
        </div>

        <button type="button" class="help-btn">
            <i class="ri-question-line"></i> Need Help
        </button>
    </div>

    <div class="top-filter-grid">
        <div class="filter-group">
            <label>Business Unit</label>
            <select class="filter-select">
                <option>All Business Units</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Location</label>
            <select class="filter-select">
                <option>All Locations</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Cost Center</label>
            <select class="filter-select">
                <option>All Cost Centers</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Department</label>
            <select class="filter-select">
                <option>All Departments</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Employee Tag</label>
            <select class="filter-select">
                <option>None</option>
            </select>
        </div>
    </div>

    <div class="filter-row">
        <button type="button" class="filter-btn" id="prevMonth">
            <i class="ri-arrow-left-s-line"></i>
        </button>

        <input type="month" class="month-box" id="salaryMonth" value="2026-05">

        <button type="button" class="filter-btn" id="nextMonth">
            <i class="ri-arrow-right-s-line"></i>
        </button>

        <input type="text" class="employee-input" placeholder="All Employees">

        <button type="button" class="view-btn">
            <i class="ri-download-2-line"></i> Download (PDF)
        </button>

        <button type="button" class="view-btn">
            <i class="ri-file-excel-2-line"></i> Download (EXCEL)
        </button>
    </div>

    <div class="report-options-card">

        <div class="report-options-title">
            <i class="ri-filter-3-line"></i>
            Report Options
        </div>

        <hr>

        <div class="records-row">
            <div>
                <h6>Records to Include</h6>

                <div class="record-checks">
                    <div class="form-check form-switch">
                        <input class="form-check-input record-type" type="checkbox" id="allRecords">
                        <label class="form-check-label" for="allRecords">All Records</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input record-type" type="checkbox" id="activeRecords" checked>
                        <label class="form-check-label" for="activeRecords">Active Records</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input record-type" type="checkbox" id="inactiveRecords">
                        <label class="form-check-label" for="inactiveRecords">Inactive Records</label>
                    </div>
                </div>
            </div>

            <div class="form-check form-switch hold-check">
                <input class="form-check-input" type="checkbox" id="excludeHold" checked>
                <label class="form-check-label" for="excludeHold">Exclude HOLD Salary</label>
            </div>
        </div>

        <div class="checkbox-grid">

            <div class="check-column">
                <h6>Basic Details</h6>

                @php
                    $basic = [
                        ['Employee Code', 'empCode', true],
                        ['Employee Name', 'empName', true],
                        ['Gender', 'gender', false],
                        ['Date of Birth', 'dob', false],
                        ['Date of Joining', 'doj', true],
                        ['Date of Exit', 'exitDate', false],
                        ['Salary Units', 'salaryUnits', false],
                    ];
                @endphp

                @foreach($basic as $item)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" id="{{ $item[1] }}" {{ $item[2] ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $item[1] }}">{{ $item[0] }}</label>
                    </div>
                @endforeach

                <h6 class="mt-3">Extra Info</h6>

                @for($i = 1; $i <= 5; $i++)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" id="extra{{ $i }}">
                        <label class="form-check-label" for="extra{{ $i }}">Other Info {{ $i }}</label>
                    </div>
                @endfor
            </div>

            <div class="check-column">
                <h6>Personal Details</h6>

                @php
                    $personal = [
                        ['ESI IP Number', 'esi', false],
                        ['PF UAN Number', 'pf', false],
                        ['Income Tax PAN', 'pan', false],
                        ['Aadhar Number', 'aadhar', false],
                        ['Office E-Mail', 'email', false],
                        ['Mobile Phone', 'mobile', false],
                        ['Bank Name', 'bankName', true],
                        ['Bank IFSC', 'ifsc', true],
                        ['Bank Account', 'account', true],
                    ];
                @endphp

                @foreach($personal as $item)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" id="{{ $item[1] }}" {{ $item[2] ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $item[1] }}">{{ $item[0] }}</label>
                    </div>
                @endforeach
            </div>

            <div class="check-column">
                <h6>Attendance</h6>

                @php
                    $attendance = [
                        ['Total Days', true],
                        ['Presents', true],
                        ['Absents', true],
                        ['Week Offs', true],
                        ['Holidays', true],
                        ['Extra Days', false],
                        ['Arrear Days', false],
                        ['Overtime Days', false],
                        ['Leave Breakup', true],
                        ['Paid Leaves', true],
                        ['Unpaid Leaves', true],
                        ['Payable Days', true],
                        ['Unpaid Days', false],
                    ];
                @endphp

                @foreach($attendance as $index => $item)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" id="attendance{{ $index }}" {{ $item[1] ? 'checked' : '' }}>
                        <label class="form-check-label" for="attendance{{ $index }}">{{ $item[0] }}</label>
                    </div>
                @endforeach
            </div>

            <div class="check-column">
                <h6>Work Profile</h6>

                @php
                    $work = [
                        ['Location', 'location', true],
                        ['Cost Center', 'costCenter', false],
                        ['Department', 'department', true],
                        ['Designation', 'designation', true],
                    ];
                @endphp

                @foreach($work as $item)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" id="{{ $item[1] }}" {{ $item[2] ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $item[1] }}">{{ $item[0] }}</label>
                    </div>
                @endforeach

                <h6 class="mt-3">Excel Only Info</h6>

                <div class="form-check form-check-primary mb-2">
                    <input class="form-check-input" type="checkbox" id="ctc">
                    <label class="form-check-label" for="ctc">Show CTC</label>
                </div>

                <div class="form-check form-check-primary mb-2">
                    <input class="form-check-input" type="checkbox" id="timeStrikes">
                    <label class="form-check-label" for="timeStrikes">Show Time Strikes</label>
                </div>

                <h6 class="mt-3">Summarization</h6>

                <div class="form-check form-check-primary mb-2">
                    <input class="form-check-input" type="checkbox" id="summarizeSalary">
                    <label class="form-check-label" for="summarizeSalary">Summarize Salary</label>
                </div>

                <div class="form-check form-check-primary mb-2">
                    <input class="form-check-input" type="checkbox" id="summarizeDeduction">
                    <label class="form-check-label" for="summarizeDeduction">Summarize Deductions</label>
                </div>
            </div>

        </div>

        <div class="rounding-row">
            <div class="filter-group small-filter">
                <label>Amount Rounding</label>
                <select class="filter-select">
                    <option>0 decimals</option>
                </select>
            </div>

            <div class="filter-group small-filter">
                <label>Unit Rounding</label>
                <select class="filter-select">
                    <option>0 decimals</option>
                </select>
            </div>

            <div class="filter-group small-filter">
                <label>Records Per Page</label>
                <input type="text" class="filter-select" value="6">
            </div>
        </div>

        <div class="save-area">
            <button type="button" class="save-btn">
                <i class="ri-save-line"></i> Save
            </button>
        </div>

    </div>

</div>

@endsection

@section('script')

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/salary-register.js') }}"></script>
@endsection