@extends('layouts.master')

@section('title')
Work Shifts
@endsection

@section('css')

<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">

{{-- jQuery loaded in head so it is available when @section('script') runs --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

@endsection


@section('content')

<div class="helpdesk-header">

    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Attendance</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Work Shifts</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Work Shifts</h4>
        </div>

        <div class="header-buttons">

            <button
                type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addShiftModal">

                <i class="ri-add-line"></i>
                Add New

            </button>

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


{{-- =========================================================
     Shifts Table
     ========================================================= --}}
<div class="card">

    <div class="card-body">

        <div class="table-responsive">

            <table
                id="shifts-table"
                class="table table-bordered table-striped dt-responsive nowrap align-middle"
                style="width:100%">

                <thead class="table-light">

                <tr>
                    <th>SR NO.</th>
                    <th>CODE</th>
                    <th>NAME</th>
                    <th>START TIME</th>
                    <th>END TIME</th>
                    <th>DURATION</th>
                    <th>PAYABLE HRS</th>
                    <th>DEFAULT</th>
                    <th width="120">ACTIONS</th>
                </tr>

                </thead>

                <tbody>

                @forelse($shifts as $key => $shift)

                <tr>

                    <td>{{ $key + 1 }}</td>

                    <td><span class="badge bg-secondary">{{ $shift->code }}</span></td>

                    <td>{{ $shift->name }}</td>

                    <td>{{ \Carbon\Carbon::parse($shift->start_time)->format('h:i A') }}</td>

                    <td>{{ \Carbon\Carbon::parse($shift->end_time)->format('h:i A') }}</td>

                    <td>
                        {{ $shift->duration_hours }}h
                        {{ $shift->duration_minutes }}m
                    </td>

                    <td>
                        {{ $shift->payable_hours }}h
                        {{ $shift->payable_minutes }}m
                    </td>

                    <td>
                        @if($shift->is_default)
                            <span class="badge bg-success">Yes</span>
                        @else
                            <span class="badge bg-light text-dark">No</span>
                        @endif
                    </td>

                    <td>

                        <div class="dropdown d-inline-block">

                            <button
                                class="btn btn-soft-secondary btn-sm"
                                type="button"
                                data-bs-toggle="dropdown">

                                <i class="ri-more-fill align-middle"></i>

                            </button>

                            <ul class="dropdown-menu dropdown-menu-end">

                                <li>

                                    <button
                                        type="button"
                                        class="dropdown-item editShiftBtn"

                                        data-id="{{ $shift->id }}"
                                        data-code="{{ $shift->code }}"
                                        data-name="{{ $shift->name }}"
                                        data-start_time="{{ $shift->start_time }}"
                                        data-duration_hours="{{ $shift->duration_hours }}"
                                        data-duration_minutes="{{ $shift->duration_minutes }}"
                                        data-payable_hours="{{ $shift->payable_hours }}"
                                        data-payable_hours="{{ $shift->payable_hours }}"
                                        data-payable_minutes="{{ $shift->payable_minutes }}"
                                        data-shift_type="{{ $shift->shift_type }}"
                                        data-break_time_minutes="{{ $shift->break_time_minutes }}"
                                        data-grace_time_minutes="{{ $shift->grace_time_minutes }}"
                                        data-min_working_hours="{{ $shift->min_working_hours }}"
                                        data-max_working_hours="{{ $shift->max_working_hours }}"
                                        data-color="{{ $shift->color }}"
                                        data-status="{{ $shift->status }}"
                                        data-is_default="{{ $shift->is_default ? 1 : 0 }}">

                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>
                                        Edit

                                    </button>

                                </li>

                                <li>

                                    <form
                                        action="{{ route('shifts.destroy', $shift->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="dropdown-item text-danger"
                                            onclick="return confirm('Delete this shift?')">

                                            <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                            Delete

                                        </button>

                                    </form>

                                </li>

                            </ul>

                        </div>

                    </td>

                </tr>

                @empty

                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="ri-time-line" style="font-size:2rem;"></i>
                        <br>No Work Shifts Found. Click <strong>Add New</strong> to create one.
                    </td>
                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================================================
     Add Shift Modal
     ========================================================= --}}
