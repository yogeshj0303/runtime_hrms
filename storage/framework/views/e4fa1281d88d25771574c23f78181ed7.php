<?php $__env->startSection('profile_title', 'Strikes & Disciplinary'); ?>
<?php $__env->startSection('profile_description', 'Manage attendance strikes and disciplinary actions for this employee.'); ?>

<?php $__env->startSection('profile_actions'); ?>
    <button type="button" class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addStrikeModal">
        <i class="ri-add-line"></i> Add Manual Strike
    </button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('profile_content'); ?>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Rule Applied</th>
                <th>Penalty/Action</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $strikes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $strike): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($strike->strike_date->format('d M, Y')); ?></td>
                    <td>
                        <?php if($strike->strikeRule): ?>
                            <span class="badge bg-<?php echo e(strtolower($strike->strikeRule->strike_color) == 'red' ? 'danger' : (strtolower($strike->strikeRule->strike_color) == 'yellow' ? 'warning' : 'primary')); ?>">
                                <?php echo e($strike->strikeRule->rule_type); ?>

                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Manual Assignment</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($strike->strikeRule): ?>
                            <?php echo e($strike->strikeRule->deduction_type); ?> 
                            <?php if($strike->strikeRule->deduction_value): ?>
                                (<?php echo e($strike->strikeRule->deduction_value); ?>)
                            <?php endif; ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($strike->reason); ?></td>
                    <td>
                        <?php if($strike->status == 'Active'): ?>
                            <span class="badge bg-success-subtle text-success">Active</span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger">Waived</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($strike->status == 'Active'): ?>
                            <form action="<?php echo e(route('employee.profile.strikes.waive', ['id' => $employee->id, 'strike' => $strike->id])); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-soft-danger" onclick="return confirm('Are you sure you want to waive this strike?')">Waive</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted p-4">No strikes found for this employee.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Strike Modal -->
<div class="modal fade" id="addStrikeModal" tabindex="-1" aria-labelledby="addStrikeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="<?php echo e(route('employee.profile.strikes.store', $employee->id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStrikeModalLabel">Add Manual Strike</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Date of Strike <span class="text-danger">*</span></label>
                        <input type="date" name="strike_date" class="form-control" required max="<?php echo e(date('Y-m-d')); ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Linked Rule (Optional)</label>
                        <select name="strike_rule_id" class="form-select">
                            <option value="">-- Select Rule --</option>
                            <?php $__currentLoopData = $strikeRules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($rule->id); ?>"><?php echo e($rule->rule_type); ?> (<?php echo e($rule->strike_color); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Reason / Notes <span class="text-danger">*</span></label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Describe why this strike is being assigned..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Strike</button>
                </div>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/strikes.blade.php ENDPATH**/ ?>