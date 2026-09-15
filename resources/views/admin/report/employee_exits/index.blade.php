@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-exits.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="employee-exits-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Employee Exits
    </div>

    <div class="page-head">
        <h4 class="page-title">Employee Exits</h4>
        <p class="page-subtitle">
            View employees exited during a given date range.
        </p>
    </div>

    <form action="{{ route('employee_exits') }}" method="GET" id="filterForm">
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
        </div>

        <div class="filter-row">
            <div class="date-box">
                <label>From</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}">
            </div>

            <div class="date-box">
                <label>To</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}">
            </div>

            <div class="filter-box">
                <label>Exit Reason</label>
                <select name="exit_reason_id" class="filter-select">
                    <option value="All">All Reasons</option>
                    @foreach($exitReasons as $reason)
                        <option value="{{ $reason->id }}" {{ request('exit_reason_id') == $reason->id ? 'selected' : '' }}>{{ $reason->reason_name }}</option>
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

    <div class="exits-table-wrap mt-3">
        <table class="exits-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>E-CODE</th>
                    <th>EMPLOYEE</th>
                    <th>LOCATION</th>
                    <th>DEPARTMENT</th>
                    <th>DESIGNATION</th>
                    <th>JOINING</th>
                    <th>EXIT</th>
                    <th>REASON OF EXIT</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($exits) && $exits->count() > 0)
                    @foreach($exits as $index => $exit)
                    <tr>
                        <td>{{ $exits->firstItem() + $index }}</td>
                        <td>{{ $exit->employee_code }}</td>
                        <td>{{ $exit->first_name }} {{ $exit->last_name }}</td>
                        <td>
                            @php $wp = $exit->workProfiles->where('is_current', true)->first(); @endphp
                            {{ $wp && $wp->location ? $wp->location->name : '-' }}
                        </td>
                        <td>{{ $wp && $wp->department ? $wp->department->name : '-' }}</td>
                        <td>{{ $wp && $wp->designation ? $wp->designation->name : '-' }}</td>
                        <td>{{ $exit->joining_date ? $exit->joining_date->format('d M, Y') : '-' }}</td>
                        <td style="font-weight: bold; color: #d9534f;">{{ $exit->exit_date->format('d M, Y') }}</td>
                        <td>{{ $exit->exitReason ? $exit->exitReason->reason_name : '-' }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="empty-row">
                        <td colspan="9" style="text-align: center; padding: 20px;">
                            @if(request()->has('from_date'))
                                No exits found for the selected filters.
                            @else
                                Please select filters and click View to load exits.
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if(isset($exits) && $exits->hasPages())
        <div class="mt-3">
            {{ $exits->links('pagination::bootstrap-4') }}
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