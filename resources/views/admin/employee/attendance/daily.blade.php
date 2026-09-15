@extends('layouts.master')
@section('title') Daily Attendance @endsection
@section('content')

@component('components.breadcrumb')
@slot('li_1') Attendance @endslot
@slot('title') Daily Attendance @endslot
@endcomponent

<style>
    .timeline-container {
        position: relative;
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        margin: 25px 0 10px 0;
        width: 100%;
    }
    
    .timeline-shift-bar {
        position: absolute;
        height: 100%;
        background-color: #f1b44c; /* Warning color for shift */
        border-radius: 4px;
        /* Defaulting to 10% to 90% as placeholder for shift duration */
        left: 10%;
        width: 80%;
    }
    
    .timeline-worked-bar {
        position: absolute;
        height: 100%;
        background-color: #0ab39c; /* Success color for worked time */
        border-radius: 4px;
        /* Example: slightly inside the shift bar */
        left: 15%;
        width: 70%;
        z-index: 2;
    }
    
    .timeline-label-start {
        position: absolute;
        top: -20px;
        left: 10%;
        transform: translateX(-50%);
        font-size: 11px;
        color: #878a99;
    }
    
    .timeline-label-end {
        position: absolute;
        top: -20px;
        left: 90%;
        transform: translateX(-50%);
        font-size: 11px;
        color: #878a99;
    }
    
    .timeline-label-punch-in {
        position: absolute;
        top: 12px;
        left: 15%;
        transform: translateX(-50%);
        font-size: 11px;
        color: #0ab39c;
        font-weight: 500;
    }
    

