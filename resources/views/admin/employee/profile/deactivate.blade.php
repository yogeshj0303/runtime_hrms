@extends('admin.employee.profile.layout')

@section('profile_title', 'Deactivate/Delete')
@section('profile_description', 'Review dependencies and proceed with employee separation or deactivation.')

@section('profile_content')
<div class="row">
    <div class="col-lg-8">
        <p class="text-muted fs-13 mb-3">You are about to initiate deactivation for <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong> ({{ $employee->employee_code }}). Before proceeding, please review the dependencies below:</p>
        
        <div class="list-group mb-4 border rounded">
            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                <div>
                    <h6 class="mb-1 fs-13 fw-bold text-dark">Allocated Assets</h6>
                    <small class="text-muted">Assets currently held by the employee that must be returned.</small>
                </div>
                <span class="badge {{ $assetsHold > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill fs-12 px-2 py-1">{{ $assetsHold }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                <div>
                    <h6 class="mb-1 fs-13 fw-bold text-dark">Reporting Employees</h6>
                    <small class="text-muted">Employees reporting directly to this person requiring reassignment.</small>
                </div>
                <span class="badge {{ $reportingEmployees > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill fs-12 px-2 py-1">{{ $reportingEmployees }}</span>
            </div>
            <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                <div>
                    <h6 class="mb-1 fs-13 fw-bold text-dark">Pending Approvals</h6>
                    <small class="text-muted">Pending leave/attendance requests waiting for their approval.</small>
                </div>
                <span class="badge {{ $pendingApprovals > 0 ? 'bg-danger' : 'bg-success' }} rounded-pill fs-12 px-2 py-1">{{ $pendingApprovals }}</span>
            </div>
        </div>

        @if($assetsHold > 0 || $reportingEmployees > 0 || $pendingApprovals > 0)
        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
            <i class="ri-alert-line fs-18 flex-shrink-0"></i>
            <div class="fs-13"><strong>Warning:</strong> There are unresolved dependencies. It is strongly recommended to re-assign direct reports and recover company assets before proceeding.</div>
        </div>
        @endif

        <form action="{{ route('employee.profile.deactivate.submit', $employee->id) }}" method="POST" class="p-3 bg-light rounded border">
            @csrf
            <h6 class="fw-bold fs-14 text-danger mb-3"><i class="ri-error-warning-line me-1"></i> Separation Details</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fs-13 text-muted">Exit Date <span class="text-danger">*</span></label>
                    <input type="date" name="exit_date" class="form-control fs-13" required value="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fs-13 text-muted">Exit Reason</label>
                    <select name="exit_reason_id" class="form-select fs-13">
                        <option value="">Select Reason</option>
                        <option value="1">Resignation</option>
                        <option value="2">Termination</option>
                        <option value="3">Absconding</option>
                        <option value="4">Retirement</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-danger btn-sm px-4 fw-semibold" onclick="return confirm('Are you sure you want to deactivate this employee?');">
                <i class="ri-user-unfollow-line me-1"></i> Confirm Deactivation
            </button>
        </form>
    </div>
</div>
@endsection
