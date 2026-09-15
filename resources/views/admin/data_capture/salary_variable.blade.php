@extends('layouts.master')

@section('title') @lang('translation.salary_variable') @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Salary - Variable</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Data Capture</a></li>
                    <li class="breadcrumb-item active">Salary - Variable</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Capture variable salary. You can also import in bulk using 'Export & Import' button.</h5>
                <div class="dropdown">
                    <button class="btn btn-danger dropdown-toggle" type="button" id="optionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        Options <i class="mdi mdi-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="optionsDropdown">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#nonCashSalaryModal">Add Non Cash Salary</a></li>
                        <li><a class="dropdown-item" href="{{ route('capture.salaryvariable.export-template') }}">Download Template</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadExcelModal">Upload</a></li>
                        <li><a class="dropdown-item" href="{{ route('capture.salaryvariable.download-data') }}">Download Data</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('capture.salaryvariable') }}">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Business Units</label>
                            <select name="business_unit_id" class="form-select form-select-sm">
                                <option value="">All Business Units</option>
                                @foreach($businessUnits as $bu)
                                    <option value="{{ $bu->id }}" {{ request('business_unit_id') == $bu->id ? 'selected' : '' }}>{{ $bu->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Location</label>
                            <select name="location_id" class="form-select form-select-sm">
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Department</label>
                            <select name="department_id" class="form-select form-select-sm">
                                <option value="">All Departments</option>
                                @foreach($departments as $dep)
                                    <option value="{{ $dep->id }}" {{ request('department_id') == $dep->id ? 'selected' : '' }}>{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Variable Component</label>
                            <select name="salary_component_id" class="form-select form-select-sm" required>
                                <option value="">-- Select --</option>
                                @foreach($variableComponents as $comp)
                                    <option value="{{ $comp->id }}" {{ request('salary_component_id') == $comp->id ? 'selected' : '' }}>{{ $comp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="arrear" id="arrearCheck" value="1" {{ request('arrear') ? 'checked' : '' }}>
                                <label class="form-check-label" for="arrearCheck">Arrear</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-end">
                        <div class="col-md-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                <input type="month" name="payroll_month" class="form-control" value="{{ request('payroll_month', date('Y-m')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-sm">
                                <input type="text" name="employee_search" class="form-control" placeholder="Search employees" value="{{ request('employee_search') }}">
                                <button class="btn btn-secondary" type="submit"><i class="mdi mdi-magnify"></i> View</button>
                            </div>
                        </div>
                    </div>
                </form>

                @if(request()->has('salary_component_id') && request()->has('payroll_month'))
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>SN</th>
                                    <th>EMPLOYEE NAME</th>
                                    <th>LOCATION</th>
                                    <th>DEPARTMENT</th>
                                    <th style="width: 150px;">AMOUNT</th>
                                    <th style="width: 250px;">COMMENTS</th>
                                    <th style="width: 50px;"></th>
                                    <th style="width: 100px;">TOTAL</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong><br>
                                        <small class="text-muted">{{ $employee->employee_code }}</small>
                                    </td>
                                    <td>{{ $employee->currentWorkProfile->location->name ?? 'N/A' }}</td>
                                    <td>{{ $employee->currentWorkProfile->department->name ?? 'N/A' }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm inline-amount" id="inline_amount_{{ $employee->id }}" placeholder="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm inline-comment" id="inline_comment_{{ $employee->id }}" placeholder="Comments">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 inline-save-btn" 
                                            data-emp-id="{{ $employee->id }}"
                                            style="width: 24px; height: 24px; padding: 0;" title="Save Entry">
                                            <i class="mdi mdi-check fs-12"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <span id="total_{{ $employee->id }}" class="fw-bold text-primary">{{ $employee->current_variable_amount }}</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                            data-bs-toggle="modal" data-bs-target="#salaryDetailsModal"
                                            data-emp-id="{{ $employee->id }}"
                                            data-emp-name="{{ $employee->first_name }} {{ $employee->last_name }}"
                                            id="view_btn_{{ $employee->id }}"
                                            style="width: 24px; height: 24px; padding: 0; display: {{ $employee->current_variable_amount > 0 ? 'block' : 'none' }};" title="View Details">
                                            <i class="mdi mdi-format-list-bulleted fs-12"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">No employees found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info">Please select a variable component and month to view employees.</div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Non Cash Salary Modal -->
<div class="modal fade" id="nonCashSalaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('capture.salaryvariable.transfer-non-cash') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fs-14">Add Non Cash Salary</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label fs-12 text-muted">Source Component <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="source_component_id" class="form-select form-select-sm" required>
                                <option value="">- Select -</option>
                                @foreach($nonPayableComponents as $comp)
                                    <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label fs-12 text-muted">Target Component <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="target_component_id" class="form-select form-select-sm" required>
                                <option value="">- Select -</option>
                                @foreach($payableComponents as $comp)
                                    <option value="{{ $comp->id }}">{{ $comp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label fs-12 text-muted">Date Range <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="d-flex align-items-center gap-2">
                                <input type="date" name="date_from" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                                <span class="text-muted fs-12">to</span>
                                <input type="date" name="date_to" class="form-control form-control-sm" required value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label fs-12 text-muted">Select Employee</label>
                        <div class="col-sm-8">
                            <select name="employee_id" class="form-select form-select-sm">
                                <option value="">Search...</option>
                                @foreach(\App\Models\Employee::all() as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Hidden target payroll month since it's not in the screenshot. It might be calculated dynamically based on current payroll period on backend -->
                    <input type="hidden" name="target_payroll_month" value="{{ date('Y-m') }}">

                    <div class="alert alert-info border-0 bg-info-subtle text-info fs-12 mb-0">
                        Import will overwrite existing data for selected employee and target component.
                    </div>

                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-link link-danger fw-medium text-decoration-none btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Add</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Upload Excel Modal -->
<div class="modal fade" id="uploadExcelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('capture.salaryvariable.upload-excel') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fs-14">Upload Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Choose Excel File</label>
                        <input type="file" name="file" class="form-control form-control-sm" required accept=".xlsx,.xls,.csv">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-link link-danger fw-medium text-decoration-none btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Salary Details Modal -->
<div class="modal fade" id="salaryDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fs-14">Salary Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3 mt-3">
                    <div class="col-md-4">
                        <p class="text-muted mb-1 fs-12">Employee</p>
                        <h6 class="mb-0 fs-13" id="detail_employee_name"></h6>
                        <input type="hidden" id="detail_emp_id">
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1 fs-12">Salary Component</p>
                        <h6 class="mb-0 fs-13" id="detail_component_name"></h6>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted mb-1 fs-12">Total</p>
                        <h6 class="mb-0 fs-13" id="detail_total_amount"></h6>
                    </div>
                </div>
                
                <!-- Add form inside details modal -->
                <div class="card bg-light mb-3 shadow-none border">
                    <div class="card-body p-2">
                        <div class="row gx-2 align-items-end">
                            <div class="col-md-5">
                                <label class="form-label fs-12 mb-1">Amount</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="details_modal_amount">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label fs-12 mb-1">Comments</label>
                                <input type="text" class="form-control form-control-sm" id="details_modal_comments">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-primary btn-sm w-100" id="submit_details_add_entry_btn">Add</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="fs-12">CAPTURE DATE</th>
                                <th class="fs-12">COMMENTS</th>
                                <th class="fs-12 text-end">AMOUNT</th>
                                <th width="50"></th>
                            </tr>
                        </thead>
                        <tbody id="salary_details_body">
                            <!-- Populated via AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-link link-primary fw-medium text-decoration-none btn-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
        
        function saveEntry(empId, amount, comments, btn, successCallback) {
            var salaryCompId = $('select[name="salary_component_id"]').val();
            var payrollMonth = $('input[name="payroll_month"]').val();
            var isArrear = $('#arrearCheck').is(':checked') ? 1 : 0;
            
            if(!amount || amount === '') {
                alert('Amount is required');
                return;
            }

            var originalBtnText = btn.html();
            btn.html('<i class="mdi mdi-loading mdi-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('capture.salaryvariable.store') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    employee_id: empId,
                    salary_component_id: salaryCompId,
                    payroll_month: payrollMonth,
                    amount: amount,
                    comments: comments,
                    is_arrear: isArrear
                },
                success: function(response) {
                    btn.html(originalBtnText).prop('disabled', false);
                    
                    if(response.total !== undefined && response.total !== null) {
                        $('#total_' + empId).text(response.total);
                        $('#detail_total_amount').text(response.total);
                        if (parseFloat(response.total) > 0) {
                            $('#view_btn_' + empId).show();
                        } else {
                            $('#view_btn_' + empId).hide();
                        }
                    }
                    
                    if(successCallback) successCallback(response);
                },
                error: function(xhr) {
                    btn.html(originalBtnText).prop('disabled', false);
                    var msg = 'Error saving data';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                }
            });
        }
        // Handle Inline Entry Submit
        function submitInlineEntry(empId, btn) {
            var amount = $('#inline_amount_' + empId).val();
            var comments = $('#inline_comment_' + empId).val();
            
            if(!amount || amount === '') {
                alert('Amount is required');
                return;
            }
            
            saveEntry(empId, amount, comments, btn, function(response) {
                // On success, clear the inline inputs
                $('#inline_amount_' + empId).val('');
                $('#inline_comment_' + empId).val('');
            });
        }

        $(document).off('click', '.inline-save-btn').on('click', '.inline-save-btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var empId = btn.data('emp-id');
            submitInlineEntry(empId, btn);
        });

        $(document).off('keypress', '.inline-amount, .inline-comment').on('keypress', '.inline-amount, .inline-comment', function(e) {
            if(e.which == 13) { // Enter key
                e.preventDefault();
                var empId = $(this).closest('tr').find('.inline-save-btn').data('emp-id');
                var btn = $(this).closest('tr').find('.inline-save-btn');
                submitInlineEntry(empId, btn);
            }
        });

        // Handle Add Entry Submit from Details Modal
        $(document).off('click', '#submit_details_add_entry_btn').on('click', '#submit_details_add_entry_btn', function(e) {
            e.preventDefault();
            var btn = $(this);
            var empId = $('#detail_emp_id').val();
            var amount = $('#details_modal_amount').val();
            var comments = $('#details_modal_comments').val();
            
            saveEntry(empId, amount, comments, btn, function(response) {
                $('#details_modal_amount').val('');
                $('#details_modal_comments').val('');
                loadSalaryDetails(empId);
            });
        });

        function loadSalaryDetails(empId) {
            var salaryCompId = $('select[name="salary_component_id"]').val();
            var payrollMonth = $('input[name="payroll_month"]').val();
            var isArrear = $('#arrearCheck').is(':checked') ? 1 : 0;

            $('#salary_details_body').html('<tr><td colspan="4" class="text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

            $.ajax({
                url: "{{ route('capture.salaryvariable.details') }}",
                type: "GET",
                data: {
                    employee_id: empId,
                    salary_component_id: salaryCompId,
                    payroll_month: payrollMonth,
                    is_arrear: isArrear
                },
                success: function(response) {
                    var html = '';
                    if(response.data && response.data.length > 0) {
                        response.data.forEach(function(item) {
                            var date = new Date(item.created_at);
                            var formattedDate = ("0" + date.getDate()).slice(-2) + "-" + ("0"+(date.getMonth()+1)).slice(-2) + "-" + date.getFullYear();
                            
                            html += '<tr>';
                            html += '<td>' + formattedDate + '</td>';
                            html += '<td>' + (item.comments ? item.comments : '') + '</td>';
                            html += '<td class="text-end">' + parseFloat(item.amount).toFixed(2) + '</td>';
                            html += '<td class="text-center"><button class="btn btn-sm btn-soft-danger rounded-circle delete-detail-btn" data-id="' + item.id + '"><i class="ri-delete-bin-line fs-14"></i></button></td>';
                            html += '</tr>';
                        });
                    } else {
                        html = '<tr><td colspan="4" class="text-center text-muted">No details found.</td></tr>';
                    }
                    $('#salary_details_body').html(html);
                },
                error: function(xhr) {
                    $('#salary_details_body').html('<tr><td colspan="4" class="text-center text-danger">Error loading details.</td></tr>');
                }
            });
        }

        // Bootstrap 5 standard way to handle Salary Details Modal Data
        var salaryDetailsModalEl = document.getElementById('salaryDetailsModal');
        if (salaryDetailsModalEl) {
            salaryDetailsModalEl.addEventListener('show.bs.modal', function (event) {
                try {
                    var btn = $(event.relatedTarget).closest('button');
                    var empId = btn.attr('data-emp-id') || btn.data('emp-id');
                    var empName = btn.attr('data-emp-name') || btn.data('emp-name');
                    var total = $('#total_' + empId).text();
                    
                    var salaryCompId = $('select[name="salary_component_id"]').val();
                    var salaryCompName = $('select[name="salary_component_id"] option:selected').text();
                    
                    $('#detail_employee_name').text(empName);
                    $('#detail_emp_id').val(empId);
                    $('#detail_component_name').text(salaryCompName);
                    $('#detail_total_amount').text(total);
                    
                    loadSalaryDetails(empId);
                } catch (e) {
                    console.error('Error populating Details Modal:', e);
                }
            });
        }

        // Delete detail
        $(document).off('click', '.delete-detail-btn').on('click', '.delete-detail-btn', function(e) {
            e.preventDefault();
            if(!confirm('Are you sure you want to delete this entry?')) return;
            
            var btn = $(this);
            var id = btn.data('id');
            var empId = $('#detail_emp_id').val();
            
            btn.html('<i class="mdi mdi-loading mdi-spin"></i>').prop('disabled', true);
            
            $.ajax({
                url: "{{ route('capture.salaryvariable.delete', '') }}/" + id,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    // Update total on main table
                    $('#total_' + empId).text(response.total);
                    $('#detail_total_amount').text(response.total);
                    
                    if (parseFloat(response.total) == 0) {
                        $('#view_btn_' + empId).hide();
                    }
                    
                    // Remove row
                    btn.closest('tr').fadeOut(300, function() {
                        $(this).remove();
                        if($('#salary_details_body tr').length == 0) {
                            $('#salary_details_body').html('<tr><td colspan="4" class="text-center text-muted">No details found.</td></tr>');
                        }
                    });
                },
                error: function(xhr) {
                    btn.html('<i class="ri-delete-bin-line fs-14"></i>').prop('disabled', false);
                    alert('Error deleting entry');
                }
            });
        });
</script>
@endsection
