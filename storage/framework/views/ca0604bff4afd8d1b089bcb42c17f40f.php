<?php $__env->startSection('profile_title', 'Additional Info'); ?>
<?php $__env->startSection('profile_description', 'Manage additional employee details, notes, and custom fields.'); ?>

<?php $__env->startSection('profile_content'); ?>
<?php
    $customFields = $additionalInfo && is_array($additionalInfo->custom_fields) 
        ? $additionalInfo->custom_fields 
        : ($additionalInfo && is_string($additionalInfo->custom_fields) ? json_decode($additionalInfo->custom_fields, true) : []);
?>

<form action="<?php echo e(route('employee.profile.additional-info.update', ['id' => $employee->id])); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <div class="row g-4">
        <!-- Left: General Notes & Extra Info -->
        <div class="col-lg-6 col-md-12">
            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Internal HR Notes</label>
                <textarea class="form-control" name="notes" rows="6" placeholder="Add confidential notes or internal observations about this employee..."><?php echo e($additionalInfo->notes ?? ''); ?></textarea>
                <div class="form-text">These notes are visible only to HR Managers and Administrators.</div>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold text-dark">Extra Information / Background</label>
                <textarea class="form-control" name="extra_information" rows="5" placeholder="Special skills, hobbies, medical considerations, or other miscellaneous data..."><?php echo e($additionalInfo->extra_information ?? ''); ?></textarea>
            </div>
        </div>

        <!-- Right: Custom Key-Value Fields -->
        <div class="col-lg-6 col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <label class="form-label fw-bold text-dark mb-0">Custom Attributes</label>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addCustomField()">
                    <i class="ri-add-line"></i> Add Field
                </button>
            </div>

            <div id="customFieldsContainer">
                <?php if(!empty($customFields)): ?>
                    <?php $__currentLoopData = $customFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="row g-2 mb-2 custom-field-row align-items-center">
                            <div class="col-5">
                                <input type="text" class="form-control form-control-sm" name="custom_keys[]" value="<?php echo e($key); ?>" placeholder="Field Name (e.g. Uniform Size)">
                            </div>
                            <div class="col-6">
                                <input type="text" class="form-control form-control-sm" name="custom_values[]" value="<?php echo e($val); ?>" placeholder="Value (e.g. XL)">
                            </div>
                            <div class="col-1 text-end">
                                <button type="button" class="btn btn-sm text-danger p-0" onclick="this.closest('.custom-field-row').remove()">
                                    <i class="ri-delete-bin-line fs-16"></i>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-muted small py-3" id="noCustomFieldsMsg">No custom attributes added. Click "Add Field" to add custom parameters.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 pt-3 border-top">
            <button type="submit" class="btn-hrms-crimson">
                <i class="ri-save-line"></i> Save Additional Info
            </button>
        </div>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_script'); ?>
<script>
    function addCustomField() {
        const noMsg = document.getElementById('noCustomFieldsMsg');
        if (noMsg) noMsg.style.display = 'none';

        const container = document.getElementById('customFieldsContainer');
        const div = document.createElement('div');
        div.className = 'row g-2 mb-2 custom-field-row align-items-center';
        div.innerHTML = `
            <div class="col-5">
                <input type="text" class="form-control form-control-sm" name="custom_keys[]" placeholder="Field Name (e.g. T-Shirt Size)">
            </div>
            <div class="col-6">
                <input type="text" class="form-control form-control-sm" name="custom_values[]" placeholder="Value (e.g. L)">
            </div>
            <div class="col-1 text-end">
                <button type="button" class="btn btn-sm text-danger p-0" onclick="this.closest('.custom-field-row').remove()">
                    <i class="ri-delete-bin-line fs-16"></i>
                </button>
            </div>
        `;
        container.appendChild(div);
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/additional-info.blade.php ENDPATH**/ ?>