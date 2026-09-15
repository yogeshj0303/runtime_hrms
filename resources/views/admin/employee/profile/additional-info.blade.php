@extends('admin.employee.profile.layout')

@section('profile_title', 'Additional Info')
@section('profile_description', 'Manage additional employee details, notes, and custom fields.')

@section('profile_actions')
<button type="submit" form="additionalInfoForm" class="btn-hrms-crimson">
    <i class="ri-save-line"></i> Save Info
</button>
@endsection

@section('profile_content')
@php
    $customFields = $additionalInfo && is_array($additionalInfo->custom_fields) 
        ? $additionalInfo->custom_fields 
        : ($additionalInfo && is_string($additionalInfo->custom_fields) ? json_decode($additionalInfo->custom_fields, true) : []);
@endphp

<form id="additionalInfoForm" action="{{ route('employee.profile.additional-info.update', ['id' => $employee->id]) }}" method="POST">
    @csrf

    <div class="row g-4">
        <!-- Left: General Notes & Extra Info -->
        <div class="col-lg-6 col-md-12">
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Internal HR Notes</label>
                <textarea class="form-control" name="notes" rows="6" placeholder="Add confidential notes or internal observations about this employee...">{{ $additionalInfo->notes ?? '' }}</textarea>
                <div class="form-text">These notes are visible only to HR Managers and Administrators.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Extra Information / Background</label>
                <textarea class="form-control" name="extra_information" rows="5" placeholder="Special skills, hobbies, medical considerations, or other miscellaneous data...">{{ $additionalInfo->extra_information ?? '' }}</textarea>
            </div>
        </div>

        <!-- Right: Custom Key-Value Fields -->
        <div class="col-lg-6 col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="form-label fw-bold text-dark mb-0">Custom Attributes</label>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addCustomField()">
                    <i class="ri-add-line"></i> Add Field
                </button>
            </div>

            <div id="customFieldsContainer">
                @if(!empty($customFields))
                    @foreach($customFields as $key => $val)
                        <div class="row g-2 mb-2 custom-field-row align-items-center">
                            <div class="col-5">
                                <input type="text" class="form-control form-control-sm" name="custom_keys[]" value="{{ $key }}" placeholder="Field Name (e.g. Uniform Size)">
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" name="custom_values[]" value="{{ $val }}" placeholder="Value (e.g. XL)">
                            </div>
                            <div class="col-1 text-end">
                                <button type="button" class="btn btn-sm text-danger p-0" onclick="this.closest('.custom-field-row').remove()">
                                    <i class="ri-delete-bin-line fs-16"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-muted small py-3" id="noCustomFieldsMsg">No custom attributes added. Click "Add Field" to add custom parameters.</div>
                @endif
            </div>
        </div>

        <div class="col-12 pt-3 border-top">
            <button type="submit" class="btn-hrms-crimson">
                <i class="ri-save-line"></i> Save Additional Info
            </button>
        </div>
    </div>
</form>

@endsection

@section('page_script')
<script>
    function addCustomField() {
        const noMsg = document.getElementById('noCustomFieldsMsg');
        if (noMsg) noMsg.style.display = 'none';

        const container = document.getElementById('customFieldsContainer');
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2 custom-field-row align-items-center';
        div.innerHTML = `
            <div class="col-5">
                <input type="text" class="form-control form-control-sm" name="custom_keys[]" placeholder="Field Name (e.g. T-Shirt Size)">
            </div>
            <div class="col-6">
                <input type="text" class="form-control form-control-sm" name="custom_values[]" placeholder="Value (e.g. L)">
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn btn-sm text-danger p-0" onclick="this.closest('.custom-field-row').remove()">
                    <i class="ri-delete-bin-line fs-16"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
    }
</script>
@endsection