@extends('layouts.master')

@section('title')
    Initiate Exit
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .summary-card {
        background-color: #f8f9fa;
        border-radius: 12px;
    }
</style>
@endsection

@section('content')

<!-- Flight Risk Alert -->
@if($flightRisks->count() > 0)
<div class="alert alert-warning alert-dismissible fade show shadow-sm" role="alert">
    <strong><i class="ri-alarm-warning-line me-1"></i> High Flight Risk Alert!</strong> 
    The following employees have been flagged as high risk:
    <ul class="mb-0 mt-2">
        @foreach($flightRisks as $fr)
            <li>{{ $fr->first_name }} {{ $fr->last_name }} ({{ $fr->employee_code }})</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="helpdesk-header mb-4">
    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <a href="{{ route('separation.dashboard') }}">Separation & Offboarding</a>
        <i class="ri-arrow-right-s-line"></i>
        <span>Initiate Exit</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Initiate Exit</h4>
            <p class="text-muted mb-0">Log a resignation or termination for an employee.</p>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column: Form -->
    <div class="col-lg-7 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <form action="{{ route('separation.store') }}" method="POST" id="initiateExitForm">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label fw-medium">Select Employee <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="employee_id" id="employeeSelect" class="form-select select2" required>
                                <option value="">-- Choose Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})</option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary" type="button" id="btnLoadDetails">Load Details</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Resignation Date <span class="text-danger">*</span></label>
                            <input type="date" name="resignation_date" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Working Date <span class="text-danger">*</span></label>
                            <input type="date" name="exit_date" id="exitDate" class="form-control" required>
                            <small class="text-muted mt-1 d-block" id="noticePeriodHelp"></small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Exit Reason <span class="text-danger">*</span></label>
                        <select name="exit_reason" class="form-select" required>
                            <option value="">-- Select Reason --</option>
                            <option value="Resignation">Resignation</option>
                            <option value="Termination">Termination</option>
                            <option value="Absconding">Absconding</option>
                            <option value="Retirement">Retirement</option>
                            <option value="Better Opportunity">Better Opportunity</option>
                            <option value="Health Issues">Health Issues</option>
                            <option value="Relocation">Relocation</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Any additional notes..."></textarea>
                    </div>

                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="retention_attempted" value="1" id="retentionAttempted">
                            <label class="form-check-label" for="retentionAttempted">
                                We are trying to retain this employee (Retention Attempted)
                            </label>
                        </div>
                    </div>

                    <hr>

                    <div class="text-end">
                        <a href="{{ route('separation.dashboard') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary" id="btnSubmitForm" disabled>Submit Exit Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column: Summary -->
    <div class="col-lg-5 mb-4">
        <div class="card border-0 shadow-sm summary-card h-100">
            <div class="card-body">
                <h5 class="mb-4 border-bottom pb-2">Employee Summary</h5>
                
                <div id="summaryPlaceholder" class="text-center py-5 text-muted">
                    <i class="ri-user-search-line fs-1 d-block mb-2"></i>
                    Select an employee and click "Load Details" to view their summary.
                </div>

                <div id="summaryContent" class="d-none">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 24px;" id="empInitials">
                            -
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1" id="empName">-</h5>
                            <p class="text-muted mb-0" id="empCode">-</p>
                        </div>
                    </div>

                    <ul class="list-group list-group-flush bg-transparent">
                        <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                            <span class="text-muted">Designation</span>
                            <span class="fw-medium text-end" id="empDesignation">-</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                            <span class="text-muted">Department</span>
                            <span class="fw-medium text-end" id="empDepartment">-</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                            <span class="text-muted">Location</span>
                            <span class="fw-medium text-end" id="empLocation">-</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                            <span class="text-muted">Date of Joining</span>
                            <span class="fw-medium text-end" id="empDoj">-</span>
                        </li>
                        <li class="list-group-item bg-transparent px-0 d-flex justify-content-between">
                            <span class="text-muted">Notice Period</span>
                            <span class="fw-medium text-end"><span id="empNotice">-</span> Days</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        $('#btnLoadDetails').click(function() {
            const employeeId = $('#employeeSelect').val();
            
            if(!employeeId) {
                alert('Please select an employee first.');
                return;
            }

            const btn = $(this);
            const originalText = btn.text();
            btn.html('<i class="ri-loader-4-line ri-spin"></i> Loading...').prop('disabled', true);

            $.ajax({
                url: "{{ route('separation.employee-details') }}",
                type: "GET",
                data: { employee_id: employeeId },
                success: function(response) {
                    if(response.success) {
                        const data = response.data;
                        
                        // Set Initials
                        const initials = data.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                        $('#empInitials').text(initials);
                        
                        // Populate Fields
                        $('#empName').text(data.name);
                        $('#empCode').text(data.employee_code);
                        $('#empDesignation').text(data.designation);
                        $('#empDepartment').text(data.department);
                        $('#empLocation').text(data.location);
                        $('#empDoj').text(data.joining_date);
                        $('#empNotice').text(data.notice_period_days);
                        
                        $('#noticePeriodHelp').text('Required Notice Period: ' + data.notice_period_days + ' Days');

                        // Show Content
                        $('#summaryPlaceholder').addClass('d-none');
                        $('#summaryContent').removeClass('d-none');
                        
                        // Enable Submit
                        $('#btnSubmitForm').prop('disabled', false);
                    }
                },
                error: function() {
                    alert('Failed to load employee details.');
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });
    });
</script>
@endsection
