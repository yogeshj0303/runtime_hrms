@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-joinings.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="employee-loans-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Employee Joinings
    </div>

    <div class="page-head">
        <h4 class="page-title">Employee Joinings</h4>
        <p class="page-subtitle">
            View employees joining during a given date range.
        </p>
    </div>

    <form action="{{ route('employee_joinings') }}" method="GET" id="filterForm">
        <div class="filter-row">
            <div class="filter-box">
                <label>Location</label>
                <select name="location_id" class="filter-select">
                    <option value="All">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Cost Center</label>
                <select name="cost_center_id" class="filter-select">
                    <option value="All">All Cost Centers</option>
                    @foreach($costCenters as $cc)
                        <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name ?? $cc->code }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Department</label>
                <select name="department_id" class="filter-select">
                    <option value="All">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="date-box">
                <label>From</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}">
            </div>

            <div class="date-box">
                <label>To</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}">
            </div>

            <div class="filter-box">
                <label>Grade</label>
                <select name="grade_id" class="filter-select">
                    <option value="All">All Grades</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}" {{ request('grade_id') == $grade->id ? 'selected' : '' }}>{{ $grade->name ?? $grade->grade_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="action-row">
            <button type="submit" class="view-btn">
                <i class="ri-eye-line"></i> View
            </button>

            <button type="submit" name="export" value="excel" class="excel-btn">
                <i class="ri-file-excel-2-line"></i> Excel
            </button>

            <button type="submit" name="export" value="pdf" class="pdf-btn">
                <i class="ri-file-pdf-2-line"></i> PDF
            </button>
        </div>
    </form>

    <div class="joining-table-wrap mt-3">
        <table class="joining-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>E-CODE</th>
                    <th>EMPLOYEE</th>
                    <th>JOINING</th>
                    <th>CONFIRMATION</th>
                    <th>LOCATION</th>
                    <th>DEPARTMENT</th>
                    <th>DESIGNATION</th>
                    <th>YEARLY CTC</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($joinings) && $joinings->count() > 0)
                    @foreach($joinings as $index => $joining)
                    <tr>
                        <td>{{ $joinings->firstItem() + $index }}</td>
                        <td>{{ $joining->employee_code }}</td>
                        <td>{{ $joining->first_name }} {{ $joining->last_name }}</td>
                        <td style="font-weight: bold; color: #28a745;">{{ $joining->joining_date ? $joining->joining_date->format('d M, Y') : '-' }}</td>
                        <td>{{ $joining->profile && $joining->profile->confirmation_date ? \Carbon\Carbon::parse($joining->profile->confirmation_date)->format('d M, Y') : '-' }}</td>
                        <td>
                            @php $wp = $joining->workProfiles->where('is_current', true)->first(); @endphp
                            {{ $wp && $wp->location ? $wp->location->name : '-' }}
                        </td>
                        <td>{{ $wp && $wp->department ? $wp->department->name : '-' }}</td>
                        <td>{{ $wp && $wp->designation ? $wp->designation->name : '-' }}</td>
                        <td>{{ $joining->salary ? number_format($joining->salary, 2) : '-' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="empty-row">
                        <td colspan="9" style="text-align: center; padding: 20px;">
                            @if(request()->has('from_date'))
                                No joinings found for the selected filters.
                            @else
                                Please select filters and click View to load joinings.
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if(isset($joinings) && $joinings->hasPages())
        <div class="mt-3">
            {{ $joinings->links('pagination::bootstrap-4') }}
        </div>
    @endif

</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    (function($) {
        function initSelect2() {
            $('.filter-select').select2({
                width: '100%',
                allowClear: false
            });
        }
        $(document).ready(initSelect2);
        document.addEventListener("turbolinks:load", initSelect2);
        document.addEventListener("pjax:complete", initSelect2);
    })(jQuery);
</script>
@endsection