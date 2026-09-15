@extends('layouts.master')

@section('title')
Professional Tax Settings
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
.state-dropdown {
    min-width: 200px;
}
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <a href="{{ route('setup') }}">Setup</a>
        <i class="ri-arrow-right-s-line"></i>
        <span>Statutory Options</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Professional Tax</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>Professional Tax</h4>
            <p class="text-muted mb-0" style="font-size:13px;">Configure Professional Tax settings and applicability.</p>
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

<form id="ptaxSettingsForm">
    @csrf
    <div class="d-flex justify-content-end mb-3">
        <div class="form-check form-switch bg-white py-2 px-4 rounded shadow-sm border">
            <input class="form-check-input mt-1" type="checkbox" id="is_enabled" name="is_enabled" value="true" {{ $setting->is_enabled ? 'checked' : '' }}>
            <label class="form-check-label ms-2 fw-bold text-dark" style="font-size: 14px;" for="is_enabled">Enable Professional Tax</label>
        </div>
    </div>
    <input type="hidden" name="calculation_basis" id="hidden_calculation_basis" value="{{ $setting->calculation_basis }}">
</form>

<div class="row">
    <!-- Left Column: Components -->
    <div class="col-md-7">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold" style="font-size:14px;">Salary Component Applicability <i class="ri-information-line text-primary" title="Select custom components if Calculation Basis is 'Custom'"></i></h6>
                <div class="d-flex align-items-center gap-2">
                    <span style="font-size:12px;" class="text-muted">Calculate on <i class="ri-information-line text-primary"></i></span>
                    <select class="form-select form-select-sm w-auto" id="calculation_basis_select" style="font-size:13px; min-width: 130px;">
                        <option value="gross_salary" {{ $setting->calculation_basis == 'gross_salary' ? 'selected' : '' }}>Gross Salary</option>
                        <option value="basic_salary" {{ $setting->calculation_basis == 'basic_salary' ? 'selected' : '' }}>Basic Salary</option>
                        <option value="custom" {{ $setting->calculation_basis == 'custom' ? 'selected' : '' }}>Custom Components</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th>NAME</th>
                                <th>TYPE</th>
                                <th class="text-center">SELECT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($components as $component)
                            <tr>
                                <td>{{ $component->name }} @if($component->short_name) ({{ $component->short_name }}) @endif</td>
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

    <!-- Right Column: PT Rates -->
    <div class="col-md-5">
        <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h6 class="mb-0 fw-bold" style="font-size:14px;">Professional Tax Rates</h6>
            </div>
            <div class="card-body bg-light">
                
                <div class="d-flex justify-content-between mb-4">
                    <select class="form-select form-select-sm state-dropdown w-auto" id="stateSelect">
                        <option value="">- Select State -</option>
                        <option value="Andhra Pradesh">Andhra Pradesh</option>
                        <option value="Assam">Assam</option>
                        <option value="Bihar">Bihar</option>
                        <option value="Gujarat">Gujarat</option>
                        <option value="Karnataka">Karnataka</option>
                        <option value="Kerala">Kerala</option>
                        <option value="Madhya Pradesh">Madhya Pradesh</option>
                        <option value="Maharashtra">Maharashtra</option>
                        <option value="Meghalaya">Meghalaya</option>
                        <option value="Odisha">Odisha</option>
                        <option value="Puducherry">Puducherry</option>
                        <option value="Sikkim">Sikkim</option>
                        <option value="Tamil Nadu">Tamil Nadu</option>
                        <option value="Telangana">Telangana</option>
                        <option value="Tripura">Tripura</option>
                        <option value="West Bengal">West Bengal</option>
                    </select>
                    
                    <button class="btn btn-primary btn-sm" id="btnAddSlab" disabled data-bs-toggle="modal" data-bs-target="#addPtaxModal">
                        <i class="ri-add-line"></i> Add
                    </button>
                </div>

                <div id="slabsContainer">
                    <div class="alert alert-info" style="font-size: 13px;">
                        Select a state to show rates.
                    </div>
                </div>
                
                <div class="card mt-4 border-0 shadow-sm">
                    <div class="card-body" style="background-color: #f0f7f9; font-size: 12px; color: #3a5b67;">
                        <h6 class="fw-bold mb-2">Important Instructions</h6>
                        <ul class="mb-0 ps-3">
                            <li>When inserting rates for a specific month, make sure you insert all slabs for that month.</li>
                            <li>Rates for specific month will override rates for "All Months".</li>
                            <li>When inserting rate for a specific gender, make sure you insert all slabs for that gender and other genders as well. <u>DO NOT</u> add rates for "All Genders" in such cases.</li>
                            <li>Rates for specific gender will override rates for "All Genders".</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addPtaxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('ptax-settings.slabs.store') }}" method="POST">
                @csrf
                <input type="hidden" name="state" id="modalStateInput">
                
                <div class="modal-header">
                    <h5 class="modal-title">Professional Tax Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">State *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modalStateDisplay" disabled>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Start Date *</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="effective_date" required>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Salary Above *</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" class="form-control" name="salary_from" value="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label">Salary To</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" class="form-control" name="salary_to" placeholder="Leave empty for infinity">
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Month *</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="month" required>
                                <option value="all">All Months</option>
                                <option value="jan">January</option>
                                <option value="feb">February</option>
                                <option value="mar">March</option>
                                <option value="apr">April</option>
                                <option value="may">May</option>
                                <option value="jun">June</option>
                                <option value="jul">July</option>
                                <option value="aug">August</option>
                                <option value="sep">September</option>
                                <option value="oct">October</option>
                                <option value="nov">November</option>
                                <option value="dec">December</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Gender *</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="gender" required>
                                <option value="all">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Tax Amount *</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" class="form-control" name="tax_amount" value="0" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-primary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editPtaxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="state" id="editModalStateInput">
                
                <div class="modal-header">
                    <h5 class="modal-title">Edit Professional Tax Rate</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">State *</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="editModalStateDisplay" disabled>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Start Date *</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" name="effective_date" required>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Salary Above *</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" class="form-control" name="salary_from" value="0" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label">Salary To</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" class="form-control" name="salary_to" placeholder="Leave empty for infinity">
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Month *</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="month" required>
                                <option value="all">All Months</option>
                                <option value="jan">January</option>
                                <option value="feb">February</option>
                                <option value="mar">March</option>
                                <option value="apr">April</option>
                                <option value="may">May</option>
                                <option value="jun">June</option>
                                <option value="jul">July</option>
                                <option value="aug">August</option>
                                <option value="sep">September</option>
                                <option value="oct">October</option>
                                <option value="nov">November</option>
                                <option value="dec">December</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Gender *</label>
                        <div class="col-sm-8">
                            <select class="form-select" name="gender" required>
                                <option value="all">All Genders</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-danger">Tax Amount *</label>
                        <div class="col-sm-8">
                            <input type="number" step="0.01" min="0" class="form-control" name="tax_amount" value="0" required>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-primary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
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
    
    // Handle Enable/Disable Toggle
    $('#is_enabled').on('change', function() {
        let formData = $('#ptaxSettingsForm').serialize();
        
        $.ajax({
            url: "{{ route('ptax-settings.update') }}",
            type: "POST",
            data: formData,
            success: function(res) {
                if(res.success) {
                    // silent success for toggle
                } else {
                    Swal.fire('Error', res.message, 'error');
                    // revert toggle
                    $('#is_enabled').prop('checked', !$('#is_enabled').prop('checked'));
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while saving settings.', 'error');
            }
        });
    });

    // Handle Calculation Basis Change
    $('#calculation_basis_select').on('change', function() {
        $('#hidden_calculation_basis').val($(this).val());
        
        let formData = $('#ptaxSettingsForm').serialize();
        
        $.ajax({
            url: "{{ route('ptax-settings.update') }}",
            type: "POST",
            data: formData,
            success: function(res) {
                if(res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Calculation basis updated.',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'An error occurred while saving settings.', 'error');
            }
        });
    });

    // Checkbox toggle for components
    $('.component-checkbox').on('change', function() {
        let componentId = $(this).data('id');
        let isSelected = $(this).is(':checked');
        
        $.ajax({
            url: "{{ route('ptax-settings.component.update') }}",
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

    // State selection
    $('#stateSelect').on('change', function() {
        let state = $(this).val();
        
        if (state) {
            $('#btnAddSlab').prop('disabled', false);
            $('#modalStateInput').val(state);
            $('#modalStateDisplay').val(state);
            
            // Fetch slabs
            $.ajax({
                url: "{{ route('ptax-settings.slabs.get') }}",
                type: "GET",
                data: { state: state },
                success: function(res) {
                    if(res.success) {
                        renderSlabs(res.slabs);
                    }
                }
            });
            
        } else {
            $('#btnAddSlab').prop('disabled', true);
            $('#slabsContainer').html('<div class="alert alert-info" style="font-size: 13px;">Select a state to show rates.</div>');
        }
    });

    function renderSlabs(slabs) {
        if (slabs.length === 0) {
            $('#slabsContainer').html('<div class="alert alert-warning" style="font-size: 13px;">No rates found for the selected state.</div>');
            return;
        }
        
        let groupedSlabs = {};
        slabs.forEach(function(slab) {
            if (!groupedSlabs[slab.effective_date]) {
                groupedSlabs[slab.effective_date] = [];
            }
            groupedSlabs[slab.effective_date].push(slab);
        });

        let html = '';
        for (let date in groupedSlabs) {
            html += '<div class="mb-4 bg-white p-3 border rounded shadow-sm">';
            let formattedDate = new Date(date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }).replace(/ /g, '-');
            html += '<h6 class="text-primary mb-3" style="font-size:13px; font-weight:600;">Effective From: ' + formattedDate + '</h6>';
            
            groupedSlabs[date].forEach(function(slab) {
                let destroyRoute = "{{ route('ptax-settings.slabs.destroy', ':id') }}";
                let deleteUrl = destroyRoute.replace(':id', slab.id);
                
                html += '<div class="row align-items-center mb-2 pb-2 border-bottom" style="font-size: 12px; color: #333;">';
                html += '<div class="col-3">Base Salary<br>> ' + slab.salary_from + '</div>';
                html += '<div class="col-3">Tax Amount<br>' + slab.tax_amount + '</div>';
                
                let genderText = slab.gender == 'all' ? 'All Genders' : slab.gender;
                let monthText = slab.month == 'all' ? 'All Months' : slab.month;
                
                html += '<div class="col-4 text-capitalize">' + genderText + '<br>' + monthText + '</div>';
                html += '<div class="col-2 text-end">';
                
                html += '<button type="button" class="btn btn-primary rounded-circle me-1 btn-edit-slab" data-slab=\'' + JSON.stringify(slab).replace(/'/g, "&#39;") + '\' style="width:24px; height:24px; padding:0; font-size:11px;"><i class="ri-pencil-fill"></i></button>';
                
                html += '<form action="' + deleteUrl + '" method="POST" class="d-inline delete-slab-form">';
                html += '@csrf @method("DELETE")';
                html += '<button type="button" class="btn btn-danger rounded-circle btn-delete-slab" style="width:24px; height:24px; padding:0; font-size:11px;"><i class="ri-delete-bin-fill"></i></button>';
                html += '</form>';
                
                html += '</div>';
                html += '</div>';
            });
            html += '</div>';
        }
        
        $('#slabsContainer').html(html);
        
        // Re-attach delete event
        attachDeleteEvent();
    }

    function attachDeleteEvent() {
        $('.btn-delete-slab').off('click').on('click', function() {
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: "You want to delete this slab?",
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
    }

    $(document).on('click', '.btn-edit-slab', function() {
        let slab = $(this).data('slab');
        let updateRoute = "{{ route('ptax-settings.slabs.update', ':id') }}".replace(':id', slab.id);
        
        $('#editPtaxModal form').attr('action', updateRoute);
        $('#editPtaxModal #editModalStateInput').val(slab.state);
        $('#editPtaxModal #editModalStateDisplay').val(slab.state);
        $('#editPtaxModal [name="effective_date"]').val(slab.effective_date);
        $('#editPtaxModal [name="salary_from"]').val(slab.salary_from);
        $('#editPtaxModal [name="salary_to"]').val(slab.salary_to);
        $('#editPtaxModal [name="month"]').val(slab.month);
        $('#editPtaxModal [name="gender"]').val(slab.gender);
        $('#editPtaxModal [name="tax_amount"]').val(slab.tax_amount);
        
        $('#editPtaxModal').modal('show');
    });

});
</script>
@endsection
