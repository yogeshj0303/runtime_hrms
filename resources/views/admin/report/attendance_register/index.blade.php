@extends('layouts.master')

@section('content')
<link rel="stylesheet" href="{{ asset('assets/admin/css/attendance-register.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<div class="attendance-register-page">

    <div class="breadcrumb-text">
        Reports / Attendance Reports / Attendance Register
    </div>

    <div class="top-head">
        <div>
            <h4 class="page-title">Attendance Register</h4>
            <p class="page-subtitle">
                Download details of attendance during a given date range with optional information like time punches, strikes and time summary.
            </p>
        </div>

        <button class="help-btn">
            <i class="ri-question-line"></i> Read Help
        </button>
    </div>

    <form method="GET" action="{{ route('attendance_register') }}">
        <div class="filter-area">
            <div class="filter-grid">
                <div class="filter-box">
                    <label>Location</label>
                    <select name="location_id" class="form-select">
                        <option value="">All Locations</option>
                        @foreach($locations as $loc)
                            <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-box">
                    <label>Cost Center</label>
                    <select name="cost_center_id" class="form-select">
                        <option value="">All Cost Centers</option>
                        @foreach($costCenters as $cc)
                            <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-box">
                    <label>Department</label>
                    <select name="department_id" class="form-select">
                        <option value="">All Departments</option>
                        @foreach($departments as $dep)
                            <option value="{{ $dep->id }}" {{ request('department_id') == $dep->id ? 'selected' : '' }}>{{ $dep->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-box">
                    <label>From <span class="text-danger">*</span></label>
                    <input type="date" name="from_date" value="{{ $fromDate }}" required>
                </div>

                <div class="filter-box">
                    <label>To <span class="text-danger">*</span></label>
                    <input type="date" name="to_date" value="{{ $toDate }}" required>
                </div>

                <div class="filter-box">
                    <label>Employee</label>
                    <select name="employee_id" class="form-select select2-employee" style="width:100%">
                        <option value="">Search employee</option>
                        @foreach($employeesList as $emp)
                            <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="action-row">
                <button type="submit" class="view-btn">
                    <i class="ri-eye-line"></i> View
                </button>
                <button type="button" class="download-btn" onclick="window.print()">
                    <i class="ri-printer-line"></i> Print
                </button>
            </div>
        </div>

        <div class="report-options">
            <h5>Report Options</h5>

            <div class="radio-row">
                <label><input type="radio" name="record_status" value="all" {{ request('record_status', 'all') == 'all' ? 'checked' : '' }}> All Records</label>
                <label><input type="radio" name="record_status" value="active" {{ request('record_status') == 'active' ? 'checked' : '' }}> Active Records</label>
                <label><input type="radio" name="record_status" value="inactive" {{ request('record_status') == 'inactive' ? 'checked' : '' }}> Inactive Records</label>
            </div>

            <div class="switch-row">
                <label class="toggle-line">
                    <input type="checkbox" name="show_punches" value="1" {{ $showPunches ? 'checked' : '' }}>
                    <span class="toggle"></span>
                    Show Time Punches
                </label>

                <label class="toggle-line">
                    <input type="checkbox" name="show_strikes" value="1" {{ $showStrikes ? 'checked' : '' }}>
                    <span class="toggle"></span>
                    Show Strikes
                </label>

                <label class="toggle-line">
                    <input type="checkbox" name="show_summary" value="1" {{ $showSummary ? 'checked' : '' }}>
                    <span class="toggle"></span>
                    Show Time Summary
                </label>
            </div>
        </div>
    </form>

    <div class="short-info">
        <b>P</b> - Present | <b>A</b> - Absent | <b>WO</b> - Week Off |
        <b>H</b> - Holiday | <b>L</b> - Leave
    </div>

    <div class="report-result-box" style="padding:0;">
        <div class="table-responsive" style="max-height: 500px; overflow-y: auto; overflow-x: auto;">
            <table class="table table-bordered table-hover attendance-table" style="font-size: 11px; white-space: nowrap; margin: 0;">
                <thead style="position: sticky; top: 0; background: #fbfcfe; z-index: 10;">
                    <tr>
                        <th style="min-width: 150px; position: sticky; left: 0; background: #fbfcfe; z-index: 11;">Employee Details</th>
                        @foreach($dates as $date)
                            <th class="text-center" style="min-width: 80px;">{{ \Carbon\Carbon::parse($date)->format('d M') }}</th>
                        @endforeach
                        @if($showSummary)
                            <th class="text-center bg-light">P</th>
                            <th class="text-center bg-light">A</th>
                            <th class="text-center bg-light">Lates</th>
                            @if($showStrikes)
                                <th class="text-center bg-light">Strikes</th>
                            @endif
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($reportData as $row)
                        <tr>
                            <td style="position: sticky; left: 0; background: #fff; z-index: 5; font-weight:600;">
                                {{ $row['employee']->first_name }} {{ $row['employee']->last_name }}<br>
                                <span class="text-muted" style="font-size:10px; font-weight:normal;">{{ $row['employee']->employee_code }}</span>
                            </td>
                            @foreach($dates as $date)
                                @php
                                    $dayData = $row['attendance'][$date];
                                    $bgClass = '';
                                    if ($dayData['status'] == 'P') $bgClass = 'bg-success-light text-success';
                                    elseif ($dayData['status'] == 'A') $bgClass = 'bg-danger-light text-danger';
                                    elseif ($dayData['status'] == 'WO') $bgClass = 'bg-secondary text-white';
                                    elseif ($dayData['status'] == 'L') $bgClass = 'bg-warning-light text-warning';
                                    elseif ($dayData['status'] == 'H') $bgClass = 'bg-info-light text-info';
                                @endphp
                                <td class="text-center" style="vertical-align: middle;">
                                    <div class="badge {{ $bgClass }}" style="font-size:11px; padding:4px 6px;">
                                        {{ $dayData['status'] }}
                                        @if($dayData['is_late']) <span class="text-danger" title="Late">*</span> @endif
                                    </div>
                                    @if($showPunches && count($dayData['punches']) > 0)
                                        <div style="font-size:9px; margin-top:4px; color:#6b7280;">
                                            @foreach($dayData['punches'] as $punch)
                                                <div>{{ $punch }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                    @if($showStrikes && $dayData['has_strike'])
                                        <div style="font-size:9px; margin-top:2px; color:#dc3545; font-weight:bold;">[STRIKE]</div>
                                    @endif
                                </td>
                            @endforeach
                            
                            @if($showSummary)
                                <td class="text-center fw-bold bg-light" style="vertical-align: middle;">{{ $row['summary']['present'] }}</td>
                                <td class="text-center fw-bold bg-light" style="vertical-align: middle;">{{ $row['summary']['absent'] }}</td>
                                <td class="text-center fw-bold bg-light text-danger" style="vertical-align: middle;">{{ $row['summary']['late'] }}</td>
                                @if($showStrikes)
                                    <td class="text-center fw-bold bg-light text-danger" style="vertical-align: middle;">{{ $row['summary']['strikes'] }}</td>
                                @endif
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($dates) + ($showSummary ? ($showStrikes ? 5 : 4) : 1) }}" class="text-center p-4">
                                No records found for the selected criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2-employee').select2({
            placeholder: "Search employee",
            allowClear: true
        });
    });
</script>
<style>
    .bg-success-light { background-color: #d4edda !important; }
    .bg-danger-light { background-color: #f8d7da !important; }
    .bg-warning-light { background-color: #fff3cd !important; }
    .bg-info-light { background-color: #d1ecf1 !important; }
    .attendance-table th, .attendance-table td {
        border-color: #eef1f5 !important;
    }
</style>
@endsection