@extends('layouts.master')

@section('title')
Leave Types
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Leaves & Attendance</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Leave Types</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Leave Types</h4>
            <p class="text-muted fs-13 mb-0">Create and manage leave types, balances, and request rules for your business.</p>
        </div>
        <div class="header-buttons">
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addLeaveTypeModal">
                <i class="ri-add-line"></i> Add Leave Type
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

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="leave-types-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th width="80">SR NO.</th>
                        <th>LEAVE NAME</th>
                        <th>ALIAS</th>
                        <th>COLOR</th>
                        <th>STATUS</th>
                        <th>IS PAID</th>
                        <th>MONTHLY LIMIT</th>
                        <th width="120">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveTypes as $key => $type)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>
                            <span class="fw-medium text-dark">{{ $type->name }}</span>
                            <div class="text-muted fs-12">{{ Str::limit($type->description, 30) }}</div>
                        </td>
                        <td>{{ $type->short_name }}</td>
                        <td>
                            <div style="width: 24px; height: 24px; border-radius: 4px; background-color: {{ $type->color ?? '#007bff' }};" title="{{ $type->color }}"></div>
                        </td>
                        <td>
                            @if($type->status == 'active')
                            <span class="badge bg-success rounded-pill px-3">Active</span>
                            @else
                            <span class="badge bg-secondary rounded-pill px-3">Inactive</span>
                            @endif
                        </td>
                        <td>
                            @if($type->is_paid_leave)
                            <span class="badge bg-primary rounded-pill px-3">Yes</span>
                            @else
                            <span class="badge bg-warning text-dark rounded-pill px-3">No</span>
                            @endif
                        </td>
                        <td>{{ $type->monthly_limit > 0 ? $type->monthly_limit . ' Days' : 'Unlimited' }}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm btn-icon rounded-circle edit-btn" data-id="{{ $type->id }}">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <form action="{{ route('leave-types.destroy', $type->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mx-1" onclick="return confirm('Delete this leave type?')">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="ri-calendar-event-line" style="font-size:2rem;"></i>
                            <br>No Leave Types Found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addLeaveTypeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalTitle">Add Leave Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('leave-types.store') }}" method="POST" id="leaveTypeForm">
                @csrf
                <div id="methodContainer"></div>
                
                <div class="modal-body bg-light">
                    <div class="row">
                        <!-- Left Column (Basic Info) -->
                        <div class="col-md-6 pe-md-4 border-end">
                            <h6 class="fs-13 fw-bold mb-3">Basic Details</h6>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted fs-12 mb-1">Leave Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" id="name" required placeholder="e.g. Casual Leave">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fs-12 mb-1">Short Name (Alias) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="short_name" id="short_name" required placeholder="e.g. CL">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fs-12 mb-1">Color Code</label>
                                    <input type="color" class="form-control form-control-color w-100" name="color" id="color" value="#007bff">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-muted fs-12 mb-1">Description</label>
                                <textarea class="form-control" name="description" id="description" rows="2" placeholder="Optional notes"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted fs-12 mb-2 d-block">Status <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_active" value="active" checked required>
                                    <label class="form-check-label" for="status_active">Active</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="status" id="status_inactive" value="inactive" required>
                                    <label class="form-check-label" for="status_inactive">Inactive</label>
                                </div>
                            </div>

                            <h6 class="fs-13 fw-bold mb-3">Policy Options</h6>

                            <div class="mb-2 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_paid_leave" name="is_paid_leave" value="1" checked>
                                <label class="form-check-label fs-13" for="is_paid_leave">Paid Leave</label>
                            </div>

                            <div class="mb-2 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="maintain_leave_balance" name="maintain_leave_balance" value="1" checked>
                                <label class="form-check-label fs-13" for="maintain_leave_balance">Maintain Leave Balance</label>
                            </div>

                            <div class="mb-2 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="track_balance" name="track_balance" value="1" checked>
                                <label class="form-check-label fs-13" for="track_balance">Track Balance</label>
                            </div>

                            <div class="mb-2 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="allow_leave_requests" name="allow_leave_requests" value="1" checked>
                                <label class="form-check-label fs-13" for="allow_leave_requests">Allow Leave Requests</label>
                            </div>

                            <div class="mb-2 form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="allow_future_requests" name="allow_future_requests" value="1" checked>
                                <label class="form-check-label fs-13" for="allow_future_requests">Allow Future Requests</label>
                            </div>
                        </div>

                        <!-- Right Column (Limits & Rules) -->
                        <div class="col-md-6 ps-md-4">
                            <h6 class="fs-13 fw-bold mb-3">Limits & Rules</h6>
                            
                            <div class="mb-3">
                                <label class="form-label text-muted fs-12 mb-1">Probation Rule</label>
                                <select class="form-select" name="probation_rule" id="probation_rule" required>
                                    <option value="allow">Allowed</option>
                                    <option value="disallow">Not Allowed</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fs-12 mb-1">Advance Leave Days</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="advance_leave_days" id="advance_leave_days" value="0" min="0">
                                        <span class="input-group-text fs-12">Days</span>
                                    </div>
                                    <div class="form-text fs-11">How many days in advance request is allowed.</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fs-12 mb-1">Past Leave Days</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="past_request_days" id="past_request_days" value="0" min="0">
                                        <span class="input-group-text fs-12">Days</span>
                                    </div>
                                    <div class="form-text fs-11">How many days back request is allowed.</div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fs-12 mb-1">Monthly Leave Limit</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="monthly_limit" id="monthly_limit" value="0" min="0">
                                        <span class="input-group-text fs-12">Days</span>
                                    </div>
                                    <div class="form-text fs-11">0 = Unlimited</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted fs-12 mb-1">Request Limit</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="request_limit" id="request_limit" value="0" min="0">
                                        <span class="input-group-text fs-12">Days</span>
                                    </div>
                                    <div class="form-text fs-11">Max days per single request. 0 = No limit.</div>
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
    if($('#leave-types-table').length) {
        $('#leave-types-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[0, 'asc']]
        });
    }

    // Edit Rule AJAX
    $('.edit-btn').on('click', function () {
        let id = $(this).data('id');
        let url = `{{ url('business/setup/attendance/leave-types') }}/${id}/edit`;
        
        $.get(url, function (data) {
            $('#modalTitle').text('Edit Leave Type');
            let formUrl = `{{ url('business/setup/attendance/leave-types') }}/${id}`;
            $('#leaveTypeForm').attr('action', formUrl);
            $('#methodContainer').html('<input type="hidden" name="_method" value="PUT">');

            $('#name').val(data.name);
            $('#short_name').val(data.short_name);
            $('#color').val(data.color || '#007bff');
            $('#description').val(data.description);
            $('input[name="status"][value="' + data.status + '"]').prop('checked', true);

            $('#is_paid_leave').prop('checked', data.is_paid_leave);
            $('#maintain_leave_balance').prop('checked', data.maintain_leave_balance);
            $('#track_balance').prop('checked', data.track_balance);
            $('#allow_leave_requests').prop('checked', data.allow_leave_requests);
            $('#allow_future_requests').prop('checked', data.allow_future_requests);

            $('#probation_rule').val(data.probation_rule);
            $('#advance_leave_days').val(data.advance_leave_days);
            $('#past_request_days').val(data.past_request_days);
            $('#monthly_limit').val(data.monthly_limit);
            $('#request_limit').val(data.request_limit);

            $('#addLeaveTypeModal').modal('show');
        });
    });

    // Reset Modal on Close
    $('#addLeaveTypeModal').on('hidden.bs.modal', function () {
        $('#modalTitle').text('Add Leave Type');
        $('#leaveTypeForm').attr('action', '{{ route('leave-types.store') }}');
        $('#methodContainer').empty();
        $('#leaveTypeForm')[0].reset();
        $('#color').val('#007bff');
        $('input[name="status"][value="active"]').prop('checked', true);
        $('#is_paid_leave, #maintain_leave_balance, #track_balance, #allow_leave_requests, #allow_future_requests').prop('checked', true);
    });
});
</script>
@endsection