</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card" id="dailyAttendanceList">
            <div class="card-header border-0 pb-1">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Daily Attendance</h5>
                </div>
                <p class="text-muted mb-0">View or edit daily time punches for all employees.</p>
            </div>
            
            <div class="card-body border border-dashed border-start-0 border-end-0 pb-3 pt-2">
                <form action="{{ route('attendance.daily') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Business Unit</label>
                            <select class="form-control form-control-sm" data-choices name="business_unit_id" id="business_unit_id">
                                <option value="">All Units</option>
                                @foreach($businessUnits as $unit)
                                    <option value="{{ $unit->id }}" {{ request('business_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Location</label>
                            <select class="form-control form-control-sm" data-choices name="location_id" id="location_id">
                                <option value="">All Locations</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Cost Center</label>
                            <select class="form-control form-control-sm" data-choices name="cost_center_id" id="cost_center_id">
                                <option value="">All Cost Centers</option>
                                @foreach($costCenters as $center)
                                    <option value="{{ $center->id }}" {{ request('cost_center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Department</label>
                            <select class="form-control form-control-sm" data-choices name="department_id" id="department_id">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-1">
                        <div class="col-xxl-5 col-sm-12 d-flex align-items-center">
                            <div class="form-check form-radio-primary mb-3 me-3">
                                <input class="form-check-input" type="radio" name="status_filter" id="status_all" value="all" {{ $statusFilter == 'all' ? 'checked' : '' }}>
                                <label class="form-check-label fs-13" for="status_all">Show All</label>
                            </div>
                            <div class="form-check form-radio-warning mb-3 me-3">
                                <input class="form-check-input" type="radio" name="status_filter" id="status_late" value="late" {{ $statusFilter == 'late' ? 'checked' : '' }}>
                                <label class="form-check-label fs-13" for="status_late">Late Coming Only</label>
                            </div>
                            <div class="form-check form-radio-danger mb-3 me-3">
                                <input class="form-check-input" type="radio" name="status_filter" id="status_absent" value="absent" {{ $statusFilter == 'absent' ? 'checked' : '' }}>
                                <label class="form-check-label fs-13" for="status_absent">Absent Only</label>
                            </div>
                            <div class="form-check form-radio-secondary mb-3 me-3">
                                <input class="form-check-input" type="radio" name="status_filter" id="status_no_punches" value="no_punches" {{ $statusFilter == 'no_punches' ? 'checked' : '' }}>
                                <label class="form-check-label fs-13" for="status_no_punches">No Punches</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Date Filter</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ri-calendar-2-line"></i></span>
                                <input type="date" class="form-control" name="date" value="{{ $date }}">
                            </div>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Month Filter</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                                <input type="month" class="form-control" name="month" value="{{ request('month') }}">
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <label class="fs-12 mb-1">&nbsp;</label>
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" placeholder="Search employee" name="employee_search" value="{{ request('employee_search') }}">
                                <button class="btn btn-secondary" type="submit"><i class="ri-search-line me-1"></i> View</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                @forelse($paginatedItems as $item)
                    @php 
                        $workProfile = $item->employee->workProfiles->first();
                        $firstPunch = ($item->attendance && $item->attendance->details->isNotEmpty()) ? $item->attendance->details->first() : null;
                        $lastPunch = ($item->attendance && $item->attendance->details->isNotEmpty()) ? $item->attendance->details->last() : null;
                        
                        $isAbsent = (!$item->attendance || strtolower($item->attendance->status) == 'absent');
                        $statusText = $isAbsent ? 'Absent' : 'Present';
                        $statusColor = $isAbsent ? 'text-danger' : 'text-success';

                        // Calculate shift times dynamically
                        $policy = $item->employee->policy;
                        $shiftPolicy = $policy ? ($shiftPolicies[$policy->shift_policy_id] ?? null) : null;
                        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));
                        
                        $shiftId = $shiftPolicy ? ($shiftPolicy->{$dayOfWeek.'_shift_id'} ?? $shiftPolicy->default_shift_id) : null;
                        $shift = $shiftId ? ($shifts[$shiftId] ?? null) : null;
                    
                        $shiftStartDisplay = '09:00A'; // fallback
                        $shiftEndDisplay = '06:00P'; // fallback
                        $shiftName = 'Default Shift';
                        $shiftLeft = 10;
                        $shiftWidth = 80;
                    
                        $punchInLeft = 15;
                        $punchWidth = 0;
                        $punchInDisplay = null;
                        $punchOutDisplay = null;
                        
                        $punchMethodIcon = 'ri-computer-line'; // default
                        $punchMethodIn = $firstPunch ? $firstPunch->device_name : null;
                        if ($punchMethodIn == 'Biometric') $punchMethodIcon = 'ri-fingerprint-line text-success';
                        elseif ($punchMethodIn == 'App') $punchMethodIcon = 'ri-smartphone-line text-secondary';
                        elseif ($punchMethodIn == 'Manual') $punchMethodIcon = 'ri-edit-box-line text-warning';

                        if ($shift && $shift->start_time && $shift->end_time) {
                            $shiftName = $shift->name;
                            $shiftStart = \Carbon\Carbon::parse($date . ' ' . $shift->start_time);
                            $shiftEnd = \Carbon\Carbon::parse($date . ' ' . $shift->end_time);
                            if ($shiftEnd->lt($shiftStart)) {
                                $shiftEnd->addDay();
                            }
                            
                            $shiftStartDisplay = $shiftStart->format('h:iA');
                            $shiftEndDisplay = $shiftEnd->format('h:iA');
                    
                            $timelineStart = $shiftStart->copy()->subHours(2);
                            $timelineEnd = $shiftEnd->copy()->addHours(2);
                            $duration = $timelineEnd->timestamp - $timelineStart->timestamp;
                            
                            $shiftLeft = (($shiftStart->timestamp - $timelineStart->timestamp) / $duration) * 100;
                            $shiftWidth = (($shiftEnd->timestamp - $shiftStart->timestamp) / $duration) * 100;
                            
                            if ($firstPunch && $firstPunch->punch_in_time) {
                                $punchIn = \Carbon\Carbon::parse($firstPunch->punch_in_time);
                                $punchInDisplay = $punchIn->format('g:ia');
                                
                                $pInLeft = (($punchIn->timestamp - $timelineStart->timestamp) / $duration) * 100;
                                $punchInLeft = max(0, min(100, $pInLeft));
                                
                                if ($lastPunch && $lastPunch->punch_out_time) {
                                    $punchOut = \Carbon\Carbon::parse($lastPunch->punch_out_time);
                                    $punchOutDisplay = $punchOut->format('g:ia');
                                    if ($punchOut->lt($punchIn)) $punchOut->addDay();
                                    
                                    $pOutLeft = (($punchOut->timestamp - $timelineStart->timestamp) / $duration) * 100;
                                    $pOutLeft = max(0, min(100, $pOutLeft));
                                    
                                    $punchWidth = max(0, $pOutLeft - $punchInLeft);
                                } else {
                                    $punchWidth = 1; // Show a sliver if they haven't punched out yet
                                }
                            }
                        } else {
                            if ($firstPunch && $firstPunch->punch_in_time) {
                                $punchInDisplay = \Carbon\Carbon::parse($firstPunch->punch_in_time)->format('g:ia');
                                $punchInLeft = 20;
                                if ($lastPunch && $lastPunch->punch_out_time) {
                                    $punchOutDisplay = \Carbon\Carbon::parse($lastPunch->punch_out_time)->format('g:ia');
                                    $punchWidth = 60;
                                } else {
                                    $punchWidth = 1;
                                }
                            }
                        }
                    @endphp
                    
                    <div class="card border border-light shadow-none mb-3">
                        <div class="card-body p-3">
                            <!-- Header Row -->
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <h6 class="fs-13 fw-semibold mb-1 text-uppercase">{{ $item->employee->first_name }} {{ $item->employee->last_name }} <span class="text-muted fw-normal">({{ $item->employee->employee_code }})</span></h6>
                                    <div class="fs-12 text-muted">{{ $item->employee->designationInfo->name ?? ($workProfile->designation->name ?? 'Designation N/A') }}</div>
                                </div>
                                <div class="d-flex gap-2 text-muted fs-11">
                                    <span><i class="ri-map-pin-line text-danger align-bottom"></i> {{ $workProfile->location->name ?? 'N/A' }}</span>
                                    <span><i class="ri-money-dollar-circle-line text-warning align-bottom"></i> {{ $workProfile->costCenter->name ?? 'N/A' }}</span>
                                    <span><i class="ri-building-line text-primary align-bottom"></i> {{ $workProfile->department->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <!-- Content Row -->
                            <div class="row align-items-center">
                                <!-- Left: Status info -->
                                <div class="col-md-3">
                                    <div class="fs-12 text-primary">{{ \Carbon\Carbon::parse($date)->format('d-M-Y') }}</div>
                                    <div class="fw-semibold {{ $statusColor }} fs-12">{{ $statusText }}</div>
                                    @if($isAbsent && $statusFilter != 'no_punches' && $firstPunch)
                                    <div class="text-muted fs-11 mt-1">Full-day absent marked due to minimum time-in rule</div>
                                    @endif
                                </div>
                                
                                <!-- Middle: Timeline -->
                                <div class="col-md-7">
                                    <div class="text-center text-muted fs-11 mb-2">{{ $shiftName }}</div>
                                    
                                    <div class="timeline-container">
                                        <!-- Placeholder logic for shifts, since actual shift data wasn't in models -->
                                        <div class="timeline-shift-bar" style="left: {{ $shiftLeft }}%; width: {{ $shiftWidth }}%;"></div>
                                        <div class="timeline-label-start" style="left: {{ $shiftLeft }}%;">{{ $shiftStartDisplay }}</div>
                                        <div class="timeline-label-end" style="left: {{ $shiftLeft + $shiftWidth }}%;">{{ $shiftEndDisplay }}</div>
                                        
                                        @if($firstPunch)
                                            <div class="timeline-worked-bar" style="left: {{ $punchInLeft }}%; width: {{ $punchWidth }}%;"></div>
                                            @if($lastPunch && $lastPunch->punch_out_time)
                                                <div class="timeline-label-punch-in" style="left: {{ $punchInLeft + ($punchWidth/2) }}%; white-space: nowrap;">
                                                    <i class="{{ $punchMethodIcon }} align-middle"></i> {{ $punchInDisplay }} to {{ $punchOutDisplay }}
                                                </div>
                                            @else
                                                <div class="timeline-label-punch-in" style="left: {{ $punchInLeft }}%; white-space: nowrap;">
                                                    <i class="{{ $punchMethodIcon }} align-middle"></i> {{ $punchInDisplay }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Right: Stats & Actions -->
                                <div class="col-md-2 text-end">
                                    <div class="fs-12 text-muted mb-2">
                                        In-Time: 
                                        <span class="fw-semibold text-primary">
                                        @if($item->attendance && $item->attendance->total_working_time)
                                            {{ floor($item->attendance->total_working_time / 60) }}h {{ $item->attendance->total_working_time % 60 }}m
                                        @else
                                            0 m
                                        @endif
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-end gap-1">
                                        <button class="btn btn-sm btn-soft-primary btn-icon btn-view-punches" data-employee="{{ $item->employee->id }}" data-date="{{ $date }}"><i class="ri-eye-fill"></i></button>
                                        <button class="btn btn-sm btn-soft-secondary btn-icon btn-edit-punches"
                                               data-id="{{ $item->attendance->id ?? '' }}"
                                               data-status="{{ $item->attendance->status ?? 'Absent' }}"
                                               data-start="{{ $punchInDisplay ?? '' }}"
                                               data-end="{{ $punchOutDisplay ?? '' }}"><i class="ri-edit-2-fill"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center mt-4">
                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                        <h5 class="mt-2">Sorry! No Result Found</h5>
                    </div>
                @endforelse
                
                <div class="d-flex justify-content-end mt-3">
                    {{ $paginatedItems->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View Punch Details Modal -->
<div class="modal fade" id="viewPunchesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title" id="viewPunchesModalLabel">Punch Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <div class="modal-body">
                <div id="punchDetailsContent" class="text-center py-4">
                    <i class="ri-loader-4-line ri-spin fs-1"></i>
                    <p class="mt-2 text-muted">Loading punch details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

@include('components.attendance-edit-modal')

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // View Punches Logic
        $('.btn-view-punches').on('click', function() {
            var employeeId = $(this).data('employee');
            var date = $(this).data('date');
            
            $('#viewPunchesModal').modal('show');
            $('#punchDetailsContent').html('<i class="ri-loader-4-line ri-spin fs-1"></i><p class="mt-2 text-muted">Loading punch details...</p>');
            
            $.ajax({
                url: "{{ route('attendance.punch-details') }}",
                type: "GET",
                data: {
                    employee_id: employeeId,
                    date: date
                },
                success: function(response) {
                    if(response.success) {
                        var html = '<ul class="list-group list-group-flush border-dashed mb-0">';
                        if(response.data.length > 0) {
                            $.each(response.data, function(index, punch) {
                                var methodIcon = 'ri-computer-line'; // Default
                                if(punch.punch_method == 'Biometric') methodIcon = 'ri-fingerprint-line text-success';
                                if(punch.punch_method == 'App') methodIcon = 'ri-smartphone-line text-secondary';
                                if(punch.punch_method == 'Manual') methodIcon = 'ri-edit-box-line text-warning';
                                
                                html += '<li class="list-group-item d-flex align-items-center justify-content-between">';
                                html += '<div>';
                                html += '<span class="badge badge-soft-success mb-1">IN</span> <span class="fw-medium">' + (punch.punch_in_time || '--') + '</span>';
                                html += '<br><span class="badge badge-soft-danger">OUT</span> <span class="fw-medium">' + (punch.punch_out_time || '--') + '</span>';
                                html += '</div>';
                                html += '<div class="text-end"><i class="' + methodIcon + ' fs-4"></i></div>';
                                html += '</li>';
                            });
                        } else {
                            html += '<li class="list-group-item text-center text-muted py-4">No punch records found for this date.</li>';
                        }
                        html += '</ul>';
                        $('#punchDetailsContent').html(html);
                    } else {
                        $('#punchDetailsContent').html('<p class="text-danger">Failed to load data.</p>');
                    }
                },
                error: function() {
                    $('#punchDetailsContent').html('<p class="text-danger">An error occurred.</p>');
                }
            });
        });

        // Edit Punches Logic
        $('.btn-edit-punches').on('click', function() {
            var id = $(this).data('id');
            var status = $(this).data('status');
            
            if(!id) {
                alert("This employee has no attendance record for this date to edit yet.");
                return;
            }

            $('#editAttendanceId').val(id);
            
            var statusValue = 'Absent';
            if(status.toLowerCase() === 'present') statusValue = 'Present';
            if(status.toLowerCase().includes('half')) statusValue = 'Half Day';
            if(status.toLowerCase() === 'leave') statusValue = 'Leave';
            
            $('#editStatus').val(statusValue);
            
            // Helper to parse '9:00am' to '09:00'
            function parseTime(timeStr) {
                if (!timeStr || timeStr === '--') return '';
                var match = timeStr.match(/(\d+):(\d+)\s*(AM|PM|a|p|am|pm)/i);
                if (!match) return '';
                var hours = parseInt(match[1]);
                var mins = match[2];
                var ampm = match[3].toUpperCase();
                if (ampm.startsWith('P') && hours < 12) hours += 12;
                if (ampm.startsWith('A') && hours === 12) hours = 0;
                return (hours < 10 ? '0' : '') + hours + ':' + mins;
            }

            var start = $(this).data('start');
            var end = $(this).data('end');

            $('#editStart').val(parseTime(start));
            $('#editEnd').val(parseTime(end));
            
            $('#editAttendanceModal').modal('show');
        });
    });
</script>
@endsection
