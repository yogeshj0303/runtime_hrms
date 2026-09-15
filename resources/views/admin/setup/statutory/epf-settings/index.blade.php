@extends('layouts.master')

@section('title')
EPF Settings
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
.form-switch .form-check-input {
    width: 2.5em;
    height: 1.25em;
}
.component-checkbox {
    width: 1.2em;
    height: 1.2em;
    cursor: pointer;
}
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Statutory Options</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>EPF Settings</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>EPF Settings</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Enable or disable EPF deduction and configure deduction options</p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-success btn-sm">
                <i class="ri-book-read-line"></i> Read Help
            </button>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success mt-3">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mt-3">
    {{ session('error') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger mt-3">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="row mt-3">
    <!-- Left Column: Components -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-3">Salary Component Applicability</h6>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>COMPONENT</th>
                                <th>TYPE</th>
                                <th class="text-center">SELECT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($components as $component)
                            <tr>
                                <td>{{ $component->name }} @if($component->short_name) - {{ $component->short_name }} @endif</td>
                                <td>{{ $component->unit_type ?? 'Variable' }}</td>
                                <td class="text-center">
                                    <input type="checkbox" class="component-checkbox form-check-input" 
                                           data-id="{{ $component->id }}" 
                                           {{ in_array($component->id, $selectedComponentIds) ? 'checked' : '' }}>
                                </td>
                            </tr>
                            @endforeach
                            @if($components->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center">No Salary Components found.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: EPF Rates -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0">PF Rate Changes</h6>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addEpfModal">
                    Add New
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="epf-table">
                        <thead class="bg-light">
                            <tr>
                                <th>STATUS</th>
                                <th>DATE</th>
                                <th>RATES</th>
                                <th>ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($settings as $setting)
                            <tr>
                                <td>
                                    @if($setting->is_enabled)
                                        <span class="text-success"><i class="ri-check-line"></i> Enabled</span>
                                    @else
                                        <span class="text-danger"><i class="ri-close-circle-line"></i> Disabled</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($setting->effective_from)->format('d-M-Y') }}</td>
                                <td>
                                    {{ (float)$setting->employee_contribution_rate }}% | 
                                    {{ (float)$setting->employer_contribution_rate }}% | 
                                    {{ (float)$setting->pension_contribution_rate }}%
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm btn-edit" 
                                            data-id="{{ $setting->id }}"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#editEpfModal">
                                        <i class="ri-edit-fill"></i>
                                    </button>
                                    <form action="{{ route('epf-settings.destroy', $setting->id) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm btn-delete">
                                            <i class="ri-delete-bin-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">No EPF Rates Found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addEpfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('epf-settings.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New PF Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_enabled" id="addEnabledSwitch" checked>
                            <label class="form-check-label ms-2" for="addEnabledSwitch">Enable PF Deduction</label>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Effective From</label>
                        <div class="col-sm-7">
                            <input type="date" class="form-control" name="effective_from" required>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-5"></div>
                        <div class="col-sm-3 text-center" style="font-size:12px; font-weight:600;">Non-Senior</div>
                        <div class="col-sm-3 text-center" style="font-size:12px; font-weight:600;">Senior</div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Employee PF Rate</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="employee_contribution_rate" required>
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="senior_employee_contribution_rate">
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Employer PF Rate</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="employer_contribution_rate" required>
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="senior_employer_contribution_rate">
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Pension Rate</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="pension_contribution_rate" required>
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="senior_pension_contribution_rate">
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">EDLI Rate</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end" name="edli_contribution_rate" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Admin Charges</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end" name="admin_charges_rate" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Wage Ceiling <i class="ri-information-line" title="Enter '0' for no limit"></i></label>
                        <div class="col-sm-7">
                            <input type="number" step="1" min="0" class="form-control text-end" name="wage_ceiling" required>
                            <small class="text-muted d-block mt-1">Enter '0' for no limit</small>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Age for Seniors</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="number" step="1" min="0" class="form-control text-end" name="senior_citizen_age">
                                <span class="input-group-text">Years</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info py-2" style="font-size: 12px; background-color: #e8f9fd; border-color: #e8f9fd; color: #0d4b5f;">
                        <i class="ri-information-fill text-primary"></i> Deduction and Contribution above ceiling can be enabled at employee level. Open employee record and go to 'Salary' to configure.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editEpfModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editEpfForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit PF Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 d-flex align-items-center">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_enabled" id="editEnabledSwitch">
                            <label class="form-check-label ms-2" for="editEnabledSwitch">Enable PF Deduction</label>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Effective From</label>
                        <div class="col-sm-7">
                            <input type="date" class="form-control" name="effective_from" id="edit_effective_from" required>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-sm-5"></div>
                        <div class="col-sm-3 text-center" style="font-size:12px; font-weight:600;">Non-Senior</div>
                        <div class="col-sm-3 text-center" style="font-size:12px; font-weight:600;">Senior</div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Employee PF Rate</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="employee_contribution_rate" id="edit_employee_contribution_rate" required>
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="senior_employee_contribution_rate" id="edit_senior_employee_contribution_rate">
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Employer PF Rate</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="employer_contribution_rate" id="edit_employer_contribution_rate" required>
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="senior_employer_contribution_rate" id="edit_senior_employer_contribution_rate">
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Pension Rate</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="pension_contribution_rate" id="edit_pension_contribution_rate" required>
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end px-1" name="senior_pension_contribution_rate" id="edit_senior_pension_contribution_rate">
                                <span class="input-group-text px-1">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">EDLI Rate</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end" name="edli_contribution_rate" id="edit_edli_contribution_rate" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Admin Charges</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" class="form-control text-end" name="admin_charges_rate" id="edit_admin_charges_rate" required>
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Wage Ceiling <i class="ri-information-line" title="Enter '0' for no limit"></i></label>
                        <div class="col-sm-7">
                            <input type="number" step="1" min="0" class="form-control text-end" name="wage_ceiling" id="edit_wage_ceiling" required>
                            <small class="text-muted d-block mt-1">Enter '0' for no limit</small>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-5 col-form-label">Age for Seniors</label>
                        <div class="col-sm-7">
                            <div class="input-group">
                                <input type="number" step="1" min="0" class="form-control text-end" name="senior_citizen_age" id="edit_senior_citizen_age">
                                <span class="input-group-text">Years</span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info py-2" style="font-size: 12px; background-color: #e8f9fd; border-color: #e8f9fd; color: #0d4b5f;">
                        <i class="ri-information-fill text-primary"></i> Deduction and Contribution above ceiling can be enabled at employee level. Open employee record and go to 'Salary' to configure.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // Checkbox toggle for components
    $('.component-checkbox').on('change', function() {
        let componentId = $(this).data('id');
        let isSelected = $(this).is(':checked');
        
        $.ajax({
            url: "{{ route('epf-settings.component.update') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                component_id: componentId,
                is_selected: isSelected
            },
            success: function(res) {
                if(!res.success) {
                    alert('Error updating component.');
                }
            },
            error: function() {
                alert('An error occurred while updating the component.');
            }
        });
    });

    // Delete confirmation
    $('.btn-delete').on('click', function() {
        let form = $(this).closest('form');
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });

    // Edit modal data population
    $('.btn-edit').on('click', function() {
        let id = $(this).data('id');
        let baseUrl = "{{ route('epf-settings.index') }}";
        let formAction = baseUrl + "/" + id;
        $('#editEpfForm').attr('action', formAction);
        
        $.ajax({
            url: formAction + '/edit',
            type: "GET",
            dataType: "json",
            cache: false,
            success: function(res) {
                $('#editEnabledSwitch').prop('checked', res.is_enabled == 1 || res.is_enabled === true);
                
                if(res.effective_from) {
                    let dateStr = res.effective_from.split('T')[0].split(' ')[0];
                    $('#edit_effective_from').val(dateStr);
                }
                
                $('#edit_employee_contribution_rate').val(res.employee_contribution_rate);
                $('#edit_senior_employee_contribution_rate').val(res.senior_employee_contribution_rate);
                
                $('#edit_employer_contribution_rate').val(res.employer_contribution_rate);
                $('#edit_senior_employer_contribution_rate').val(res.senior_employer_contribution_rate);
                
                $('#edit_pension_contribution_rate').val(res.pension_contribution_rate);
                $('#edit_senior_pension_contribution_rate').val(res.senior_pension_contribution_rate);
                
                $('#edit_edli_contribution_rate').val(res.edli_contribution_rate);
                $('#edit_admin_charges_rate').val(res.admin_charges_rate);
                
                $('#edit_wage_ceiling').val(res.wage_ceiling);
                $('#edit_senior_citizen_age').val(res.senior_citizen_age);
            },
            error: function(err) {
                console.error("Error fetching data:", err);
                alert("Failed to load EPF data for editing. Please refresh the page and try again.");
            }
        });
    });

});
</script>
@endsection
