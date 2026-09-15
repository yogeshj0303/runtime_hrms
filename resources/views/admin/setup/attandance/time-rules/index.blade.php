@extends('layouts.master')

@section('title')
Time Rules
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .time-range-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .time-range-group select {
        width: 80px;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Attendance & Leaves</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Time Rules</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Time Rules <span class="badge bg-warning text-dark fs-10 ms-1">New</span></h4>
            <p class="text-muted fs-13 mb-0">Manage late coming, early going and other events and create automated rules to send warning and update attendance</p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-light btn-sm border me-2"><i class="ri-upload-2-line"></i> Easy Add</button>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addTimeRuleModal">
                <i class="ri-add-line"></i> Add Rule
            </button>
            <button class="btn btn-success btn-sm ms-2 border-0"><i class="ri-book-read-line"></i> Read Help</button>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="time-rules-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th width="80">RULES</th>
                        <th>TIME SETTINGS</th>
                        <th>STATUS</th>
                        <th>WARNING EVENTS</th>
                        <th>ATTENDANCE EVENTS</th>
                        <th width="120">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($timeRules as $rule)
                    <tr>
                        <td>
                            <span class="fw-medium text-dark">{{ $rule->id }}</span>
                        </td>
                        <td>
                            <strong>{{ $rule->time_basis }}</strong><br>
                            <span class="text-muted fs-12">
                                {{ str_pad($rule->from_hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($rule->from_minutes, 2, '0', STR_PAD_LEFT) }} 
                                to 
                                {{ str_pad($rule->to_hours, 2, '0', STR_PAD_LEFT) }}:{{ str_pad($rule->to_minutes, 2, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td>
                            @if($rule->is_active)
                            <span class="badge bg-success rounded-pill px-3">Active</span>
                            @else
                            <span class="badge bg-secondary rounded-pill px-3">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($rule->warning_occurrences)
                            <span class="badge bg-warning text-dark rounded-pill">{{ $rule->warning_occurrences }}</span><br>
                            <span class="text-muted fs-12">Letter: {{ $rule->warning_letter ?? '-' }}</span>
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            @if($rule->attendance_occurrences)
                            <span class="badge bg-danger rounded-pill">{{ $rule->attendance_occurrences }}</span><br>
                            <span class="text-muted fs-12">Update: {{ $rule->attendance_status }} 
                                @if($rule->attendance_update_type)
                                    ({{ $rule->attendance_update_type }})
                                @endif
                            </span>
                            @else
                            -
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm btn-icon rounded-circle edit-btn" data-id="{{ $rule->id }}">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <button type="button" class="btn btn-success btn-sm btn-icon rounded-circle mx-1">
                                <i class="ri-file-copy-line"></i>
                            </button>
                            <form action="{{ route('time-rules.destroy', $rule->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle" onclick="return confirm('Delete this rule?')">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="ri-calendar-event-line" style="font-size:2rem;"></i>
                            <br>No Time Rules Found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 pt-3 border-top">
            <div class="d-flex align-items-center mb-2">
                <i class="ri-error-warning-fill text-danger me-2"></i> 
                <span class="fs-13 text-muted">Possible duplicate/overlapping rule.</span>
            </div>
            <div class="d-flex align-items-center mb-2">
                <i class="ri-information-fill text-warning me-2"></i> 
                <span class="fs-13 text-muted">Migrated from Shift Rules.</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="ri-information-fill text-primary me-2"></i> 
                <span class="fs-13 text-muted">Migrated from Strike Rules.</span>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addTimeRuleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalTitle">Add Time Rule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('time-rules.store') }}" method="POST" id="timeRuleForm">
                @csrf
                <div id="methodContainer"></div>
                <div class="modal-body bg-light">
                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6 pe-md-4 border-end">
                            <div class="mb-4">
                                <label class="form-label text-muted fs-12 mb-1">Time Basis <span class="text-danger">*</span></label>
                                <select class="form-select" name="time_basis" id="time_basis" required>
                                    <option value="Late Coming">Late Coming</option>
                                    <option value="Early Going">Early Going</option>
                                    <option value="Total Time In">Total Time In</option>
                                    <option value="Net Late">Net Late (Late Coming + Early Going)</option>
                                    <option value="Early Coming">Early Coming</option>
                                    <option value="Late Going">Late Going</option>
                                    <option value="Late Lunch">Late Lunch</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted fs-12 mb-1">Time Range <span class="text-danger">*</span></label>
                                <div class="time-range-group mb-2">
                                    <select class="form-select" name="from_hours" id="from_hours" required>
                                        @for($i=0; $i<=23; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-muted fs-12">hrs</span>
                                    <select class="form-select" name="from_minutes" id="from_minutes" required>
                                        @for($i=0; $i<=59; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-muted fs-12">mins to</span>
                                </div>
                                <div class="time-range-group">
                                    <select class="form-select" name="to_hours" id="to_hours" required>
                                        @for($i=0; $i<=23; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-muted fs-12">hrs</span>
                                    <select class="form-select" name="to_minutes" id="to_minutes" required>
                                        @for($i=0; $i<=59; $i++)
                                        <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</option>
                                        @endfor
                                    </select>
                                    <span class="text-muted fs-12">mins</span>
                                </div>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="mark_only_if_present" name="mark_only_if_present" value="1">
                                <label class="form-check-label fs-13" for="mark_only_if_present">Mark Only If Present <i class="ri-information-line text-primary"></i></label>
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label fs-13" for="is_active">Set Active</label>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6 ps-md-4">
                            <!-- Send Warning Card -->
                            <div class="card shadow-none border mb-4">
                                <div class="card-body bg-white rounded p-3">
                                    <h6 class="fs-13 fw-bold mb-3">Send Warning</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted fs-12 mb-1">Enter # of occurrences separated by comma</label>
                                        <input type="text" class="form-control form-control-sm" id="warning_occurrences" name="warning_occurrences" placeholder="">
                                        <div class="form-text fs-11 mt-1 text-danger">Value or range separated by comma. Ex. 1, 2, 3, 4-8</div>
                                    </div>

                                    <div>
                                        <label class="form-label text-muted fs-12 mb-1">Select warning letter to issue</label>
                                        <select class="form-select form-select-sm" id="warning_letter" name="warning_letter">
                                            <option value="">- Select Letter -</option>
                                            @foreach($letterTemplates as $template)
                                                <option value="{{ $template->title }}">{{ $template->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Update Attendance Card -->
                            <div class="card shadow-none border mb-0" style="background-color: #fdf5f5;">
                                <div class="card-body p-3">
                                    <h6 class="fs-13 fw-bold mb-3">Update Attendance</h6>
                                    
                                    <div class="mb-3">
                                        <label class="form-label text-muted fs-12 mb-1">Enter # of occurrences separated by comma</label>
                                        <input type="text" class="form-control form-control-sm" id="attendance_occurrences" name="attendance_occurrences" placeholder="">
                                        <div class="form-text fs-11 mt-1 text-danger">Value or range separated by comma. Ex. 1, 2, 3, 4-8</div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted fs-12 mb-1">Select attendance to update</label>
                                        <select class="form-select form-select-sm" id="attendance_status" name="attendance_status">
                                            <option value="">- Select Attendance -</option>
                                            <option value="Present">Present</option>
                                            <option value="Absent">Absent</option>
                                            <option value="Holiday">Holiday</option>
                                            <option value="Week Off">Week Off</option>
                                            <option value="Comp Off">Comp Off</option>
                                            <option value="Casual Leave">Casual Leave</option>
                                            <option value="Unpaid Leave">Unpaid Leave</option>
                                            <option value="Tour Training">Tour Training</option>
                                        </select>
                                    </div>

                                    <div class="d-flex gap-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="attendance_update_type" id="type_full" value="Full Day">
                                            <label class="form-check-label fs-13" for="type_full">Full Day</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="attendance_update_type" id="type_half" value="Half Day" checked>
                                            <label class="form-check-label fs-13 text-primary" for="type_half">Half Day</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top bg-white py-2">
                    <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="ri-save-line me-1"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function () {
    if($('#time-rules-table').length) {
        $('#time-rules-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']]
        });
    }

    // Edit Rule AJAX
    $('.edit-btn').on('click', function () {
        let id = $(this).data('id');
        let url = `{{ url('business/setup/attendance/time-rules') }}/${id}/edit`;
        
        $.get(url, function (data) {
            $('#modalTitle').text('Edit Time Rule');
            let formUrl = `{{ url('business/setup/attendance/time-rules') }}/${id}`;
            $('#timeRuleForm').attr('action', formUrl);
            $('#methodContainer').html('<input type="hidden" name="_method" value="PUT">');

            $('#time_basis').val(data.time_basis);
            $('#from_hours').val(data.from_hours);
            $('#from_minutes').val(data.from_minutes);
            $('#to_hours').val(data.to_hours);
            $('#to_minutes').val(data.to_minutes);

            $('#mark_only_if_present').prop('checked', data.mark_only_if_present);
            $('#is_active').prop('checked', data.is_active);

            $('#warning_occurrences').val(data.warning_occurrences);
            $('#warning_letter').val(data.warning_letter);

            $('#attendance_occurrences').val(data.attendance_occurrences);
            $('#attendance_status').val(data.attendance_status);
            
            if(data.attendance_update_type) {
                $(`input[name="attendance_update_type"][value="${data.attendance_update_type}"]`).prop('checked', true);
            }

            $('#addTimeRuleModal').modal('show');
        });
    });

    // Reset Modal on Close
    $('#addTimeRuleModal').on('hidden.bs.modal', function () {
        $('#modalTitle').text('Add Time Rule');
        $('#timeRuleForm').attr('action', '{{ route('time-rules.store') }}');
        $('#methodContainer').empty();
        $('#timeRuleForm')[0].reset();
    });
});
</script>
@endsection
