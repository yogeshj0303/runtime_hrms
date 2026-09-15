@extends('layouts.master')

@section('title')
Shift Roster
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    .roster-table th, .roster-table td {
        min-width: 60px;
        text-align: center;
        vertical-align: middle;
        padding: 4px;
    }
    .roster-table th.employee-col, .roster-table td.employee-col {
        min-width: 200px;
        text-align: left;
        position: sticky;
        left: 0;
        background-color: #fff;
        z-index: 1;
        box-shadow: 2px 0 5px rgba(0,0,0,0.05);
    }
    .shift-select {
        width: 100%;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 2px;
        font-size: 12px;
        cursor: pointer;
    }
    .shift-select:focus {
        outline: none;
        border-color: #556ee6;
    }
    .weekend-col {
        background-color: #f8f9fa !important;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Attendance</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Shift Roster</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>Shift Roster Management</h4>
            <p class="text-muted">
                Manage daily, weekly, and monthly shift assignments for employees.
            </p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-primary btn-add" id="btn-auto-generate">
                <i class="ri-calendar-event-line"></i>
                Auto Generate Monthly Roster
            </button>
            <div class="dropdown d-inline-block ms-2">
                <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Options
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('attendance.shift-roster.download-template') }}"><i class="ri-download-2-line align-bottom me-2 text-muted"></i> Download Template</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadRosterModal"><i class="ri-upload-2-line align-bottom me-2 text-muted"></i> Upload Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('attendance.shift-roster.export-roster', request()->all()) }}"><i class="ri-file-download-line align-bottom me-2 text-muted"></i> Export Roster</a></li>
                </ul>
            </div>
            <button class="btn btn-success btn-add ms-2" id="btn-save-roster">
                <i class="ri-save-line"></i>
                Save Changes
            </button>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('attendance.shift-roster') }}" class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="month" class="form-label mb-0">Month</label>
                <select name="month" id="month" class="form-select">
                    @for($m=1; $m<=12; ++$m)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $month == sprintf('%02d', $m) ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-auto">
                <label for="year" class="form-label mb-0">Year</label>
                <select name="year" id="year" class="form-select">
                    @for($y=date('Y')-1; $y<=date('Y')+1; $y++)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-auto align-self-end">
                <button type="submit" class="btn btn-secondary mt-4">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
            <table class="table table-bordered table-hover mb-0 roster-table">
                <thead class="table-light sticky-top" style="z-index: 2;">
                    <tr>
                        <th class="employee-col">Employee</th>
                        @for($d=1; $d<=$daysInMonth; $d++)
                            @php 
                                $dateObj = \Carbon\Carbon::createFromDate($year, $month, $d);
                                $isWeekend = $dateObj->isWeekend();
                            @endphp
                            <th class="{{ $isWeekend ? 'weekend-col' : '' }}">
                                {{ $d }}<br>
                                <small class="text-muted">{{ $dateObj->format('D') }}</small>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $emp)
                        <tr>
                            <td class="employee-col">
                                <div class="d-flex align-items-center">
                                    <div class="ms-2">
                                        <h6 class="mb-0">{{ $emp->first_name }} {{ $emp->last_name }}</h6>
                                        <span class="text-muted small">{{ $emp->emp_id ?? 'N/A' }}</span>
                                    </div>
                                </div>
                            </td>
                            @for($d=1; $d<=$daysInMonth; $d++)
                                @php 
                                    $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $d);
                                    $dateObj = \Carbon\Carbon::parse($dateStr);
                                    $isWeekend = $dateObj->isWeekend();
                                    $assignedShiftId = $rosterData[$emp->id][$dateStr] ?? '';
                                @endphp
                                <td class="{{ $isWeekend ? 'weekend-col' : '' }}">
                                    <select class="shift-select" data-emp="{{ $emp->id }}" data-date="{{ $dateStr }}">
                                        <option value="">-</option>
                                        @foreach($shifts as $shift)
                                            <option value="{{ $shift->id }}" {{ $assignedShiftId == $shift->id ? 'selected' : '' }}>
                                                {{ $shift->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

<!-- Upload Excel Modal -->
<div class="modal fade" id="uploadRosterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Upload Shift Roster</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('attendance.shift-roster.upload-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excelFile" class="form-label">Select Excel File</label>
                        <input type="file" class="form-control" name="file" id="excelFile" accept=".xlsx, .xls" required>
                        <small class="text-muted mt-1 d-block">Please use the template downloaded from Options.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload & Process</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#btn-save-roster').click(function() {
            var btn = $(this);
            var originalText = btn.html();
            btn.html('<i class="ri-loader-line ri-spin"></i> Saving...');
            btn.prop('disabled', true);

            var rosterUpdates = [];
            $('.shift-select').each(function() {
                var empId = $(this).data('emp');
                var date = $(this).data('date');
                var shiftId = $(this).val();
                
                // Only send if a shift is selected (or we can send all to clear them if empty)
                rosterUpdates.push({
                    employee_id: empId,
                    roster_date: date,
                    shift_id: shiftId
                });
            });

            $.ajax({
                url: '{{ route("attendance.shift-roster.update-bulk") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    rosters: rosterUpdates
                },
                success: function(response) {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    alert('Roster saved successfully!');
                },
                error: function(xhr) {
                    btn.html(originalText);
                    btn.prop('disabled', false);
                    alert('Error saving roster');
                }
            });
        });

        $('#btn-auto-generate').click(function() {
            if(confirm('This will automatically generate the monthly roster based on default employee shift policies. Existing manual overrides for this month may be overwritten. Proceed?')) {
                var btn = $(this);
                btn.html('<i class="ri-loader-line ri-spin"></i> Generating...');
                btn.prop('disabled', true);

                $.ajax({
                    url: '{{ route("attendance.shift-roster.auto-generate") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        month: $('#month').val(),
                        year: $('#year').val()
                    },
                    success: function(response) {
                        alert('Roster generated successfully!');
                        location.reload();
                    },
                    error: function(xhr) {
                        btn.html('<i class="ri-calendar-event-line"></i> Auto Generate Monthly Roster');
                        btn.prop('disabled', false);
                        alert('Error generating roster');
                    }
                });
            }
        });
    });
</script>
@endsection
