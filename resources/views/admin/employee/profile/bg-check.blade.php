@extends('admin.employee.profile.layout')

@section('profile_title', 'BG Check')
@section('profile_description', 'Manage employee background verification and status records.')

@section('profile_actions')
<button class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addBgCheckModal">
    <i class="ri-add-line"></i> Add New Check
</button>
@endsection

@section('profile_content')
<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light text-muted small">
            <tr>
                <th>Check Type</th>
                <th>Agency Name</th>
                <th>Status</th>
                <th>Remarks</th>
                <th>Date</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bgChecks as $check)
            <tr>
                <td class="fw-semibold text-dark fs-13">{{ $check->check_type }}</td>
                <td class="text-muted fs-13">{{ $check->agency_name ?? '-' }}</td>
                <td>
                    @if($check->status == 'Cleared')
                    <span class="badge bg-success-subtle text-success border border-success">Cleared</span>
                    @elseif($check->status == 'Failed')
                    <span class="badge bg-danger-subtle text-danger border border-danger">Failed</span>
                    @else
                    <span class="badge bg-warning-subtle text-warning border border-warning">Pending</span>
                    @endif
                </td>
                <td class="text-muted small">{{ $check->remarks ?? '-' }}</td>
                <td class="text-muted fs-13">{{ $check->created_at ? $check->created_at->format('d M Y') : '-' }}</td>
                <td class="text-end">
                    <a href="javascript:void(0)" class="text-danger" onclick="confirmDelete(event, 'delete-bg-check-{{ $check->id }}', 'Are you sure you want to delete this background check?');" title="Delete Check"><i class="ri-delete-bin-line"></i></a>
                    <form id="delete-bg-check-{{ $check->id }}" action="{{ route('employee.profile.bg-check.destroy', $check->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center text-muted p-5">
                    <i class="ri-shield-check-line" style="font-size: 54px; color: #cbd5e1;"></i>
                    <h6 class="mt-3 fw-bold text-secondary">No background checks found</h6>
                    <p class="mb-0 text-muted small">Add a background check to verify employee credentials.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="addBgCheckModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="{{ route('employee.profile.bg-check.store') }}" method="POST">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                <input type="hidden" name="id" value="{{ $employee->id }}">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold fs-15">Add Background Check</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fs-13 text-muted">Check Type <span class="text-danger">*</span></label>
                        <select name="check_type" class="form-select fs-13" required>
                            <option value="">Select Check Type</option>
                            <option value="Criminal">Criminal Record</option>
                            <option value="Education">Education Verification</option>
                            <option value="Employment">Employment Verification</option>
                            <option value="Address">Address Verification</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-13 text-muted">Agency Name</label>
                        <input type="text" name="agency_name" class="form-control fs-13" placeholder="e.g. AuthBridge / First Advantage">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-13 text-muted">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select fs-13" required>
                            <option value="Pending">Pending</option>
                            <option value="Cleared">Cleared</option>
                            <option value="Failed">Failed</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-13 text-muted">Remarks</label>
                        <textarea name="remarks" class="form-control fs-13" rows="3" placeholder="Verification notes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-medium" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save Check</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

