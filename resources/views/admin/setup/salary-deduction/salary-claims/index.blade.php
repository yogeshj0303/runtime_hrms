@extends('layouts.master')

@section('title')
Claims & Reimbursements
@endsection

@section('css')

<link rel="stylesheet"
      href="{{ asset('/assets/admin/css/business.css') }}">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css"
      rel="stylesheet">

{{-- jQuery must be loaded in head so it is available when @section('script') runs --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
    .claim-settings-body {
        transition: opacity 0.3s ease;
    }
    .claim-settings-body.disabled-section {
        opacity: 0.45;
        pointer-events: none;
    }
    .grade-list-wrap.disabled-grades {
        opacity: 0.45;
        pointer-events: none;
    }
    .limit-suffix {
        color: #6c757d;
        font-size: 13px;
        display: flex;
        align-items: center;
        padding-left: 8px;
        white-space: nowrap;
    }
    .approver-info {
        font-size: 12px;
        color: #6c757d;
        margin-top: 5px;
    }
    .approver-info i {
        color: #0d6efd;
    }
</style>

@endsection


@section('content')

<div class="helpdesk-header">

    <div class="breadcrumb-section">

        <span>Setup</span>

        <i class="ri-arrow-right-s-line"></i>

        <span>Salary &amp; Deductions</span>

        <i class="ri-arrow-right-s-line"></i>

        <span>Claims &amp; Reimbursements</span>

    </div>

    <div class="header-content">

        <div class="header-left">

            <h4>Claims &amp; Reimbursements</h4>

            <p class="text-muted mb-0" style="font-size:13px;">
                Setup Claims &amp; Reimbursement rules, limits and permissions.
            </p>

        </div>

        <div class="header-buttons">

            <button
                type="button"
                class="btn-add"
                data-bs-toggle="modal"
                data-bs-target="#addComponentModal">

                <i class="ri-add-line"></i>

                Add New Component

            </button>

        </div>

    </div>

</div>


{{-- Alert Messages --}}
<div id="ajax-alert" class="d-none mt-2"></div>


{{-- ===================================================
     Component Selector Card
     =================================================== --}}
<div class="card">

    <div class="card-body">

        <div class="row align-items-end">

            <div class="col-md-4">

                <label class="form-label fw-semibold mb-1">
                    Select a Claim Component
                </label>

                <select
                    id="componentSelect"
                    class="form-select">

                    <option value="">- Select -</option>

                    @foreach($components as $component)

                    <option
                        value="{{ $component->id }}"
                        {{ ($selectedComponent && $selectedComponent->id === $component->id) ? 'selected' : '' }}>

                        {{ $component->name }}

                    </option>

                    @endforeach

                </select>

            </div>

        </div>

    </div>

</div>


{{-- ===================================================
     Settings Row (Claim Request + Allowed Grades)
     =================================================== --}}
<div class="row mt-3" id="settingsRow" style="{{ $selectedComponent ? '' : 'display:none;' }}">

    {{-- Claim Request Settings Card --}}
    <div class="col-md-6">

        <div class="card h-100">

            <div class="card-body">

                <h6 class="fw-semibold mb-3">Claim Request Settings</h6>

                {{-- Enable Toggle --}}
                <div class="form-check form-switch mb-3">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="enableClaimToggle"
                        {{ ($claim && $claim->enable_claim_request) ? 'checked' : '' }}>

                    <label class="form-check-label fw-semibold" for="enableClaimToggle">
                        Enable Claim Requests
                    </label>

                </div>

                {{-- Settings Body --}}
                <div id="claimSettingsBody" class="claim-settings-body {{ ($claim && $claim->enable_claim_request) ? '' : 'disabled-section' }}">

                    {{-- Claim Approver --}}
                    <div class="mb-3">

                        <label class="form-label">Claim Approver</label>

                        <select
                            id="claimApprover"
                            class="form-select">

                            <option value="">— None —</option>

                            @foreach($employees as $emp)

                            <option
                                value="{{ $emp->id }}"
                                {{ ($claim && $claim->claim_approver == $emp->id) ? 'selected' : '' }}>

                                {{ $emp->first_name }} {{ $emp->last_name }}
                                ({{ $emp->employee_code }})

                            </option>

                            @endforeach

                        </select>

                        <div class="approver-info">
                            <i class="ri-information-line"></i>
                            If an approver is not selected, the request can still be submitted but can be approved only from this HR Portal.
                        </div>

                    </div>

                    {{-- Request Limit --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Request Limit
                            <i class="ri-question-line text-primary ms-1"
                               title="Maximum number of claim requests an employee can submit."
                               style="cursor:pointer;"></i>
                        </label>

                        <div class="d-flex align-items-center gap-2">

                            <input
                                type="number"
                                id="requestLimit"
                                class="form-control"
                                style="max-width:120px;"
                                min="0"
                                value="{{ $claim ? $claim->request_limit : 0 }}">

                            <span class="limit-suffix">/ request</span>

                        </div>

                    </div>

                    {{-- Monthly Limit --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Monthly Limit
                            <i class="ri-question-line text-primary ms-1"
                               title="Maximum claim amount allowed per month."
                               style="cursor:pointer;"></i>
                        </label>

                        <div class="d-flex align-items-center gap-2">

                            <input
                                type="number"
                                id="monthlyLimit"
                                class="form-control"
                                style="max-width:120px;"
                                min="0"
                                value="{{ $claim ? $claim->monthly_limit : 0 }}">

                            <span class="limit-suffix">/ month</span>

                        </div>

                    </div>

                    {{-- Employee Limit --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Employee Limit
                            <i class="ri-question-line text-primary ms-1"
                               title="Maximum amount an employee can claim per month."
                               style="cursor:pointer;"></i>
                        </label>

                        <div class="d-flex align-items-center gap-2">

                            <input
                                type="number"
                                id="employeeLimit"
                                class="form-control"
                                style="max-width:120px;"
                                min="0"
                                value="{{ $claim ? $claim->employee_limit : 0 }}">

                            <span class="limit-suffix">/ employee / month</span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Allowed Grades Card --}}
    <div class="col-md-6">

        <div class="card h-100">

            <div class="card-body">

                <h6 class="fw-semibold mb-3">Allowed Grades</h6>

                {{-- All Grades Toggle --}}
                <div class="form-check form-switch mb-3">

                    <input
                        class="form-check-input"
                        type="checkbox"
                        id="allowAllGradesToggle"
                        {{ ($claim && $claim->allow_all_grades) ? 'checked' : '' }}>

                    <label class="form-check-label fw-semibold" for="allowAllGradesToggle">
                        All Grades are allowed
                    </label>

                </div>

                <p class="text-muted" style="font-size:12px;">
                    Select Grades allowed to submit claims for the selected component.
                </p>

                {{-- Grade Checkboxes --}}
                <div id="gradeListWrap" class="grade-list-wrap {{ ($claim && $claim->allow_all_grades) ? 'disabled-grades' : '' }}">

                    @forelse($grades as $grade)

                    <div class="form-check mb-2">

                        <input
                            class="form-check-input grade-checkbox"
                            type="checkbox"
                            id="grade_{{ $grade->id }}"
                            value="{{ $grade->id }}"
                            {{ ($claim && is_array($claim->allowed_grades) && in_array($grade->id, $claim->allowed_grades)) ? 'checked' : '' }}>

                        <label class="form-check-label" for="grade_{{ $grade->id }}">
                            {{ $grade->name }}
                        </label>

                    </div>

                    @empty

                    <p class="text-muted" style="font-size:12px;">
                        No grades found. Please add grades in Master Setup first.
                    </p>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>


{{-- Save Button --}}
<div class="mt-3" id="saveSection" style="{{ $selectedComponent ? '' : 'display:none;' }}">

    <button
        type="button"
        id="saveClaimBtn"
        class="btn btn-primary">

        <i class="ri-save-line"></i>

        Save

    </button>

</div>


{{-- ===================================================
     Add Claim Component Modal
     =================================================== --}}
<div class="modal fade"
     id="addComponentModal"
     tabindex="-1"
     aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">Add Claim Component</h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div id="componentFormErrors" class="d-none"></div>

                <div class="mb-3">

                    <label class="form-label">
                        Name <span class="text-danger">*</span>
                    </label>

                    <input
                        type="text"
                        id="componentName"
                        class="form-control"
                        placeholder="e.g. Travel Reimbursement">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Short Name (Alias)
                    </label>

                    <input
                        type="text"
                        id="componentShortName"
                        class="form-control"
                        placeholder="e.g. TRAVEL">

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Close

                </button>

                <button
                    type="button"
                    id="saveComponentBtn"
                    class="btn btn-primary">

                    <i class="ri-save-line"></i>

                    Save

                </button>

            </div>

        </div>

    </div>

</div>


@endsection


@section('script')

<script>
$(function () {

    /*
    |----------------------------------------------------------
    | CSRF Token for AJAX
    |----------------------------------------------------------
    */
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // Currently active claim ID (if any)
    var currentClaimId = {{ $claim ? $claim->id : 'null' }};
    var currentComponentId = {{ $selectedComponent ? $selectedComponent->id : 'null' }};

    /*
    |----------------------------------------------------------
    | Enable/Disable Claim Settings section on toggle
    |----------------------------------------------------------
    */
    $('#enableClaimToggle').on('change', function () {
        if ($(this).is(':checked')) {
            $('#claimSettingsBody').removeClass('disabled-section');
        } else {
            $('#claimSettingsBody').addClass('disabled-section');
        }
    });

    /*
    |----------------------------------------------------------
    | Allow All Grades toggle
    |----------------------------------------------------------
    */
    $('#allowAllGradesToggle').on('change', function () {
        if ($(this).is(':checked')) {
            $('#gradeListWrap').addClass('disabled-grades');
        } else {
            $('#gradeListWrap').removeClass('disabled-grades');
        }
    });

    /*
    |----------------------------------------------------------
    | Component Dropdown Change → load claim for that component
    |----------------------------------------------------------
    */
    $('#componentSelect').on('change', function () {

        var componentId = $(this).val();

        if (!componentId) {
            $('#settingsRow').hide();
            $('#saveSection').hide();
            currentComponentId = null;
            currentClaimId = null;
            return;
        }

        currentComponentId = componentId;

        $.ajax({
            url: '{{ route("salary.claims.edit", ":id") }}'.replace(':id', componentId),
            type: 'GET',
            success: function (response) {

                var claim = response.claim;

                currentClaimId = claim ? claim.id : null;

                // Enable/Disable toggle
                $('#enableClaimToggle').prop('checked', claim && claim.enable_claim_request == 1);

                if (claim && claim.enable_claim_request == 1) {
                    $('#claimSettingsBody').removeClass('disabled-section');
                } else {
                    $('#claimSettingsBody').addClass('disabled-section');
                }

                // Limits
                $('#claimApprover').val(claim ? (claim.claim_approver || '') : '');
                $('#requestLimit').val(claim ? claim.request_limit : 0);
                $('#monthlyLimit').val(claim ? claim.monthly_limit : 0);
                $('#employeeLimit').val(claim ? claim.employee_limit : 0);

                // Grades
                var allowAll = claim && claim.allow_all_grades == 1;
                $('#allowAllGradesToggle').prop('checked', allowAll);

                if (allowAll) {
                    $('#gradeListWrap').addClass('disabled-grades');
                } else {
                    $('#gradeListWrap').removeClass('disabled-grades');
                }

                // Tick grade checkboxes
                $('.grade-checkbox').prop('checked', false);

                if (claim && claim.allowed_grades) {
                    var grades = claim.allowed_grades;
                    if (typeof grades === 'string') {
                        try { grades = JSON.parse(grades); } catch(e) { grades = []; }
                    }
                    $.each(grades, function (i, gradeId) {
                        $('#grade_' + gradeId).prop('checked', true);
                    });
                }

                $('#settingsRow').show();
                $('#saveSection').show();

            },
            error: function () {
                showAlert('danger', 'Failed to load claim settings.');
            }
        });
    });

    /*
    |----------------------------------------------------------
    | Save Claim Settings (Store / Update)
    |----------------------------------------------------------
    */
    $('#saveClaimBtn').on('click', function () {

        if (!currentComponentId) {
            showAlert('warning', 'Please select a Claim Component first.');
            return;
        }

        // Collect selected grades
        var selectedGrades = [];
        $('.grade-checkbox:checked').each(function () {
            selectedGrades.push($(this).val());
        });

        var data = {
            claim_component_id:   currentComponentId,
            enable_claim_request: $('#enableClaimToggle').is(':checked') ? 1 : 0,
            claim_approver:       $('#claimApprover').val(),
            request_limit:        $('#requestLimit').val(),
            monthly_limit:        $('#monthlyLimit').val(),
            employee_limit:       $('#employeeLimit').val(),
            allow_all_grades:     $('#allowAllGradesToggle').is(':checked') ? 1 : 0,
            allowed_grades:       selectedGrades,
        };

        var url, method;

        if (currentClaimId) {
            url    = '{{ route("salary.claims.update", ":id") }}'.replace(':id', currentClaimId);
            method = 'PUT';
        } else {
            url    = '{{ route("salary.claims.store") }}';
            method = 'POST';
        }

        $.ajax({
            url:  url,
            type: method,
            data: data,
            success: function (response) {
                if (response.success) {
                    if (response.claim) {
                        currentClaimId = response.claim.id;
                    }
                    showAlert('success', response.message);
                } else {
                    showAlert('danger', 'Something went wrong. Please try again.');
                }
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var msg = '';
                    $.each(errors, function (field, messages) {
                        msg += messages[0] + '<br>';
                    });
                    showAlert('danger', msg);
                } else {
                    showAlert('danger', 'Server error. Please try again.');
                }
            }
        });

    });

    /*
    |----------------------------------------------------------
    | Save Claim Component (Modal AJAX)
    |----------------------------------------------------------
    */
    $('#saveComponentBtn').on('click', function () {

        var name      = $('#componentName').val().trim();
        var shortName = $('#componentShortName').val().trim();

        if (!name) {
            showModalError('Name is required.');
            return;
        }

        $('#saveComponentBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

        $.ajax({
            url:  '{{ route("salary.claims.component.store") }}',
            type: 'POST',
            data: {
                name:       name,
                short_name: shortName
            },
            success: function (response) {

                if (response.success) {

                    // Close modal
                    $('#addComponentModal').modal('hide');

                    // Reset modal fields
                    $('#componentName').val('');
                    $('#componentShortName').val('');
                    $('#componentFormErrors').addClass('d-none').html('');

                    // Reload dropdown and select new component
                    reloadComponents(response.component.id);

                    showAlert('success', response.message);

                } else {
                    showModalError('Something went wrong. Please try again.');
                }

            },
            error: function (xhr) {

                if (xhr.status === 422) {
                    var errors = xhr.responseJSON.errors;
                    var msg = '';
                    $.each(errors, function (field, messages) {
                        msg += messages[0] + '<br>';
                    });
                    showModalError(msg);
                } else {
                    showModalError('Server error. Please try again.');
                }

            },
            complete: function () {
                $('#saveComponentBtn').prop('disabled', false).html('<i class="ri-save-line"></i> Save');
            }
        });

    });

    /*
    |----------------------------------------------------------
    | Reload Component Dropdown (after adding new component)
    |----------------------------------------------------------
    */
    function reloadComponents(selectId) {

        $.ajax({
            url:  '{{ route("salary.claims.components.list") }}',
            type: 'GET',
            success: function (response) {

                if (response.success) {

                    var select = $('#componentSelect');
                    select.html('<option value="">- Select -</option>');

                    $.each(response.components, function (i, component) {
                        var option = $('<option>')
                            .val(component.id)
                            .text(component.name);

                        if (component.id == selectId) {
                            option.prop('selected', true);
                        }

                        select.append(option);
                    });

                    // Trigger change to load the settings for newly selected component
                    if (selectId) {
                        select.val(selectId).trigger('change');
                    }
                }
            }
        });

    }

    /*
    |----------------------------------------------------------
    | Helpers
    |----------------------------------------------------------
    */
    function showAlert(type, message) {
        var alertBox = $('#ajax-alert');
        alertBox
            .removeClass('d-none alert-success alert-danger alert-warning')
            .addClass('alert alert-' + type)
            .html(message);

        setTimeout(function () {
            alertBox.addClass('d-none').html('');
        }, 4000);
    }

    function showModalError(message) {
        $('#componentFormErrors')
            .removeClass('d-none')
            .addClass('alert alert-danger')
            .html(message);
    }

    // Reset modal errors on open
    $('#addComponentModal').on('show.bs.modal', function () {
        $('#componentFormErrors').addClass('d-none').html('');
        $('#componentName').val('');
        $('#componentShortName').val('');
    });

});
</script>

@endsection
