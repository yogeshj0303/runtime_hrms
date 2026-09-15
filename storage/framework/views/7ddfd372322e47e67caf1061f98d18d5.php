

<?php $__env->startSection('title'); ?>
    All Employees
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="helpdesk-header">

    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Employee Management</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>All Employees</span>
    </div>

    <!-- Alert for pending confirmations -->
    <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
        <strong>Employee confirmation updates available.</strong> <a href="#" class="alert-link">Read More</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>All Employees</h4>
            <p class="text-muted">
                List of employees. Filter by location, cost center or department.
            </p>
        </div>

        <div class="header-buttons">
        </div>

    </div>

</div>

<div class="card">

    <div class="card-body">

        <!-- Filters Form -->
        <form action="<?php echo e(route('business.employee')); ?>" method="GET" id="employeeFilterForm">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label class="form-label fs-12 text-muted mb-1">Business Unit</label>
                    <select class="form-select form-select-sm" name="business_unit_id" onchange="this.form.submit()">
                        <option value="">All Units</option>
                        <?php $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($unit->id); ?>" <?php echo e(request('business_unit_id') == $unit->id ? 'selected' : ''); ?>><?php echo e($unit->unit_name ?? $unit->name ?? $unit->report_title ?? 'Unit ' . $unit->id); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12 text-muted mb-1">Location</label>
                    <select class="form-select form-select-sm" name="location_id" onchange="this.form.submit()">
                        <option value="">All Locations</option>
                        <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($loc->id); ?>" <?php echo e(request('location_id') == $loc->id ? 'selected' : ''); ?>><?php echo e($loc->name ?? 'Location ' . $loc->id); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12 text-muted mb-1">Cost Center</label>
                    <select class="form-select form-select-sm" name="cost_center_id" onchange="this.form.submit()">
                        <option value="">All Cost Centers</option>
                        <?php $__currentLoopData = $costCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cc->id); ?>" <?php echo e(request('cost_center_id') == $cc->id ? 'selected' : ''); ?>><?php echo e($cc->name ?? 'CC ' . $cc->id); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-12 text-muted mb-1">Department</label>
                    <select class="form-select form-select-sm" name="department_id" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($dept->id); ?>" <?php echo e(request('department_id') == $dept->id ? 'selected' : ''); ?>><?php echo e($dept->name ?? 'Dept ' . $dept->id); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-12 text-muted mb-1">Designation</label>
                    <select class="form-select form-select-sm" name="designation_id" onchange="this.form.submit()">
                        <option value="">All Designations</option>
                        <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desig): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($desig->id); ?>" <?php echo e(request('designation_id') == $desig->id ? 'selected' : ''); ?>><?php echo e($desig->name ?? 'Desig ' . $desig->id); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <!-- Search and Status Toggle -->
            <div class="d-flex justify-content-between align-items-end mb-4">
                <div style="width: 250px;">
                    <label class="form-label fs-12 text-muted mb-1">Search Employee</label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" name="search" placeholder="Search employee" value="<?php echo e(request('search')); ?>">
                        <button type="submit" class="btn btn-secondary"><i class="ri-search-line"></i></button>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check form-switch d-flex align-items-center gap-2">
                        <label class="form-check-label fs-13 fw-bold text-primary" for="statusActive">Active</label>
                        <input class="form-check-input ms-0 mt-0" style="width: 35px; height: 18px;" type="checkbox" role="switch" name="status" id="statusActive" value="active" <?php echo e(request('status', 'active') == 'active' ? 'checked' : ''); ?> onchange="if(!this.checked) this.value='inactive'; this.form.submit()">
                        <label class="form-check-label fs-13 text-muted" for="statusInactive">Inactive</label>
                    </div>
                </div>
            </div>
        </form>

        <!-- Employee Table -->
        <div class="table-responsive">
            <table id="employeeTable" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Employee</th>
                        <th>Employee Code</th>
                        <th>Designation</th>
                        <th>Department</th>
                        <th>Location</th>
                        <th>Joining Date</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $wp = $employee->workProfiles->first();
                        $designationName = $wp && $wp->designation ? $wp->designation->name : $employee->designation;
                        $departmentName = $wp && $wp->department ? $wp->department->name : $employee->department;
                        $locationName = $wp && $wp->location ? $wp->location->name : 'N/A';
                    ?>
                    <tr>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <a href="<?php echo e(route('employee.profile.summary', ['id' => $employee->id])); ?>" class="text-body fw-bold">
                                <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?>

                            </a>
                        </td>
                        <td><?php echo e($employee->employee_code); ?></td>
                        <td><?php echo e($designationName); ?></td>
                        <td><?php echo e($departmentName); ?></td>
                        <td><?php echo e($locationName); ?></td>
                        <td><?php echo e($employee->joining_date ? $employee->joining_date->format('d-M-Y') : 'N/A'); ?></td>
                        <td>
                            <?php if($employee->status === 'inactive'): ?>
                                <span class="badge bg-danger mb-1">Inactive</span>
                                <?php if($employee->exit_date): ?>
                                    <div class="fs-11 text-muted">Exited: <?php echo e(\Carbon\Carbon::parse($employee->exit_date)->format('d-M-Y')); ?></div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="badge bg-success mb-1">Active</span>
                                <!-- Mock confirmation pending check -->
                                <?php if(rand(0, 3) == 1): ?>
                                    <br><a href="#" class="badge bg-info mt-1">Confirm</a>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <a href="<?php echo e(route('employee.profile.basic', ['id' => $employee->id])); ?>" class="btn btn-soft-primary btn-sm d-inline-flex align-items-center justify-content-center" style="width: 30px; height: 30px; border-radius: 4px;" title="Edit">
                                    <i class="ri-pencil-fill"></i>
                                </a>
                                <div class="dropdown">
                                    <button class="btn btn-soft-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                        Options
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a href="<?php echo e(route('employee.statement', ['id' => $employee->id])); ?>" class="dropdown-item">
                                                <i class="ri-file-download-line me-2"></i> Download Statement
                                            </a>
                                        </li>
                                        <li>
                                            <form action="<?php echo e(route('employee.toggle-status', ['id' => $employee->id])); ?>" method="POST">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="dropdown-item text-<?php echo e($employee->status === 'inactive' ? 'success' : 'danger'); ?>">
                                                    <i class="ri-shut-down-line me-2"></i> <?php echo e($employee->status === 'inactive' ? 'Activate' : 'Deactivate'); ?>

                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">No employees found.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>

<script>

$(document).ready(function () {

    $('#employeeTable').DataTable({

        responsive: true,

        pageLength: 10,

        ordering: true,

        searching: true

    });

});

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/index.blade.php ENDPATH**/ ?>