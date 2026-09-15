@extends('layouts.master')
@section('title') Monthly Attendance @endsection
@section('content')

@component('components.breadcrumb')
@slot('li_1') Attendance @endslot
@slot('title') Monthly Attendance @endslot
@endcomponent

<style>
    .cal-cell {
        height: 60px;
        padding: 5px;
        position: relative;
    }
    .cal-header {
        text-align: center;
        font-weight: 600;
        padding: 10px 0;
        border: 1px solid #e9ebec;
        background-color: #f3f6f9;
    }
    .cal-date {
        font-size: 11px;
        font-weight: 500;
        background-color: #fff;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        margin-right: 5px;
    }
    
    .status-P { background-color: #68d6c7; color: #fff; }
    .status-A { background-color: #ff7f7f; color: #fff; }
    .status-W { background-color: #a4a19b; color: #fff; }
    .status-L { background-color: #f7b84b; color: #fff; }
    .status-H { background-color: #6559cc; color: #fff; }
    .status-empty { background-color: #dbdbdb; }
    
    .legend-box {
        display: inline-block;
        padding: 4px 10px;
        color: #fff;
        font-size: 11px;
        font-weight: 500;
        border-radius: 3px;
        margin-right: 10px;
    }
    .legend-container {
        background-color: #1a2234;
        padding: 8px 15px;
        border-radius: 4px;
        color: #fff;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Monthly Attendance</h5>
                    <div class="flex-shrink-0">
                        <div class="dropdown">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Options
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('attendance.export-template') }}"><i class="ri-download-2-line align-bottom me-2 text-muted"></i> Export Template</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadAttendanceModal"><i class="ri-upload-2-line align-bottom me-2 text-muted"></i> Upload Excel</a></li>
                                <li><a class="dropdown-item" href="{{ route('attendance.download-data', request()->all()) }}"><i class="ri-file-download-line align-bottom me-2 text-muted"></i> Download Data</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <p class="text-muted mb-0 mt-1 fs-12">View monthly attendance at employee level in a calendar view.</p>
            </div>
            
            <div class="card-body bg-light border-bottom border-top border-light">
                <form action="{{ route('attendance.monthly') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Business Unit</label>
                            <select name="business_unit_id" class="form-control form-control-sm" data-choices>
                                <option value="">All Units</option>
                                @foreach($businessUnits as $unit)
                                    <option value="{{ $unit->id }}" {{ request('business_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name ?? $unit->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Location</label>
                            <select name="location_id" class="form-control form-control-sm" data-choices>
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Cost Center</label>
                            <select name="cost_center_id" class="form-control form-control-sm" data-choices>
                                <option value="">All Cost Centers</option>
                                @foreach($costCenters as $cc)
                                    <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Department</label>
                            <select name="department_id" class="form-control form-control-sm" data-choices>
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-1">
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Month</label>
                            <input type="month" name="month_year" class="form-control form-control-sm" value="{{ $monthYear }}" required>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <label class="fs-12 mb-1">Employee</label>
                            <select name="employee_id" class="form-control form-control-sm" data-choices>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->user_id }}" {{ $employeeId == $emp->user_id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="ri-search-line align-bottom me-1"></i> View</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                @if($selectedEmployee)
                    @php
                        $workProfile = $selectedEmployee->workProfiles->first();
                        $policy = $selectedEmployee->policy;
                        $shiftPolicy = $policy ? ($shiftPolicies[$policy->shift_policy_id] ?? null) : null;
                        
                        $shiftName = 'Default Shift';
                        if ($shiftPolicy && $shiftPolicy->default_shift_id) {
                            $shift = $shifts[$shiftPolicy->default_shift_id] ?? null;
                            if ($shift) $shiftName = $shift->name;
                        }
                    @endphp
                    
                    <div class="row">
                        <!-- Left Panel: Employee Details -->
                        <div class="col-md-3 border-end pe-4">
                            <h6 class="text-uppercase fw-semibold mb-4 text-primary">{{ $selectedEmployee->first_name }} {{ $selectedEmployee->last_name }} ({{ $selectedEmployee->employee_code }})</h6>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="text-muted fs-11 mb-0">Date of Joining</p>
                                    <p class="fs-12 text-dark">{{ $selectedEmployee->joining_date ? $selectedEmployee->joining_date->format('d M Y') : 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted fs-11 mb-0">Date of Exit</p>
                                    <p class="fs-12 text-dark">-</p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-6">
                                    <p class="text-muted fs-11 mb-0">Location</p>
                                    <p class="fs-12 text-dark">{{ $workProfile->location->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-6">
                                    <p class="text-muted fs-11 mb-0">Department</p>
                                    <p class="fs-12 text-dark">{{ $workProfile->department->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <p class="text-muted fs-11 mb-0">Designation</p>
                                    <p class="fs-12 text-dark">{{ $selectedEmployee->designationInfo->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-12">
                                    <p class="text-muted fs-11 mb-0">Default Shift</p>
                                    <p class="fs-12 text-dark">{{ $shiftName }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="button" class="btn btn-warning btn-sm">Recalculate</button>
                                <button type="button" class="btn btn-success btn-sm">Send Update</button>
                            </div>
                        </div>
                        
                        <!-- Right Panel: Calendar -->
                        <div class="col-md-9 ps-4">
                            @php
                                $year = substr($monthYear, 0, 4);
                                $month = substr($monthYear, 5, 2);
                                $daysInMonth = \Carbon\Carbon::parse($monthYear . '-01')->daysInMonth;
                                $firstDayOfMonth = \Carbon\Carbon::parse($monthYear . '-01')->dayOfWeek; // 0 (Sun) to 6 (Sat)
                                
                                $calendar = [];
                                $dayCounter = 1;
                                
                                for ($week = 0; $week < 6; $week++) {
                                    $calendar[$week] = [];
                                    for ($day = 0; $day < 7; $day++) {
                                        if ($week == 0 && $day < $firstDayOfMonth) {
                                            $calendar[$week][] = null; 
                                        } elseif ($dayCounter <= $daysInMonth) {
                                            $calendar[$week][] = $dayCounter;
                                            $dayCounter++;
                                        } else {
                                            $calendar[$week][] = null; 
                                        }
                                    }
                                    if ($dayCounter > $daysInMonth && array_filter($calendar[$week]) == []) {
                                        unset($calendar[$week]);
                                    }
                                }
                            @endphp
                            
                            <div class="table-responsive">
                                <table class="table table-borderless table-nowrap mb-0" style="table-layout: fixed; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="cal-header">SUN</th>
                                            <th class="cal-header">MON</th>
                                            <th class="cal-header">TUE</th>
                                            <th class="cal-header">WED</th>
                                            <th class="cal-header">THU</th>
                                            <th class="cal-header">FRI</th>
                                            <th class="cal-header">SAT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($calendar as $week)
                                            <tr>
                                                @foreach($week as $dayNum)
                                                    @php
                                                        $cellClass = 'status-empty';
                                                        $displayText = '';
                                                        $cellStyle = '';
                                                        
                                                        if ($dayNum !== null) {
                                                            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $dayNum);
                                                            $att = $attendances->get($dateStr);
                                                            
                                                            if ($att) {
                                                                $statusLower = strtolower($att->status);
                                                                if ($statusLower == 'present') {
                                                                    $cellClass = 'status-P';
                                                                    $displayText = 'P';
                                                                } elseif ($statusLower == 'absent') {
                                                                    $cellClass = 'status-A';
                                                                    $displayText = 'A';
                                                                } elseif ($statusLower == 'week_off' || $statusLower == 'week-off') {
                                                                    $cellClass = 'status-W';
                                                                    $displayText = 'W';
                                                                } elseif ($statusLower == 'holiday') {
                                                                    $cellClass = 'status-H';
                                                                    $displayText = 'H';
                                                                } else {
                                                                    $matchedLeave = false;
                                                                    foreach($leaveTypes as $leaveType) {
                                                                        if ($statusLower == strtolower($leaveType->short_name) || $statusLower == strtolower($leaveType->name)) {
                                                                            $cellClass = '';
                                                                            $cellStyle = 'background-color: ' . ($leaveType->color ?? '#f7b84b') . '; color: #fff;';
                                                                            $displayText = $leaveType->short_name ?? strtoupper(substr($leaveType->name, 0, 2));
                                                                            $matchedLeave = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                    
                                                                    if (!$matchedLeave) {
                                                                        if ($statusLower == 'leave') {
                                                                            $cellClass = 'status-L';
                                                                            $displayText = 'L';
                                                                        } elseif ($statusLower == 'comp off' || $statusLower == 'co') {
                                                                            $cellClass = 'status-H'; // Same color as holiday or similar
                                                                            $displayText = 'CO';
                                                                        } else {
                                                                            // Fallback for custom statuses like UL, T
                                                                            $cellClass = 'status-L';
                                                                            $displayText = strtoupper(substr($att->status, 0, 2));
                                                                        }
                                                                    }
                                                                }
                                                            } else {
                                                                // Empty date logic
                                                                $dateObj = \Carbon\Carbon::createFromFormat('Y-m-d', $dateStr);
                                                                
                                                                if ($dateObj->startOfDay()->lte(\Carbon\Carbon::now()->startOfDay())) {
                                                                    $dayOfWeek = strtolower($dateObj->format('l'));
                                                                    $weekOfMonth = (string) ceil($dateObj->day / 7);
                                                                    $weekColumn = $dayOfWeek . '_weeks';
                                                                    
                                                                    $weekOffPolicy = ($selectedEmployee && $selectedEmployee->policy && $selectedEmployee->policy->weekOffPolicy) 
                                                                        ? $selectedEmployee->policy->weekOffPolicy 
                                                                        : ($defaultWeekOffPolicy ?? null);
                                                                    
                                                                    $isWeekOff = false;
                                                                    if ($weekOffPolicy && is_array($weekOffPolicy->$weekColumn)) {
                                                                        if (in_array($weekOfMonth, $weekOffPolicy->$weekColumn)) {
                                                                            $isWeekOff = true;
                                                                        }
                                                                    } elseif ($dayOfWeek == 'sunday') {
                                                                        $isWeekOff = true;
                                                                    }

                                                                    if (isset($holidays[$dateStr])) {
                                                                        $cellClass = 'status-H';
                                                                        $displayText = 'H';
                                                                    } elseif ($isWeekOff) {
                                                                        $cellClass = 'status-W';
                                                                        $displayText = 'W';
                                                                    } else {
                                                                        $cellClass = 'status-A';
                                                                        $displayText = 'A';
                                                                    }
                                                                } else {
                                                                    $cellClass = 'status-empty';
                                                                }
                                                            }
                                                        }
                                                    @endphp
                                                    <td class="p-0 border" style="border-color: #fff !important;">
                                                        @if($dayNum !== null)
                                                            <div class="cal-cell {{ $cellClass }} cursor-pointer edit-attendance-cell" 
                                                                 style="{{ $cellStyle }}; cursor: pointer;"
                                                                 data-date="{{ $dateStr }}"
                                                                 data-status="{{ $att ? $att->status : 'Absent' }}"
                                                                 data-att-id="{{ $att ? $att->id : '' }}"
                                                                 data-punch-in="{{ ($att && $att->details->first()) ? \Carbon\Carbon::parse($att->details->first()->punch_in_time)->format('H:i') : '' }}"
                                                                 data-punch-out="{{ ($att && $att->details->last()) ? \Carbon\Carbon::parse($att->details->last()->punch_out_time)->format('H:i') : '' }}"
                                                                 >
                                                                <div class="d-flex align-items-center">
                                                                    <span class="cal-date text-dark fw-bold">{{ sprintf('%02d', $dayNum) }}</span>
                                                                    <span class="fw-bold">{{ $displayText }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <div class="legend-container d-flex align-items-center flex-wrap">
                            <span class="me-3 fw-bold fs-12">Icons & Legend:</span>
                            <span class="legend-box" style="background-color: #68d6c7;">P: Present</span>
                            <span class="legend-box" style="background-color: #ff7f7f;">A: Absent</span>
                            <span class="legend-box" style="background-color: #a4a19b;">W: Week Off</span>
                            <span class="legend-box" style="background-color: #6559cc;">H: Holiday</span>
                            <span class="legend-box" style="background-color: #6559cc;">CO: Comp Off</span>
                            
                            @foreach($leaveTypes as $leaveType)
                                <span class="legend-box" style="background-color: {{ $leaveType->color ?? '#f7b84b' }};">
                                    {{ $leaveType->short_name ?? strtoupper(substr($leaveType->name, 0, 2)) }}: {{ $leaveType->name }}
                                </span>
                            @endforeach
                            
                            <span class="legend-box" style="background-color: #f7b84b;">L: Leave</span>
                        </div>
                    </div>
                @else
                    <div class="text-center p-5 text-muted">
                        <i class="ri-calendar-line fs-24 mb-2"></i>
                        <p>Select an employee and click "View" to see their monthly attendance.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@include('components.attendance-edit-modal')

<!-- Upload Excel Modal -->
<div class="modal fade" id="uploadAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Upload Monthly Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('attendance.upload-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="excelFile" class="form-label">Select Excel File</label>
                        <input type="file" class="form-control" name="file" id="excelFile" accept=".xlsx, .xls" required>
                        <small class="text-muted mt-1 d-block">Please use the template downloaded from Export Template option.</small>
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

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.edit-attendance-cell').on('click', function() {
            var date = $(this).data('date');
            var status = $(this).data('status');
            var attId = $(this).data('att-id');
            var punchIn = $(this).data('punch-in');
            var punchOut = $(this).data('punch-out');
            
            var employeeId = "{{ $selectedEmployee ? $selectedEmployee->user_id : '' }}";
            if (!employeeId) return;

            $('#editAttendanceId').val(attId);
            $('#editEmployeeId').val(employeeId);
            $('#editAttendanceDate').val(date);
            
            var statusValue = 'Absent';
            if(status && status.toLowerCase() === 'present') statusValue = 'Present';
            else if(status && status.toLowerCase().includes('half')) statusValue = 'Half Day';
            else if(status && status.toLowerCase() === 'leave') statusValue = 'Leave';
            else if(status) statusValue = status; // Keep original if it doesn't match default
            
            // If the select doesn't have the option, add it temporarily
            if ($('#editStatus option[value="'+statusValue+'"]').length === 0) {
                $('#editStatus').append($('<option>', {
                    value: statusValue,
                    text: statusValue
                }));
            }
            
            $('#editStatus').val(statusValue);
            $('#editStart').val(punchIn);
            $('#editEnd').val(punchOut);
            
            $('#editAttendanceModal').modal('show');
        });
    });
</script>
@endsection
