<?php $__env->startSection('profile_title', 'Activity Logs'); ?>
<?php $__env->startSection('profile_description', 'Chronological audit trail and change logs for this employee.'); ?>

<?php $__env->startSection('profile_content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h6 class="fw-bold mb-0 text-dark">Audit Trail (<?php echo e($logs->total()); ?> events recorded)</h6>
</div>

<?php if($logs->count() > 0): ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 180px;">Date & Time</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td class="text-muted small">
                            <?php echo e($log->created_at ? $log->created_at->format('d-M-Y h:i A') : 'N/A'); ?>

                        </td>
                        <td>
                            <span class="badge bg-soft-primary text-primary px-2 py-1"><?php echo e($log->action ?? 'Update'); ?></span>
                        </td>
                        <td class="text-dark"><?php echo e($log->description ?? 'Record modified'); ?></td>
                        <td class="text-muted small"><?php echo e($log->ip ?? '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <?php echo e($logs->links()); ?>

    </div>
<?php else: ?>
    <div class="text-center py-5">
        <i class="ri-history-line text-muted" style="font-size: 48px;"></i>
        <h6 class="mt-3 text-dark fw-bold">No Activity Logs Found</h6>
        <p class="text-muted small">All employee profile changes and system actions will automatically be logged here.</p>
    </div>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/activity-logs.blade.php ENDPATH**/ ?>