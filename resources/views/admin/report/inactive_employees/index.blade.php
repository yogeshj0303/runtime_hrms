@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/inactive-employees.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="inactive-employees-page">

    <div class="breadcrumb-text">
        ‹ Reports / Employee Reports / Inactive Employees
    </div>

    <div class="page-head">
        <h4 class="page-title">Inactive Employees</h4>
        <p class="page-subtitle">
            View or download list of inactive employees.
        </p>
    </div>

    <form method="GET" action="{{ route('inactive_employees') }}" class="mb-4">
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
            
            <div class="d-flex gap-2 align-items-end">
                <button type="submit" class="view-btn">
                    <i class="ri-table-line"></i> View
                </button>
                <button type="submit" name="export" value="excel" class="view-btn" style="background:#1d6f42;">
                    <i class="ri-file-excel-2-line"></i> Excel
                </button>
                <button type="submit" name="export" value="pdf" class="view-btn" style="background:#e25050;">
                    <i class="ri-file-pdf-2-line"></i> PDF
                </button>
            </div>
        </div>
    </form>

    <div class="inactive-table-wrap">
        <table class="inactive-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>EMPLOYEE</th>
                    <th>JOINING DATE</th>
                    <th>LOCATION</th>
                    <th>COST CENTER</th>
                    <th>DEPARTMENT</th>
                    <th>DESIGNATION</th>
                </tr>
            </thead>

            <tbody>
                @forelse($employees as $index => $emp)
                    @php
                        $wp = $emp->workProfiles->first();
                    @endphp
                    <tr>
                        <td>{{ $employees->firstItem() + $index }}</td>
                        <td>
                            <div class="employee-info">
                                @php
                                    $initials = strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1));
                                    $colors = ['#f8d7da', '#d1ecf1', '#d4edda', '#fff3cd', '#cce5ff', '#e2e3e5', '#f8d7da'];
                                    $textColors = ['#721c24', '#0c5460', '#155724', '#856404', '#004085', '#383d41', '#721c24'];
                                    $colorIndex = $emp->id % count($colors);
                                @endphp
                                <span class="emp-avatar" style="background: {{ $colors[$colorIndex] }}; color: {{ $textColors[$colorIndex] }};">
                                    {{ $initials }}
                                </span>
                                <div>
                                    <a href="#">{{ $emp->first_name }} {{ $emp->last_name }}</a>
                                    <p>{{ $emp->employee_code }}</p>
                                    @if($emp->status == 'resigned')
                                        <span class="badge bg-danger" style="font-size: 10px;">Resigned</span>
                                    @elseif($emp->status == 'terminated')
                                        <span class="badge bg-dark" style="font-size: 10px;">Terminated</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $emp->joining_date ? \Carbon\Carbon::parse($emp->joining_date)->format('Y-m-d') : '-' }}</td>
                        <td>{{ optional(optional($wp)->location)->name ?? '-' }}</td>
                        <td>{{ optional(optional($wp)->costCenter)->name ?? '-' }}</td>
                        <td>{{ optional(optional($wp)->department)->name ?? '-' }}</td>
                        <td>{{ optional(optional($wp)->designation)->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No inactive employees found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/inactive-employees.js') }}"></script>
@endsection