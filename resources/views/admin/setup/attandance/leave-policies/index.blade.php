@extends('layouts.master')

@section('title')
Leave Policies
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .grid-container {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-top: 15px;
    }
    .grid-item {
        display: flex;
        flex-direction: column;
    }
    .grid-item label {
        font-size: 11px;
        margin-bottom: 2px;
        color: #6c757d;
    }
    .grid-item input {
        font-size: 13px;
        padding: 4px 8px;
    }
    .policy-box {
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        height: 100%;
    }
    .grant-box {
        border: 2px solid #0dcaf0;
        background-color: #f8ffff;
    }
    .lapse-box {
        border: 2px solid #fd7e14;
        background-color: #fff9f4;
    }
    .options-box {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        background-color: #fff;
    }
    .faq-section {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-top: 30px;
    }
    .faq-item {
        border-bottom: 1px solid #e9ecef;
        padding: 12px 0;
        display: flex;
        justify-content: space-between;
        color: #495057;
        font-size: 13px;
        cursor: pointer;
    }
    .faq-item:last-child {
        border-bottom: none;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Leaves & Attendance</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Leave Policies</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Leave Policies</h4>
            <p class="text-muted fs-13 mb-0">Create leave policies to auto-grant and lapse rules. Assign policies to employees from 'Policies' section.</p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-outline-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#recalculateModal">
                <i class="ri-refresh-line"></i> Recalculate
            </button>
            <button class="btn-add" data-bs-toggle="modal" data-bs-target="#addLeavePolicyModal">
                <i class="ri-add-line"></i> Add Leave Policy
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
            <table id="leave-policies-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>LEAVE TYPE</th>
                        <th>POLICY DESCRIPTION</th>
                        <th>GRANT ENABLED</th>
                        <th>LAPSE ENABLED</th>
                        <th width="120">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leavePolicies as $policy)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div style="width: 16px; height: 16px; border-radius: 50%; background-color: {{ $policy->leaveType->color ?? '#ccc' }}; margin-right: 8px;"></div>
                                <span class="fw-medium">{{ $policy->leaveType->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td>{{ $policy->policy_description }}</td>
                        <td>
                            @if($policy->grant_leaves)
                            <span class="badge bg-success rounded-pill px-3">Yes</span>
                            @else
                            <span class="badge bg-secondary rounded-pill px-3">No</span>
                            @endif
                        </td>
                        <td>
                            @if($policy->lapse_leaves)
                            <span class="badge bg-success rounded-pill px-3">Yes</span>
                            @else
                            <span class="badge bg-secondary rounded-pill px-3">No</span>
                            @endif
                        </td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm btn-icon rounded-circle edit-btn" data-id="{{ $policy->id }}">
                                <i class="ri-pencil-fill"></i>
                            </button>
                            <form action="{{ route('leave-policies.destroy', $policy->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon rounded-circle mx-1" onclick="return confirm('Delete this leave policy?')">
                                    <i class="ri-delete-bin-fill"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            <i class="ri-file-list-3-line" style="font-size:2rem;"></i>
                            <br>No Leave Policies Found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="faq-section mt-5">
            <h6 class="fw-bold mb-3"><i class="ri-lightbulb-flash-line text-warning me-1"></i> Frequently Asked Questions</h6>
            <div class="faq-item">
                <span>How are leaves granted in the system?</span>
                <i class="ri-add-line"></i>
            </div>
            <div class="faq-item">
                <span>Can different leave policies be assigned to different employee groups?</span>
                <i class="ri-add-line"></i>
            </div>
            <div class="faq-item">
                <span>Can unused leaves be carried forward to the next year?</span>
                <i class="ri-add-line"></i>
            </div>
            <div class="faq-item">
                <span>Can leave eligibility be restricted for probationary employees?</span>
                <i class="ri-add-line"></i>
            </div>
        </div>

    </div>
</div>

<!-- Recalculate Modal -->
<div class="modal fade" id="recalculateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Recalculate Policies</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('leave-policies.recalculate') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="fw-bold mb-1 fs-13">What is this?</p>
                    <p class="text-muted fs-12 mb-4">Use this function to recalculate leave grant and lapse numbers, in case you have changed the leave policies.</p>
                    
                    <label class="form-label fs-12">Select Period</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="ri-calendar-2-line"></i></span>
                        <input type="month" class="form-control" name="recalculate_period" required value="{{ date('Y-m') }}">
                        <span class="input-group-text"><i class="ri-arrow-down-s-line"></i></span>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary px-4"><i class="ri-play-fill me-1"></i> Recalculate</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal fade" id="addLeavePolicyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="modalTitle">Edit Leave Policy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('leave-policies.store') }}" method="POST" id="leavePolicyForm">
                @csrf
                <div id="methodContainer"></div>
                
                <div class="modal-body bg-light p-4">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label text-muted fs-12 mb-1">Leave Type <span class="text-danger">*</span></label>
                            <select class="form-select" name="leave_type_id" id="leave_type_id" required>
                                <option value="">Select</option>
                                @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label text-muted fs-12 mb-1">Policy Description</label>
                            <input type="text" class="form-control" name="policy_description" id="policy_description" placeholder="Leave Policy for ...">
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Grant Leaves -->
                        <div class="col-md-5">
                            <div class="policy-box grant-box">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="grant_leaves" name="grant_leaves" value="1" checked>
                                    <label class="form-check-label fw-bold" for="grant_leaves">Grant Leaves</label>
                                </div>
                                
                                <div class="d-flex align-items-center mb-3">
                                    <span class="fs-12 text-muted me-2">If Presents are more than or eq:</span>
                                    <input type="number" class="form-control w-25" name="minimum_presence_days" id="minimum_presence_days" value="0" min="0">
                                </div>
                                <div class="fs-12 text-muted mb-2">then grant leaves as follows:</div>
                                
                                <div class="grid-container">
                                    @php $months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec']; @endphp
                                    @foreach($months as $m)
                                    <div class="grid-item">
                                        <label>{{ ucfirst($m) }} <i class="ri-arrow-right-line text-info"></i></label>
                                        <input type="number" step="0.5" class="form-control" name="{{ $m }}_grant" id="{{ $m }}_grant" value="0">
                                    </div>
                                    @endforeach
                                </div>

                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="reset_negative_balance" name="reset_negative_balance" value="1">
                                    <label class="form-check-label fs-12 fw-medium" for="reset_negative_balance">Reset negative balance before grant <i class="ri-information-line text-primary"></i></label>
                                </div>
                            </div>
                        </div>

                        <!-- Lapse Leaves -->
                        <div class="col-md-4">
                            <div class="policy-box lapse-box">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="lapse_leaves" name="lapse_leaves" value="1">
                                    <label class="form-check-label fw-bold" for="lapse_leaves">Lapse Leaves</label>
                                </div>
                                
                                <div class="fs-12 text-muted mb-3">Lapse balance of leaves <strong>above:</strong></div>
                                
                                <div class="grid-container">
                                    @foreach($months as $m)
                                    <div class="grid-item">
                                        <label>{{ ucfirst($m) }} <i class="ri-arrow-right-line text-warning"></i></label>
                                        <input type="number" step="0.5" class="form-control" name="{{ $m }}_lapse" id="{{ $m }}_lapse" value="0">
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Other Options -->
                        <div class="col-md-3">
                            <div class="options-box h-100">
                                <h6 class="fs-13 fw-bold mb-3">Other Options</h6>
                                
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="during_probation" name="during_probation" value="1" checked>
                                    <label class="form-check-label fs-12" for="during_probation">Do not apply <strong>during</strong> probation <i class="ri-information-line text-primary"></i></label>
                                </div>
                                
                                <div class="form-check mb-4">
                                    <input class="form-check-input" type="checkbox" id="after_probation" name="after_probation" value="1">
                                    <label class="form-check-label fs-12" for="after_probation">Do not apply <strong>after</strong> probation <i class="ri-information-line text-primary"></i></label>
                                </div>

                                <div class="form-check form-switch bg-light p-2 rounded d-flex align-items-center">
                                    <input class="form-check-input ms-0 me-2 mt-0" type="checkbox" id="auto_apply" name="auto_apply" value="1">
                                    <label class="form-check-label fs-13 fw-bold" for="auto_apply">Auto Apply <i class="ri-information-line text-primary fw-normal"></i></label>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                
                <div class="modal-footer border-top bg-white py-2">
                    <button type="button" class="btn btn-link text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4">Save</button>
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
    if($('#leave-policies-table').length) {
        $('#leave-policies-table').DataTable({
            responsive: true,
            pageLength: 10,
            order: [[1, 'asc']]
        });
    }

    const months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];

    // Edit Modal AJAX
    $('.edit-btn').on('click', function () {
        let id = $(this).data('id');
        let url = `{{ url('business/setup/attendance/leave-policies') }}/${id}/edit`;
        
        $.get(url, function (data) {
            $('#modalTitle').text('Edit Leave Policy');
            let formUrl = `{{ url('business/setup/attendance/leave-policies') }}/${id}`;
            $('#leavePolicyForm').attr('action', formUrl);
            $('#methodContainer').html('<input type="hidden" name="_method" value="PUT">');

            $('#leave_type_id').val(data.leave_type_id);
            $('#policy_description').val(data.policy_description);
            $('#minimum_presence_days').val(data.minimum_presence_days);

            $('#grant_leaves').prop('checked', data.grant_leaves);
            $('#lapse_leaves').prop('checked', data.lapse_leaves);
            $('#reset_negative_balance').prop('checked', data.reset_negative_balance);
            $('#during_probation').prop('checked', data.during_probation);
            $('#after_probation').prop('checked', data.after_probation);
            $('#auto_apply').prop('checked', data.auto_apply);

            months.forEach(m => {
                $('#' + m + '_grant').val(data[m + '_grant'] || 0);
                $('#' + m + '_lapse').val(data[m + '_lapse'] || 0);
            });

            $('#addLeavePolicyModal').modal('show');
        });
    });

    // Reset Modal on Close
    $('#addLeavePolicyModal').on('hidden.bs.modal', function () {
        $('#modalTitle').text('Add Leave Policy');
        $('#leavePolicyForm').attr('action', '{{ route('leave-policies.store') }}');
        $('#methodContainer').empty();
        $('#leavePolicyForm')[0].reset();
        $('#grant_leaves, #during_probation').prop('checked', true);
        
        months.forEach(m => {
            $('#' + m + '_grant').val(0);
            $('#' + m + '_lapse').val(0);
        });
    });
});
</script>
@endsection
