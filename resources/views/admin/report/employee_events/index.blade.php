@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-events.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="overtime-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Employee Events
    </div>

    <div class="page-head">
        <div>
            <h4 class="page-title">Employee Events</h4>
            <p class="page-subtitle">
                View or download list of employee events like birthdays, work anniversaries and wedding anniversaries.
            </p>
        </div>
    </div>

    <form action="{{ route('employee_event') }}" method="GET" id="filterForm">
        <div class="filter-grid">
            <div class="filter-group">
                <label>Location</label>
                <select name="location_id" class="filter-select">
                    <option value="All">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Cost Center</label>
                <select name="cost_center_id" class="filter-select">
                    <option value="All">All Cost Centers</option>
                    @foreach($costCenters as $cc)
                        <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name ?? $cc->code }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group">
                <label>Department</label>
                <select name="department_id" class="filter-select">
                    <option value="All">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="toggle-row">
            <label class="switch-item">
                <input type="checkbox" name="show_birthdays" value="1" {{ request('show_birthdays') || !request()->has('from_month') ? 'checked' : '' }}>
                <span class="slider"></span>
                <span>Birthdays</span>
            </label>

            <label class="switch-item">
                <input type="checkbox" name="show_work_anniversaries" value="1" {{ request('show_work_anniversaries') ? 'checked' : '' }}>
                <span class="slider"></span>
                <span>Work Anniversaries</span>
            </label>

            <label class="switch-item">
                <input type="checkbox" name="show_wedding_anniversaries" value="1" {{ request('show_wedding_anniversaries') ? 'checked' : '' }}>
                <span class="slider"></span>
                <span>Wedding Anniversaries</span>
            </label>
        </div>

        @php
            $monthOptions = [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];
            $currentMonth = date('F');
        @endphp

        <div class="action-row">
            <div class="filter-group small-filter">
                <label>From</label>
                <select name="from_month" class="filter-select month-select">
                    @foreach($monthOptions as $m)
                        <option value="{{ $m }}" {{ request('from_month', $currentMonth) == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-group small-filter">
                <label>To</label>
                <select name="to_month" class="filter-select month-select">
                    @foreach($monthOptions as $m)
                        <option value="{{ $m }}" {{ request('to_month', $currentMonth) == $m ? 'selected' : '' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="view-btn">
                <i class="ri-table-line"></i> View
            </button>

            <button type="submit" name="export" value="excel" class="download-btn">
                <i class="ri-file-excel-2-line"></i> Download (EXCEL)
            </button>

            <button type="submit" name="export" value="pdf" class="download-btn">
                <i class="ri-file-pdf-2-line"></i> Download (PDF)
            </button>
        </div>
    </form>

    <div class="report-table-wrap mt-3">
        <table class="report-table">
            <thead>
                <tr>
                    <th width="100">DATE</th>
                    <th width="150">EVENT TYPE</th>
                    <th>EMPLOYEE</th>
                    <th>LOCATION</th>
                    <th>DEPARTMENT</th>
                    <th>DESIGNATION</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($events) && $events->count() > 0)
                    @foreach($events as $event)
                    <tr>
                        <td style="font-weight: bold; color: #2F75B5;">{{ $event['date'] }}</td>
                        <td>
                            @if($event['event_type'] == 'Birthday')
                                <i class="ri-cake-2-fill text-warning"></i> Birthday
                            @elseif($event['event_type'] == 'Work Anniversary')
                                <i class="ri-briefcase-4-fill text-primary"></i> Work Anniversary
                            @else
                                <i class="ri-hearts-fill text-danger"></i> Wedding Anniversary
                            @endif
                        </td>
                        <td>{{ $event['employee'] }}</td>
                        <td>{{ $event['location'] }}</td>
                        <td>{{ $event['department'] }}</td>
                        <td>{{ $event['designation'] }}</td>
                    </tr>
                    @endforeach
                @else
                    <tr class="empty-row">
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            @if(request()->has('from_month'))
                                No events found for the selected filters.
                            @else
                                Please select filters and click View to load events.
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

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