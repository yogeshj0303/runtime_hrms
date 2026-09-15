@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/esi-deduction.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="esi-deduction-page">

    <div class="breadcrumb-text">
        ‹ Reports / Statutory Reports / ESI Deduction
    </div>

    <div class="page-head">
        <div>
            <h4 class="page-title">ESI Deduction</h4>
            <p class="page-subtitle">
                View or download ESI deduction for a period and ESI Monthly Contribution Report
            </p>
        </div>

        <button type="button" class="help-btn">
            <i class="ri-question-line"></i> Need Help
        </button>
    </div>

    <form method="GET" action="{{ route('esi_deduction') }}" id="esiForm">
        <div class="filter-row">
            <div class="filter-box">
                <label>Location</label>
                <select name="location_id" class="form-select">
                    <option value="All">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Cost Center</label>
                <select name="cost_center_id" class="form-select">
                    <option value="All">All Cost Centers</option>
                    @foreach($costCenters as $cc)
                        <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Department</label>
                <select name="department_id" class="form-select">
                    <option value="All">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="month-action-row">
            <input type="hidden" name="month" id="monthInput" value="{{ $month }}">
            
            <button type="button" class="month-nav-btn" id="prevMonth">
                <i class="ri-arrow-left-circle-line"></i>
            </button>

            <button type="button" class="month-btn" id="esiMonth">
                {{ strtoupper(\Carbon\Carbon::parse($month . '-01')->format('M-Y')) }}
            </button>

            <button type="button" class="month-nav-btn" id="nextMonth">
                <i class="ri-arrow-right-circle-line"></i>
            </button>

            <button type="submit" class="action-btn">
                <i class="ri-table-line"></i> View Summary
            </button>

            <button type="submit" name="export" value="excel" class="action-btn">
                <i class="ri-file-excel-2-line"></i> Excel Summary
            </button>

            <button type="submit" name="export" value="esi_return" class="action-btn">
                <i class="ri-file-text-line"></i> ESI Return
            </button>
        </div>
    </form>

    <div class="esi-table-wrap">
        <table class="esi-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>EMPLOYEE</th>
                    <th>IP NUMBER</th>
                    <th>DAYS</th>
                    <th>WAGES</th>
                    <th>EMPLOYEE ESI</th>
                    <th>EMPLOYER ESI</th>
                    <th>REASON FOR 0 DAYS</th>
                    <th>LAST WORKING DATE</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $item['employee']->employee_code }}</div>
                        </td>
                        <td>{{ $item['esi_number'] ?? '-' }}</td>
                        <td>{{ $item['days'] }}</td>
                        <td class="fw-semibold text-primary">₹ {{ number_format($item['gross_salary'], 2) }}</td>
                        <td class="fw-semibold text-success">₹ {{ number_format($item['employee_esi'], 2) }}</td>
                        <td class="fw-semibold text-info">₹ {{ number_format($item['employer_esi'], 2) }}</td>
                        <td>{{ $item['reason_for_0_days'] }}</td>
                        <td>{{ $item['employee']->exit_date ? \Carbon\Carbon::parse($item['employee']->exit_date)->format('d-m-Y') : '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="ri-inbox-line fs-3 d-block mb-2"></i>
                            No covered employees found for the selected criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/esi-deduction.js') }}"></script>
@endsection