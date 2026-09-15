@extends('layouts.master')

@section('title') IT Declarations @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Data Capture @endslot
    @slot('title') IT Declarations @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Manage Employee IT Declarations</h5>
                </div>
            </div>
            
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form method="GET" action="{{ route('capture.itdeclarations') }}">
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label class="form-label text-muted">Business Unit</label>
                                <select name="business_unit_id" class="form-control" data-choices data-choices-search-false>
                                    <option value="">All Business Units</option>
                                    @foreach($businessUnits as $bu)
                                        <option value="{{ $bu->id }}" {{ request('business_unit_id') == $bu->id ? 'selected' : '' }}>{{ $bu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label class="form-label text-muted">Location</label>
                                <select name="location_id" class="form-control" data-choices data-choices-search-false>
                                    <option value="">All Locations</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label class="form-label text-muted">Department</label>
                                <select name="department_id" class="form-control" data-choices data-choices-search-false>
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-xxl-2 col-sm-6">
                            <div>
                                <label class="form-label text-muted d-none">Financial Year</label>
                                <div class="input-group">
                                    <div class="input-group-text"><i class="ri-calendar-event-line"></i></div>
                                    <select name="financial_year" class="form-control" required id="financial_year">
                                        @php
                                            $currentYear = date('Y');
                                            $years = [
                                                ($currentYear-1) . '-' . substr($currentYear, -2),
                                                $currentYear . '-' . substr($currentYear+1, -2),
                                                ($currentYear+1) . '-' . substr($currentYear+2, -2)
                                            ];
                                            $selectedYear = request('financial_year', $currentYear . '-' . substr($currentYear+1, -2));
                                        @endphp
                                        @foreach($years as $yr)
                                            <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <div>
                                <label class="form-label text-muted d-none">Search Employee</label>
                                <div class="input-group">
                                    <input type="text" name="employee_search" class="form-control" placeholder="Search Employee Name" value="{{ request('employee_search') }}">
                                    <button type="submit" class="btn btn-primary"><i class="ri-search-line me-1"></i> Load</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                @if(request()->has('financial_year'))
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0 table-striped">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">SN</th>
                                    <th scope="col">EMPLOYEE NAME</th>
                                    <th scope="col">POSITION</th>
                                    <th scope="col" class="text-end">TOTAL DECLARED</th>
                                    <th scope="col" class="text-end">TOTAL VERIFIED</th>
                                    <th scope="col" style="width: 150px;" class="text-center">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $employee)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                            <div class="text-muted fs-12">{{ $employee->employee_code }}</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">{{ $employee->currentWorkProfile->designation->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="text-end fw-bold text-primary" id="total_declared_{{ $employee->id }}">
                                            {{ number_format($employee->total_declared, 2) }}
                                        </td>
                                        <td class="text-end fw-bold text-success" id="total_verified_{{ $employee->id }}">
                                            {{ number_format($employee->total_verified, 2) }}
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info btn-view-details" 
                                                    data-emp-id="{{ $employee->id }}"
                                                    data-emp-name="{{ $employee->first_name }} {{ $employee->last_name }}">
                                                <i class="ri-eye-line align-bottom me-1"></i> View / Edit Details
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No employees found matching the criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted p-4">
                        Please load data using the filters above.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title">IT Declaration Details - <span id="modal_employee_name" class="text-primary"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body bg-light">
                <input type="hidden" id="modal_employee_id">
                
                <div id="details_loading" class="text-center py-5 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <div id="details_content">
                    <form id="declaration_form">
                        <div id="sections_container" class="accordion custom-accordionwithicon accordion-border-box">
                            <!-- Populated via AJAX -->
                        </div>
                    </form>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save_details">Save Details</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.btn-view-details').on('click', function() {
            var empId = $(this).data('emp-id');
            var empName = $(this).data('emp-name');
            var finYear = $('#financial_year').val();

            $('#modal_employee_id').val(empId);
            $('#modal_employee_name').text(empName);
            
            $('#details_content').hide();
            $('#details_loading').removeClass('d-none');
            $('#detailsModal').modal('show');

            $.ajax({
                url: '{{ route('capture.itdeclarations.details') }}',
                type: 'GET',
                data: {
                    employee_id: empId,
                    financial_year: finYear
                },
                success: function(response) {
                    $('#details_loading').addClass('d-none');
                    $('#details_content').show();

                    var container = $('#sections_container');
                    container.empty();

                    if(response.success && response.data) {
                        var idx = 0;
                        $.each(response.data, function(section, items) {
                            var isExpanded = idx === 0 ? 'true' : 'false';
                            var collapseClass = idx === 0 ? 'show' : '';
                            
                            var sectionHtml = `
                                <div class="accordion-item mb-2">
                                    <h2 class="accordion-header" id="heading${idx}">
                                        <button class="accordion-button fw-semibold ${idx === 0 ? '' : 'collapsed'}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${idx}" aria-expanded="${isExpanded}">
                                            ${section}
                                        </button>
                                    </h2>
                                    <div id="collapse${idx}" class="accordion-collapse collapse ${collapseClass}" aria-labelledby="heading${idx}" data-bs-parent="#sections_container">
                                        <div class="accordion-body p-0">
                                            <div class="table-responsive">
                                                <table class="table table-bordered mb-0">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Particulars</th>
                                                            <th width="120">Max Limit</th>
                                                            <th width="150">Declared</th>
                                                            <th width="150">Verified</th>
                                                            <th width="200">Remarks</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                            `;

                            $.each(items, function(i, item) {
                                var maxLimitTxt = item.max_limit ? item.max_limit : 'No Limit';
                                sectionHtml += `
                                    <tr>
                                        <td>${item.name}</td>
                                        <td class="text-muted">${maxLimitTxt}</td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="items[${item.id}][declared]" value="${item.declared_amount}">
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" class="form-control form-control-sm" name="items[${item.id}][verified]" value="${item.verified_amount}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control form-control-sm" name="items[${item.id}][remarks]" value="${item.remarks}">
                                        </td>
                                    </tr>
                                `;
                            });

                            sectionHtml += `
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;

                            container.append(sectionHtml);
                            idx++;
                        });
                    }
                },
                error: function() {
                    $('#details_loading').addClass('d-none');
                    alert('Error loading details.');
                }
            });
        });

        $('#btn_save_details').on('click', function() {
            var btn = $(this);
            var empId = $('#modal_employee_id').val();
            var finYear = $('#financial_year').val();
            
            var formData = $('#declaration_form').serializeArray();
            formData.push({name: 'employee_id', value: empId});
            formData.push({name: 'financial_year', value: finYear});
            formData.push({name: '_token', value: '{{ csrf_token() }}'});

            btn.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i> Saving...');

            $.ajax({
                url: '{{ route('capture.itdeclarations.store') }}',
                type: 'POST',
                data: formData,
                success: function(response) {
                    btn.prop('disabled', false).html('Save Details');
                    if(response.success) {
                        $('#total_declared_' + empId).text(parseFloat(response.total_declared).toFixed(2));
                        $('#total_verified_' + empId).text(parseFloat(response.total_verified).toFixed(2));
                        
                        alert('Details saved successfully');
                        $('#detailsModal').modal('hide');
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('Save Details');
                    var msg = 'Error saving data';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                }
            });
        });
    });
</script>
@endsection
