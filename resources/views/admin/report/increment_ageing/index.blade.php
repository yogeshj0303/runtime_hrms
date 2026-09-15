@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/increment-ageing.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="increment-ageing-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Increment Ageing
    </div>

    <div class="page-head">
        <h4 class="page-title">Increment Ageing</h4>
        <p class="page-subtitle">
            View increment ageing based on last increment date of employees.
        </p>
    </div>

    <form method="GET" action="{{ route('increment_ageing') }}">
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

        <div class="action-row">
            <div class="filter-box small-box">
                <label>Ageing</label>
                <select name="ageing" class="form-select">
                    <option value="All">All Ageing</option>
                    <option value="1" {{ request('ageing') == '1' ? 'selected' : '' }}>More than 1 Year</option>
                    <option value="2" {{ request('ageing') == '2' ? 'selected' : '' }}>More than 2 Years</option>
                    <option value="3" {{ request('ageing') == '3' ? 'selected' : '' }}>More than 3 Years</option>
                </select>
            </div>

            <div class="filter-box small-box">
                <label>Grade</label>
                <select name="grade_id" class="form-select">
                    <option value="All">All Grades</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="view-btn">
                <i class="ri-table-line"></i> View
            </button>

            <button type="submit" name="export" value="excel" class="export-btn" style="background:#1d6f42;">
                <i class="ri-file-excel-2-line"></i> Export
            </button>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="increment-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>EMPLOYEE</th>
                    <th>DESIGNATION</th>
                    <th>DEPARTMENT</th>
                    <th>LAST INCREMENT</th>
                    <th>AGEING</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paginatedEmployees as $index => $emp)
                    @php
                        $wp = $emp->workProfiles->first();
                    @endphp
                    <tr>
                        <td>{{ $paginatedEmployees->firstItem() + $index }}</td>
                        <td>
                            <div class="employee-info" style="display:flex; align-items:center; gap:10px;">
                                @php
                                    $initials = strtoupper(substr($emp->first_name, 0, 1) . substr($emp->last_name, 0, 1));
                                    $colors = ['#f8d7da', '#d1ecf1', '#d4edda', '#fff3cd', '#cce5ff', '#e2e3e5', '#f8d7da'];
                                    $textColors = ['#721c24', '#0c5460', '#155724', '#856404', '#004085', '#383d41', '#721c24'];
                                    $colorIndex = $emp->id % count($colors);
                                @endphp
                                <span style="background: {{ $colors[$colorIndex] }}; color: {{ $textColors[$colorIndex] }}; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; border-radius: 50%; font-weight: bold; font-size: 14px;">
                                    {{ $initials }}
                                </span>
                                <div>
                                    <div style="font-weight: 500; color: #1a56db;">{{ $emp->first_name }} {{ $emp->last_name }}</div>
                                    <div style="font-size: 11px; color: #6b7280;">{{ $emp->employee_code }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ optional(optional($wp)->designation)->name ?? '-' }}</td>
                        <td>{{ optional(optional($wp)->department)->name ?? '-' }}</td>
                        <td>
                            <span class="badge bg-light text-dark border"><i class="ri-calendar-event-line"></i> {{ \Carbon\Carbon::parse($emp->last_increment_date)->format('M d, Y') }}</span>
                        </td>
                        <td>
                            @if($emp->ageing_years >= 3)
                                <span class="badge bg-danger-subtle text-danger">{{ $emp->ageing_str }}</span>
                            @elseif($emp->ageing_years >= 1)
                                <span class="badge bg-warning-subtle text-warning">{{ $emp->ageing_str }}</span>
                            @else
                                <span class="badge bg-success-subtle text-success">{{ $emp->ageing_str }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="6" style="text-align: center; padding: 30px; color: #6b7280;">No employees found matching the criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-end">
        {{ $paginatedEmployees->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/increment-ageing.js') }}"></script>
@endsection