<div class="modal fade" id="addShiftModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form action="{{ route('shifts.store') }}" method="POST">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="ri-time-line me-1"></i> Add Work Shift
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        {{-- Code --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Shift Code <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control @error('code') is-invalid @enderror"
                                name="code"
                                placeholder="e.g. GS-9"
                                value="{{ old('code') }}"
                                required>

                            @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Name --}}
                        <div class="col-md-8 mb-3">

                            <label class="form-label">
                                Shift Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                name="name"
                                placeholder="e.g. General Shift"
                                value="{{ old('name') }}"
                                required>

                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                    </div>

                    <div class="row">

                        {{-- Start Time --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Start Time <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                class="form-control @error('start_time') is-invalid @enderror"
                                name="start_time"
                                value="{{ old('start_time', '09:00') }}"
                                required>

                            @error('start_time')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                        </div>

                        {{-- Duration --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">Duration</label>

                            <div class="d-flex gap-2">

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="duration_hours"
                                        placeholder="Hrs"
                                        min="0" max="23"
                                        value="{{ old('duration_hours', 9) }}">
                                    <span class="input-group-text">h</span>
                                </div>

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="duration_minutes"
                                        placeholder="Min"
                                        min="0" max="59"
                                        value="{{ old('duration_minutes', 0) }}">
                                    <span class="input-group-text">m</span>
                                </div>

                            </div>

                        </div>

                        {{-- Payable Hours --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">Payable Hours</label>

                            <div class="d-flex gap-2">

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="payable_hours"
                                        placeholder="Hrs"
                                        min="0" max="23"
                                        value="{{ old('payable_hours', 9) }}">
                                    <span class="input-group-text">h</span>
                                </div>

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        name="payable_minutes"
                                        placeholder="Min"
                                        min="0" max="59"
                                        value="{{ old('payable_minutes', 0) }}">
                                    <span class="input-group-text">m</span>
                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- End Time Display Row --}}
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">
                                End Time: <strong class="text-dark fs-14 ms-2 end-time-display">--:--</strong>
                            </label>
                        </div>
                    </div>

                    {{-- New Enterprise Fields --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Shift Type</label>
                            <select class="form-select" name="shift_type">
                                <option value="General">General</option>
                                <option value="Rotational">Rotational</option>
                                <option value="Flexible">Flexible</option>
                                <option value="Night">Night</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Break Time (Mins)</label>
                            <input type="number" class="form-control" name="break_time_minutes" value="0" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Grace Time (Mins)</label>
                            <input type="number" class="form-control" name="grace_time_minutes" value="0" min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Min Working Hrs</label>
                            <input type="number" class="form-control" name="min_working_hours" value="0" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Working Hrs</label>
                            <input type="number" class="form-control" name="max_working_hours" value="0" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Shift Color</label>
                            <input type="color" class="form-control form-control-color w-100" name="color" value="#556ee6" title="Choose your color">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    {{-- Default Toggle --}}
                    <div class="form-check form-switch mt-1">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="is_default"
                            id="add_is_default"
                            value="1"
                            {{ old('is_default') ? 'checked' : '' }}>

                        <label class="form-check-label" for="add_is_default">
                            Set as Default Shift
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Save
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =========================================================
     Edit Shift Modal
     ========================================================= --}}
<div class="modal fade" id="editShiftModal" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <form id="editShiftForm" method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="ri-edit-line me-1"></i> Edit Work Shift
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        {{-- Code --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Shift Code <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="edit_code"
                                name="code"
                                required>

                        </div>

                        {{-- Name --}}
                        <div class="col-md-8 mb-3">

                            <label class="form-label">
                                Shift Name <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="edit_name"
                                name="name"
                                required>

                        </div>

                    </div>

                    <div class="row">

                        {{-- Start Time --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Start Time <span class="text-danger">*</span>
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="edit_start_time"
                                name="start_time"
                                required>

                        </div>

                        {{-- Duration --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">Duration</label>

                            <div class="d-flex gap-2">

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="edit_duration_hours"
                                        name="duration_hours"
                                        min="0" max="23">
                                    <span class="input-group-text">h</span>
                                </div>

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="edit_duration_minutes"
                                        name="duration_minutes"
                                        min="0" max="59">
                                    <span class="input-group-text">m</span>
                                </div>

                            </div>

                        </div>

                        {{-- Payable Hours --}}
                        <div class="col-md-4 mb-3">

                            <label class="form-label">Payable Hours</label>

                            <div class="d-flex gap-2">

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="edit_payable_hours"
                                        name="payable_hours"
                                        min="0" max="23">
                                    <span class="input-group-text">h</span>
                                </div>

                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="edit_payable_minutes"
                                        name="payable_minutes"
                                        min="0" max="59">
                                    <span class="input-group-text">m</span>
                                </div>

                            </div>

                    {{-- End Time Display Row --}}
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-medium text-muted">
                                End Time: <strong class="text-dark fs-14 ms-2 end-time-display">--:--</strong>
                            </label>
                        </div>
                    </div>

                    {{-- New Enterprise Fields --}}
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Shift Type</label>
                            <select class="form-select" id="edit_shift_type" name="shift_type">
                                <option value="General">General</option>
                                <option value="Rotational">Rotational</option>
                                <option value="Flexible">Flexible</option>
                                <option value="Night">Night</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Break Time (Mins)</label>
                            <input type="number" class="form-control" id="edit_break_time_minutes" name="break_time_minutes" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Grace Time (Mins)</label>
                            <input type="number" class="form-control" id="edit_grace_time_minutes" name="grace_time_minutes" min="0">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Min Working Hrs</label>
                            <input type="number" class="form-control" id="edit_min_working_hours" name="min_working_hours" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Max Working Hrs</label>
                            <input type="number" class="form-control" id="edit_max_working_hours" name="max_working_hours" min="0">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Shift Color</label>
                            <input type="color" class="form-control form-control-color w-100" id="edit_color" name="color" title="Choose your color">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                        </div>

                    </div>

                    {{-- Default Toggle --}}
                    <div class="form-check form-switch mt-1">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="edit_is_default"
                            name="is_default"
                            value="1">

                        <label class="form-check-label" for="edit_is_default">
                            Set as Default Shift
                        </label>

                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="ri-save-line me-1"></i> Update
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

    $('#shifts-table').DataTable({
        responsive: true,
        pageLength: 10,
        order: [[0, 'asc']]
    });

});


/*
|----------------------------------------------------------
| Edit Shift — populate modal from data attributes
|----------------------------------------------------------
*/
$(document).on('click', '.editShiftBtn', function () {

    var id             = $(this).data('id');
    var code           = $(this).data('code');
    var name           = $(this).data('name');
    var startTime      = $(this).data('start_time');
    var durationHours  = $(this).data('duration_hours');
    var durationMins   = $(this).data('duration_minutes');
    var payableHours   = $(this).data('payable_hours');
    var payableMins    = $(this).data('payable_minutes');
    var shiftType      = $(this).data('shift_type');
    var breakMins      = $(this).data('break_time_minutes');
    var graceMins      = $(this).data('grace_time_minutes');
    var minHrs         = $(this).data('min_working_hours');
    var maxHrs         = $(this).data('max_working_hours');
    var color          = $(this).data('color');
    var status         = $(this).data('status');
    var isDefault      = $(this).data('is_default');

    $('#edit_code').val(code);
    $('#edit_name').val(name);

    // Time input needs HH:MM format (strip seconds if present)
    if (startTime && startTime.length > 5) {
        startTime = startTime.substring(0, 5);
    }
    $('#edit_start_time').val(startTime);

    $('#edit_duration_hours').val(durationHours);
    $('#edit_duration_minutes').val(durationMins);
    $('#edit_payable_hours').val(payableHours);
    $('#edit_payable_minutes').val(payableMins);
    $('#edit_shift_type').val(shiftType);
    $('#edit_break_time_minutes').val(breakMins);
    $('#edit_grace_time_minutes').val(graceMins);
    $('#edit_min_working_hours').val(minHrs);
    $('#edit_max_working_hours').val(maxHrs);
    $('#edit_color').val(color);
    $('#edit_status').val(status);
    $('#edit_is_default').prop('checked', isDefault == 1);

    // Set form action dynamically
    $('#editShiftForm').attr(
        'action',
        '{{ route("shifts.update", ":id") }}'.replace(':id', id)
    );

    calculateEndTime($('#editShiftForm'));

    $('#editShiftModal').modal('show');

});

/*
|----------------------------------------------------------
| Auto Calculate End Time
|----------------------------------------------------------
*/
function calculateEndTime(form) {
    let startVal = form.find('[name="start_time"]').val();
    let hours = parseInt(form.find('[name="duration_hours"]').val()) || 0;
    let mins = parseInt(form.find('[name="duration_minutes"]').val()) || 0;

    let displayEl = form.find('.end-time-display');

    if(!startVal) {
        displayEl.text('--:--');
        return;
    }

    let parts = startVal.split(':');
    let date = new Date();
    date.setHours(parseInt(parts[0]));
    date.setMinutes(parseInt(parts[1]));

    date.setHours(date.getHours() + hours);
    date.setMinutes(date.getMinutes() + mins);

    let endH = date.getHours();
    let endM = date.getMinutes();
    let ampm = endH >= 12 ? 'PM' : 'AM';
    endH = endH % 12;
    endH = endH ? endH : 12;

    let formattedM = endM < 10 ? '0'+endM : endM;
    let formattedH = endH < 10 ? '0'+endH : endH;

    displayEl.text(`${formattedH}:${formattedM} ${ampm}`);
}

$(document).on('change input', '[name="start_time"], [name="duration_hours"], [name="duration_minutes"]', function() {
    calculateEndTime($(this).closest('form'));
});

// Run calculation initially for the Add modal (which has default values)
$(document).ready(function() {
    calculateEndTime($('#addShiftModal form'));
});

</script>

@endsection
