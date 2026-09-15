@extends('admin.employee.profile.layout')

@section('profile_title', 'Policies')
@section('profile_description', 'Specify policies applicable to the employee.')

@section('profile_actions')
<button type="submit" form="policiesForm" class="btn-hrms-crimson">
    <i class="ri-save-line"></i> Save Policies
</button>
@endsection

@section('profile_content')
@php
    $assignment = $employee->policy;
    $assignedLeaves = $assignment ? $assignment->leavePolicies->pluck('id')->toArray() : [];
    $assignedTimeRules = $assignment ? $assignment->timeRules->pluck('id')->toArray() : [];
@endphp

<form id="policiesForm" action="{{ route('employee.profile.policies.update', ['id' => $employee->id]) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="row mb-4 align-items-center">
        <div class="col-md-3">
            <label class="form-label mb-0 fw-semibold fs-13">Effective From <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-4">
            <input type="date" class="form-control form-control-sm" name="effective_from" value="{{ $assignment?->effective_from ? \Carbon\Carbon::parse($assignment->effective_from)->format('Y-m-d') : date('Y-m-d') }}" required>
        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <div class="col-md-3">
            <label class="form-label mb-0 fw-semibold fs-13">Shift Policy <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-6">
            <select name="shift_policy_id" class="form-select form-select-sm" required>
                <option value="">Select Shift Policy</option>
                @foreach($shiftPolicies as $policy)
                    <option value="{{ $policy->id }}" {{ ($assignment?->shift_policy_id == $policy->id) ? 'selected' : '' }}>
                        {{ $policy->name ?? 'Shift Policy ' . $policy->id }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-check form-switch d-flex align-items-center gap-2 ps-0">
                <input class="form-check-input ms-0 mt-0" type="checkbox" name="auto_shift_selection" id="auto_shift_selection" value="1" {{ $assignment?->auto_shift_selection ? 'checked' : '' }}>
                <label class="form-check-label ms-2 fs-13" for="auto_shift_selection">Auto Shift Selection <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <div class="col-md-3">
            <label class="form-label mb-0 fw-semibold fs-13">Week Off Policy <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-6">
            <select name="week_off_policy_id" class="form-select form-select-sm" required>
                <option value="">Select Week Off Policy</option>
                @foreach($weekOffPolicies as $policy)
                    <option value="{{ $policy->id }}" {{ ($assignment?->week_off_policy_id == $policy->id) ? 'selected' : '' }}>
                        {{ $policy->name ?? 'Week Off Policy ' . $policy->id }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-4 align-items-center">
        <div class="col-md-3">
            <label class="form-label mb-0 fw-semibold fs-13">Overtime Policy</label>
        </div>
        <div class="col-md-6">
            <select name="overtime_policy_id" class="form-select form-select-sm">
                <option value="">Not Applicable</option>
                @foreach($overtimePolicies as $policy)
                    <option value="{{ $policy->id }}" {{ ($assignment?->overtime_policy_id == $policy->id) ? 'selected' : '' }}>
                        {{ $policy->policy_name ?? 'Overtime Policy ' . $policy->id }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <label class="form-label fw-semibold fs-13">Leave Policies</label>
        </div>
        <div class="col-md-6">
            @if(count($leavePolicies) > 0)
                <div class="d-flex flex-column gap-2 mt-1">
                    @foreach($leavePolicies as $policy)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="leave_policy_ids[]" value="{{ $policy->id }}" id="leave_{{ $policy->id }}" 
                                {{ in_array($policy->id, $assignedLeaves) ? 'checked' : '' }}>
                            <label class="form-check-label fs-13" for="leave_{{ $policy->id }}">
                                {{ $policy->leaveType?->name ?? $policy->policy_description ?? 'Leave Policy ' . $policy->id }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted fs-13 mt-1">No leave policies defined for this business.</div>
            @endif
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <label class="form-label fw-semibold fs-13">Time Rules</label>
        </div>
        <div class="col-md-6">
            @if(count($timeRules) > 0)
                <div class="d-flex flex-column gap-2 mt-1">
                    @foreach($timeRules as $rule)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="time_rule_ids[]" value="{{ $rule->id }}" id="rule_{{ $rule->id }}" 
                                {{ in_array($rule->id, $assignedTimeRules) ? 'checked' : '' }}>
                            <label class="form-check-label fs-13" for="rule_{{ $rule->id }}">
                                {{ $rule->name ?? 'Time Rule ' . $rule->id }}
                            </label>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-muted fs-13 mt-1">No time rules defined for this business.</div>
            @endif
        </div>
    </div>

    <div class="row mt-4 pt-4 border-top">
        <div class="col-12 mb-3">
            <h6 class="fw-bold fs-14 text-dark">Attendance Tracking & ESS Settings</h6>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-check form-switch d-flex align-items-center gap-2 ps-0">
                <input class="form-check-input ms-0 mt-0" type="checkbox" name="web_chat_punch" id="web_chat_punch" value="1" {{ $employee->permission?->web_chat_punch ? 'checked' : '' }}>
                <label class="form-check-label ms-2 fs-13" for="web_chat_punch">Allow Web Attendance (ESS)</label>
            </div>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-check form-switch d-flex align-items-center gap-2 ps-0">
                <input class="form-check-input ms-0 mt-0" type="checkbox" name="selfie_at_all_locations" id="selfie_at_all_locations" value="1" {{ $employee->permission?->selfie_at_all_locations ? 'checked' : '' }}>
                <label class="form-check-label ms-2 fs-13" for="selfie_at_all_locations">Require Selfie for Attendance</label>
            </div>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-check form-switch d-flex align-items-center gap-2 ps-0">
                <input class="form-check-input ms-0 mt-0" type="checkbox" name="live_travel_attendance" id="live_travel_attendance" value="1" {{ $employee->permission?->live_travel_attendance ? 'checked' : '' }}>
                <label class="form-check-label ms-2 fs-13" for="live_travel_attendance">Allow Live Travel (GPS) Attendance</label>
            </div>
        </div>
    </div>

    <div class="row mb-3 align-items-center">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="form-check form-switch d-flex align-items-center gap-2 ps-0">
                <input class="form-check-input ms-0 mt-0" type="checkbox" name="auto_punch_in_out" id="auto_punch_in_out" value="1" {{ $employee->permission?->auto_punch_in_out ? 'checked' : '' }}>
                <label class="form-check-label ms-2 fs-13" for="auto_punch_in_out">Auto Punch In/Out (Geofencing)</label>
            </div>
        </div>
    </div>

    <div class="row mt-4 border-top pt-3">
        <div class="col-md-9 text-end">
            <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save Policies</button>
        </div>
    </div>
</form>

<!-- Policy History Section -->
@php
    $policiesList = $employee->policies->sortByDesc('effective_from')->values();
    $hasHistory = $policiesList->where('is_current', false)->count() > 0;
@endphp

@if($hasHistory)
<div class="mt-4 pt-3 border-top">
    <h6 class="fw-bold fs-14 mb-3 text-dark">Policy History</h6>
    <div class="list-group list-group-flush border rounded">
        @foreach($policiesList as $index => $historyPolicy)
            @if(!$historyPolicy->is_current)
                @php
                    $nextPolicy = $policiesList[$index - 1] ?? null;
                    $toDate = $nextPolicy ? \Carbon\Carbon::parse($nextPolicy->effective_from)->subDay()->format('d M Y') : 'N/A';
                    $fromDate = \Carbon\Carbon::parse($historyPolicy->effective_from)->format('d M Y');
                    $shiftName = \App\Models\ShiftPolicy::find($historyPolicy->shift_policy_id)?->name ?? 'N/A';
                @endphp
                <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                    <div>
                        <div class="fw-bold fs-13">Shift: {{ $shiftName }}</div>
                        <div class="text-muted small">
                            {{ $fromDate }} to {{ $toDate }}
                        </div>
                    </div>
                    <div>
                        <form action="{{ route('employee.profile.policies.destroy', ['id' => $employee->id, 'policy_id' => $historyPolicy->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this historical revision?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-circle-delete" title="Delete revision"><i class="ri-delete-bin-line"></i></button>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</div>
@endif

@endsection