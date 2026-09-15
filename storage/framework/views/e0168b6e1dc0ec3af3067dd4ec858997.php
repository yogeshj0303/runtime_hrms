<?php $__env->startSection('title'); ?> <?php echo app('translator')->get('translation.salary_variable'); ?> <?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                        <li><a class="dropdown-item" href="<?php echo e(route('capture.salaryvariable.export-template')); ?>">Download Template</a></li>
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#uploadExcelModal">Upload</a></li>
                        <li><a class="dropdown-item" href="<?php echo e(route('capture.salaryvariable.download-data')); ?>">Download Data</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="<?php echo e(route('capture.salaryvariable')); ?>">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Business Units</label>
                            <select name="business_unit_id" class="form-select form-select-sm">
                                <option value="">All Business Units</option>
                                <?php $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($bu->id); ?>" <?php echo e(request('business_unit_id') == $bu->id ? 'selected' : ''); ?>><?php echo e($bu->unit_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Location</label>
                            <select name="location_id" class="form-select form-select-sm">
                                <option value="">All Locations</option>
                                <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($loc->id); ?>" <?php echo e(request('location_id') == $loc->id ? 'selected' : ''); ?>><?php echo e($loc->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Department</label>
                            <select name="department_id" class="form-select form-select-sm">
                                <option value="">All Departments</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dep->id); ?>" <?php echo e(request('department_id') == $dep->id ? 'selected' : ''); ?>><?php echo e($dep->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Select Variable Component</label>
                            <select name="salary_component_id" class="form-select form-select-sm" required>
                                <option value="">-- Select --</option>
                                <?php $__currentLoopData = $variableComponents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($comp->id); ?>" <?php echo e(request('salary_component_id') == $comp->id ? 'selected' : ''); ?>><?php echo e($comp->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="arrear" id="arrearCheck" value="1" <?php echo e(request('arrear') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="arrearCheck">Arrear</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4 align-items-end">
                        <div class="col-md-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                <input type="month" name="payroll_month" class="form-control" value="<?php echo e(request('payroll_month', date('Y-m'))); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="input-group input-group-sm">
                                <input type="text" name="employee_search" class="form-control" placeholder="Search employees" value="<?php echo e(request('employee_search')); ?>">
                                <button class="btn btn-secondary" type="submit"><i class="mdi mdi-magnify"></i> View</button>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if(request()->has('salary_component_id') && request()->has('payroll_month')): ?>
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
                                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td>
                                        <strong><?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?></strong><br>
                                        <small class="text-muted"><?php echo e($employee->employee_code); ?></small>
                                    </td>
                                    <td><?php echo e($employee->currentWorkProfile->location->name ?? 'N/A'); ?></td>
                                    <td><?php echo e($employee->currentWorkProfile->department->name ?? 'N/A'); ?></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm inline-amount" id="inline_amount_<?php echo e($employee->id); ?>" placeholder="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control form-control-sm inline-comment" id="inline_comment_<?php echo e($employee->id); ?>" placeholder="Comments">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 inline-save-btn" 
                                            data-emp-id="<?php echo e($employee->id); ?>"
                                            style="width: 24px; height: 24px; padding: 0;" title="Save Entry">
                                            <i class="mdi mdi-check fs-12"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <span id="total_<?php echo e($employee->id); ?>" class="fw-bold text-primary"><?php echo e($employee->current_variable_amount); ?></span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                            data-bs-toggle="modal" data-bs-target="#salaryDetailsModal"
                                            data-emp-id="<?php echo e($employee->id); ?>"
                                            data-emp-name="<?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?>"
                                            id="view_btn_<?php echo e($employee->id); ?>"
                                            style="width: 24px; height: 24px; padding: 0; display: <?php echo e($employee->current_variable_amount > 0 ? 'block' : 'none'); ?>;" title="View Details">
                                            <i class="mdi mdi-format-list-bulleted fs-12"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="9" class="text-center">No employees found.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">Please select a variable component and month to view employees.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Non Cash Salary Modal -->
<div class="modal fade" id="nonCashSalaryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('capture.salaryvariable.transfer-non-cash')); ?>" method="POST">
            <?php echo csrf_field(); ?>
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
                                <?php $__currentLoopData = $nonPayableComponents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($comp->id); ?>"><?php echo e($comp->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label fs-12 text-muted">Target Component <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="target_component_id" class="form-select form-select-sm" required>
                                <option value="">- Select -</option>
                                <?php $__currentLoopData = $payableComponents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($comp->id); ?>"><?php echo e($comp->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label fs-12 text-muted">Date Range <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <div class="d-flex align-items-center gap-2">
                                <input type="date" name="date_from" class="form-control form-control-sm" required value="<?php echo e(date('Y-m-d')); ?>">
                                <span class="text-muted fs-12">to</span>
                                <input type="date" name="date_to" class="form-control form-control-sm" required value="<?php echo e(date('Y-m-d')); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label fs-12 text-muted">Select Employee</label>
                        <div class="col-sm-8">
                            <select name="employee_id" class="form-select form-select-sm">
                                <option value="">Search...</option>
                                <?php $__currentLoopData = \App\Models\Employee::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $emp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($emp->id); ?>"><?php echo e($emp->first_name); ?> <?php echo e($emp->last_name); ?> (<?php echo e($emp->employee_code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <!-- Hidden target payroll month since it's not in the screenshot. It might be calculated dynamically based on current payroll period on backend -->
                    <input type="hidden" name="target_payroll_month" value="<?php echo e(date('Y-m')); ?>">

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
        <form action="<?php echo e(route('capture.salaryvariable.upload-excel')); ?>" method="POST" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
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

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
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
                url: "<?php echo e(route('capture.salaryvariable.store')); ?>",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
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
                url: "<?php echo e(route('capture.salaryvariable.details')); ?>",
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
                url: "<?php echo e(route('capture.salaryvariable.delete', '')); ?>/" + id,
                type: "DELETE",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>"
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/data_capture/salary_variable.blade.php ENDPATH**/ ?>