@extends('layouts.master')
@section('title') Daily Punches @endsection
@section('content')

@component('components.breadcrumb')
@slot('li_1') Attendance @endslot
@slot('title') Daily Punches @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card" id="dailyPunchesList">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Daily Punches</h5>
                </div>
                <p class="text-muted mb-0">View or edit daily time punches for a single date.</p>
            </div>
            <div class="card-body bg-soft-light border border-dashed border-start-0 border-end-0">
                <form action="{{ route('attendance.daily-punches') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-6">
                            <label>Business Unit</label>
                            <select class="form-control" data-choices name="business_unit_id" id="business_unit_id">
                                <option value="">All Units</option>
                                @foreach($businessUnits as $unit)
                                    <option value="{{ $unit->id }}" {{ request('business_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label>Location</label>
                            <select class="form-control" data-choices name="location_id" id="location_id">
                                <option value="">All Locations</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location->id }}" {{ request('location_id') == $location->id ? 'selected' : '' }}>{{ $location->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label>Cost Center</label>
                            <select class="form-control" data-choices name="cost_center_id" id="cost_center_id">
                                <option value="">All Cost Centers</option>
                                @foreach($costCenters as $center)
                                    <option value="{{ $center->id }}" {{ request('cost_center_id') == $center->id ? 'selected' : '' }}>{{ $center->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label>Department</label>
                            <select class="form-control" data-choices name="department_id" id="department_id">
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
                                <label class="form-check-label" for="status_all">Show All</label>
                            </div>
                            <div class="form-check form-radio-warning mb-3 me-3">
                                <input class="form-check-input" type="radio" name="status_filter" id="status_late" value="late" {{ $statusFilter == 'late' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_late">Late Coming Only</label>
                            </div>
                            <div class="form-check form-radio-danger mb-3 me-3">
                                <input class="form-check-input" type="radio" name="status_filter" id="status_absent" value="absent" {{ $statusFilter == 'absent' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_absent">Absent Only</label>
                            </div>
                            <div class="form-check form-radio-secondary mb-3 me-3">
                                <input class="form-check-input" type="radio" name="status_filter" id="status_no_punches" value="no_punches" {{ $statusFilter == 'no_punches' ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_no_punches">No Punches</label>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-xxl-3 col-sm-6">
                            <label>Date Filter</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-calendar-2-line"></i></span>
                                <input type="date" class="form-control" name="date" value="{{ $date }}">
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <label>Month Filter (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-calendar-event-line"></i></span>
                                <input type="month" class="form-control" name="month" value="{{ request('month') }}">
                            </div>
                        </div>
                        <div class="col-xxl-4 col-sm-12">
                            <label>&nbsp;</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search employee" name="employee_search" value="{{ request('employee_search') }}">
                                <button class="btn btn-primary" type="submit"><i class="ri-search-line me-1"></i> View</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="table-responsive table-card mb-1">
                    <table class="table align-middle table-nowrap" id="employeeTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th style="width: 50px;">SN</th>
                                <th>EMPLOYEE</th>
                                <th>DESIGNATION</th>
                                <th>START</th>
                                <th>END</th>
                                <th>DURATION</th>
                                <th>ATTENDANCE</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody class="list form-check-all">
                            @forelse($paginatedItems as $index => $item)
                            <tr>
                                <td>{{ $paginatedItems->firstItem() + $index }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">{{ $item->employee->first_name }} {{ $item->employee->last_name }}</h5>
                                            <p class="text-muted mb-0">{{ $item->employee->employee_code }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $item->employee->designationInfo->name ?? ($item->employee->workProfiles->first()->designation->name ?? 'N/A') }}</td>
                                
                                @if($item->attendance && $item->attendance->details->isNotEmpty())
                                    @php 
                                        $firstPunch = $item->attendance->details->first();
                                        $lastPunch = $item->attendance->details->last();
                                        $startTime = $firstPunch->punch_in_time ? \Carbon\Carbon::parse($firstPunch->punch_in_time)->format('h:i A') : '--';
                                        $endTime = $lastPunch->punch_out_time ? \Carbon\Carbon::parse($lastPunch->punch_out_time)->format('h:i A') : '--';
                                    @endphp
                                    <td>{{ $startTime }}</td>
                                    <td>{{ $endTime }}</td>
                                    <td>
                                        @if($item->attendance->total_working_time)
                                            {{ floor($item->attendance->total_working_time / 60) }}:{{ sprintf('%02d', $item->attendance->total_working_time % 60) }}
                                        @else
                                            0:00
                                        @endif
                                    </td>
                                @else
                                    <td>--</td>
                                    <td>--</td>
                                    <td>0:00</td>
                                @endif

                                <td>
                                    @if($item->attendance && strtolower($item->attendance->status) == 'present')
                                        <span class="badge bg-success fs-12">P</span>
                                    @elseif($item->attendance && strtolower($item->attendance->status) == 'absent')
                                        <span class="badge bg-danger fs-12">A</span>
                                    @elseif($item->attendance && in_array(strtolower($item->attendance->status), ['half day', 'half_day']))
                                        <span class="badge bg-warning fs-12">PA</span>
                                    @else
                                        <span class="badge bg-danger fs-12">A</span>
                                    @endif
                                </td>
                                <td>
                                    <ul class="list-inline hstack gap-2 mb-0">
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0);" class="text-primary d-inline-block btn-view-punches" data-employee="{{ $item->employee->id }}" data-date="{{ $date }}">
                                                <i class="ri-eye-fill fs-16"></i>
                                            </a>
                                        </li>
                                        <li class="list-inline-item">
                                            <a href="javascript:void(0);" class="text-secondary d-inline-block btn-edit-punches" 
                                               data-id="{{ $item->attendance->id ?? '' }}"
                                               data-status="{{ $item->attendance->status ?? 'Absent' }}"
                                               data-start="{{ $startTime ?? '' }}"
                                               data-end="{{ $endTime ?? '' }}">
                                                <i class="ri-edit-2-fill fs-16"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8">
                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">Sorry! No Result Found</h5>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $paginatedItems->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            </div>
            
            <div class="card-footer bg-light border-0 text-muted">
                <div class="d-flex flex-wrap gap-2 fs-12 align-items-center">
                    <strong>Punch Legend:</strong>
                    <span class="badge badge-soft-primary"><i class="ri-computer-line me-1"></i> Web</span>
                    <span class="badge badge-soft-secondary"><i class="ri-smartphone-line me-1"></i> App</span>
                    <span class="badge badge-soft-info"><i class="ri-qr-code-line me-1"></i> QR Scan</span>
                    <span class="badge badge-soft-success"><i class="ri-fingerprint-line me-1"></i> Biometric</span>
                    <span class="badge badge-soft-warning"><i class="ri-edit-box-line me-1"></i> Manual</span>
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

<!-- Edit Attendance Modal -->
<div class="modal fade" id="editAttendanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Edit Attendance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('attendance.update-punches') }}" method="POST" id="editAttendanceForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="attendance_id" id="editAttendanceId">
                    
                    <div class="mb-3">
                        <label for="editStatus" class="form-label">Attendance Status</label>
                        <select name="status" id="editStatus" class="form-select" required>
                            <option value="Present">Present</option>
                            <option value="Half Day">Half Day</option>
                            <option value="Absent">Absent</option>
                            <option value="Leave">Leave</option>
                        </select>
                    </div>

                    <div class="row g-3">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="editStart" class="form-label">Start Time</label>
                                <input type="time" class="form-control" name="punch_in_time" id="editStart">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label for="editEnd" class="form-label">End Time</label>
                                <input type="time" class="form-control" name="punch_out_time" id="editEnd">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
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
            
            // Map 'PA' back to 'Half Day' or similar if needed, but the status string from DB should be used
            // We ensure it matches the dropdown options
            var statusValue = 'Absent';
            if(status.toLowerCase() === 'present') statusValue = 'Present';
            if(status.toLowerCase().includes('half')) statusValue = 'Half Day';
            if(status.toLowerCase() === 'leave') statusValue = 'Leave';
            
            $('#editStatus').val(statusValue);
            
            // Helper to parse '09:00 AM' to '09:00'
            function parseTime(timeStr) {
                if (!timeStr || timeStr === '--') return '';
                var match = timeStr.match(/(\d+):(\d+)\s*(AM|PM)/i);
                if (!match) return '';
                var hours = parseInt(match[1]);
                var mins = match[2];
                var ampm = match[3].toUpperCase();
                if (ampm === 'PM' && hours < 12) hours += 12;
                if (ampm === 'AM' && hours === 12) hours = 0;
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
