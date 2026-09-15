

<?php $__env->startSection('title'); ?>
    Business Units
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('/assets/admin/css/business.css')); ?>">

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">

<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="helpdesk-header">

    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Master Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Business Units</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Business Units</h4>
            <!-- <p>Manage one or more business units within your organization</p> -->
        </div>

        <div class="header-buttons">

            <a href="<?php echo e(route('business-units.create')); ?>" class="btn-add">
                <i class="ri-add-line"></i>
                Add New
            </a>

            <!-- <button class="btn-help">
                <i class="ri-book-open-line"></i>
                Read Help
            </button> -->

        </div>

    </div>

</div>

<?php if(session('success')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo e(session('success')); ?>

    <button type="button"
            class="btn-close"
            data-bs-dismiss="alert">
    </button>
</div>
<?php endif; ?>

<div class="card">

    <div class="card-body">

        <div class="table-responsive">

            <table id="fixed-header"
                class="table table-bordered table-striped dt-responsive nowrap align-middle"
                style="width:100%">

                <thead class="table-light">
                    <tr>
                        <th>SR NO.</th>
                        <th>UNIT NAME</th>
                        <th>REPORT TITLE</th>
                        <th>DEFAULT</th>
                        <th>SUB HEADER 1</th>
                        <th>SUB HEADER 2</th>
                        <th width="120">ACTIONS</th>
                    </tr>
                </thead>

                <tbody>

                    <?php $__empty_1 = true; $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $businessUnit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                    <tr>

                        <td><?php echo e($key + 1); ?></td>

                        <td>
                            <?php echo e($businessUnit->unit_name); ?>

                        </td>

                        <td>
                            <?php echo e($businessUnit->report_title); ?>

                        </td>

                        <td>
                            <?php if($businessUnit->is_default): ?>
                                <span class="badge bg-success">
                                    Yes
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    No
                                </span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <?php echo e($businessUnit->sub_header_1 ?? '-'); ?>

                        </td>

                        <td>
                            <?php echo e($businessUnit->sub_header_2 ?? '-'); ?>

                        </td>

                        <td>

                            <div class="dropdown d-inline-block">

                                <button class="btn btn-soft-secondary btn-sm"
                                    type="button"
                                    data-bs-toggle="dropdown">

                                    <i class="ri-more-fill align-middle"></i>

                                </button>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <li>
                                        <a href="<?php echo e(route('business-units.edit', $businessUnit->id)); ?>"
                                            class="dropdown-item">

                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>

                                            Edit

                                        </a>
                                    </li>

                                    <li>
                                        <a href="<?php echo e(route('business-units.images', $businessUnit->id)); ?>"
                                            class="dropdown-item">

                                            <i class="ri-image-fill align-bottom me-2 text-muted"></i>

                                            Manage Images

                                        </a>
                                    </li>

                                    <li>

                                        <form action="<?php echo e(route('business-units.destroy', $businessUnit->id)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this Business Unit?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="dropdown-item">
                                                <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i>
                                                Delete
                                            </button>
                                        </form>

                                    </li>

                                </ul>

                            </div>

                        </td>

                    </tr>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                    <tr>
                        <td colspan="7" class="text-center">
                            No Business Units Found
                        </td>
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

    $('#fixed-header').DataTable({
        responsive: true,
        pageLength: 10,
        fixedHeader: true
    });

});
</script>

<script src="<?php echo e(asset('assets/admin/js/business.js')); ?>"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/setup/Business_units/index.blade.php ENDPATH**/ ?>