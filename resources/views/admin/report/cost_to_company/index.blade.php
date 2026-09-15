@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/cost-to-company.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="ctc-page">

    <div class="ctc-breadcrumb">
        Reports / Salary Reports / Cost To Company
    </div>

    <h4 class="ctc-title">Cost to Company</h4>
    <p class="ctc-subtitle">
        Shows breakup of salary for one or more selected employees including non-payable salary components and additional employer costs.
    </p>

    <div class="ctc-divider"></div>

    <form method="GET" action="{{ route('cost_to_company') }}" id="ctcForm">
        <div class="ctc-filter-row">
            <div class="ctc-field">
                <label>Location</label>
                <select name="location_id" class="form-select">
                    <option value="All">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="ctc-field">
                <label>Cost Center</label>
                <select name="cost_center_id" class="form-select">
                    <option value="All">All Cost Centers</option>
                    @foreach($costCenters as $cc)
                        <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="ctc-field">
                <label>Department</label>
                <select name="department_id" class="form-select">
                    <option value="All">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="ctc-options">
            <label class="radio-row">
                <input type="radio" name="revision_mode" value="latest" {{ request('revision_mode', 'latest') == 'latest' ? 'checked' : '' }}>
                <span>Show Latest Revision Only</span>
            </label>

            <label class="radio-row">
                <input type="radio" name="revision_mode" value="all" {{ request('revision_mode') == 'all' ? 'checked' : '' }}>
                <span>Show All Revisions</span>
            </label>

            <label class="radio-row">
                <input type="radio" name="revision_mode" value="date" {{ request('revision_mode') == 'date' ? 'checked' : '' }}>
                <span>Show Revisions as on</span>
                <input type="date" name="revision_date" class="date-box" value="{{ request('revision_date', date('Y-m-d')) }}">
            </label>

            <label class="check-row">
                <input type="checkbox" name="active_only" value="1" {{ request('active_only', '1') == '1' ? 'checked' : '' }}>
                <span>Active Records Only</span>
            </label>
        </div>

        <div class="ctc-action-row">
            <input type="text" name="employee_search" class="employee-search" placeholder="Search Employee Name or Code" value="{{ request('employee_search') }}">

            <select name="export_format" class="format-select" id="exportFormat">
                <option value="excel">Excel</option>
                <option value="pdf">PDF</option>
            </select>
        </div>

        <div class="ctc-btn-row">
            <input type="hidden" name="export" value="excel" id="exportType">
            <button type="button" class="ctc-btn" id="downloadBtn">
                <i class="ri-download-2-line"></i> Download
            </button>

            <button type="button" class="ctc-btn" onclick="alert('E-Mail feature requires SMTP configuration.')">
                <i class="ri-mail-line"></i> E-Mail
            </button>
        </div>
    </form>

    <div class="ctc-help-box">
        <h5>How to use this report</h5>

        <ul>
            <li>To generate report for a particular Location, select a location from the Location dropdown, else leave All Locations selected.</li>
            <li>In the same way, you can select Cost Center or Department to filter employee records.</li>
            <li>To generate report for a single employee, enter employee's name or employee code in Search Employee box.</li>
            <li>Click Download to download report in selected format.</li>
            <li>Click E-Mail to send report by email to selected employee(s).</li>
        </ul>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/cost-to-company.js') }}"></script>
@endsection