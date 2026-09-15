<?php $__env->startSection('profile_title', 'Identity'); ?>
<?php $__env->startSection('profile_description', 'Manage Identity records and official government verifications.'); ?>

<?php $__env->startSection('profile_content'); ?>
<?php
    $identity = $employee->identity;
?>
<div class="row">
    <!-- Main Content (Left) -->
    <div class="col-lg-8 pe-lg-4 border-end">
        
        <!-- Bank Details -->
        <div class="mb-4 pb-3 border-bottom">
            <h6 class="fw-bold fs-14 text-dark mb-3">Bank Details</h6>
            <form action="<?php echo e(route('employee.profile.identity.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo e($employee->id); ?>">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">Bank Name</label></div>
                    <div class="col-md-8">
                        <input type="text" name="bank_name" class="form-control form-control-sm" value="<?php echo e($identity->bank_name ?? ''); ?>" placeholder="e.g. HDFC Bank">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">IFSC Code</label></div>
                    <div class="col-md-8">
                        <input type="text" name="ifsc" class="form-control form-control-sm text-uppercase" value="<?php echo e($identity->ifsc ?? ''); ?>" placeholder="HDFC0001234">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">Account Number</label></div>
                    <div class="col-md-8">
                        <input type="text" name="account_number" class="form-control form-control-sm" value="<?php echo e($identity->account_number ?? ''); ?>" placeholder="01234567890">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save</button>
                    <?php if(isset($identity) && $identity->is_bank_verified): ?>
                        <span class="badge bg-success-subtle text-success border border-success d-inline-flex align-items-center px-2 py-1"><i class="ri-checkbox-circle-line me-1"></i> Verified</span>
                    <?php else: ?>
                        <button type="submit" name="action" value="verify_bank" class="btn btn-sm btn-outline-success px-3"><i class="ri-shield-check-line me-1"></i> Verify Now</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Aadhar Number -->
        <div class="mb-4 pb-3 border-bottom">
            <h6 class="fw-bold fs-14 text-dark mb-3">Aadhaar Number</h6>
            <form action="<?php echo e(route('employee.profile.identity.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo e($employee->id); ?>">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">Aadhaar (12-digit)</label></div>
                    <div class="col-md-8">
                        <input type="text" name="aadhaar_number" class="form-control form-control-sm" maxlength="12" value="<?php echo e($identity->aadhaar_number ?? ''); ?>" placeholder="1234 5678 9012">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save</button>
                    <?php if(isset($identity) && $identity->is_aadhaar_verified): ?>
                        <span class="badge bg-success-subtle text-success border border-success d-inline-flex align-items-center px-2 py-1"><i class="ri-checkbox-circle-line me-1"></i> Verified</span>
                    <?php else: ?>
                        <button type="submit" name="action" value="verify_aadhaar" class="btn btn-sm btn-outline-success px-3"><i class="ri-shield-check-line me-1"></i> Verify Now</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Income Tax PAN -->
        <div class="mb-4 pb-3 border-bottom">
            <h6 class="fw-bold fs-14 text-dark mb-3">Income Tax PAN</h6>
            <form action="<?php echo e(route('employee.profile.identity.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo e($employee->id); ?>">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">PAN (10-digit)</label></div>
                    <div class="col-md-8">
                        <input type="text" name="pan_number" class="form-control form-control-sm text-uppercase" maxlength="10" value="<?php echo e($identity->pan_number ?? ''); ?>" placeholder="ABCDE1234F">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save</button>
                    <?php if(isset($identity) && $identity->is_pan_verified): ?>
                        <span class="badge bg-success-subtle text-success border border-success d-inline-flex align-items-center px-2 py-1"><i class="ri-checkbox-circle-line me-1"></i> Verified</span>
                    <?php else: ?>
                        <button type="submit" name="action" value="verify_pan" class="btn btn-sm btn-outline-success px-3"><i class="ri-shield-check-line me-1"></i> Verify Now</button>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- General Information -->
        <div class="mb-2">
            <h6 class="fw-bold fs-14 text-dark mb-3">General Information</h6>
            <form action="<?php echo e(route('employee.profile.identity.update')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo e($employee->id); ?>">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">Passport #</label></div>
                    <div class="col-md-8">
                        <input type="text" name="passport" class="form-control form-control-sm" value="<?php echo e($identity->passport ?? ''); ?>">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">Driving License #</label></div>
                    <div class="col-md-8">
                        <input type="text" name="driving_license" class="form-control form-control-sm" value="<?php echo e($identity->driving_license ?? ''); ?>">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">Blood Group</label></div>
                    <div class="col-md-8">
                        <select name="blood_group" class="form-select form-select-sm">
                            <option value="">- Select -</option>
                            <?php $__currentLoopData = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($bg); ?>" <?php echo e(isset($identity) && $identity->blood_group == $bg ? 'selected' : ''); ?>><?php echo e($bg); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">PF UAN</label></div>
                    <div class="col-md-8">
                        <input type="text" name="pf_uan" class="form-control form-control-sm" value="<?php echo e($identity->pf_uan ?? ''); ?>">
                    </div>
                </div>
                <div class="row mb-3 align-items-center">
                    <div class="col-md-4"><label class="mb-0 fs-13 text-muted">ESI IP Number</label></div>
                    <div class="col-md-8">
                        <input type="text" name="esi_number" class="form-control form-control-sm" value="<?php echo e($identity->esi_number ?? ''); ?>">
                    </div>
                </div>
                <div class="d-flex gap-2 mt-3">
                    <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save Information</button>
                </div>
            </form>
        </div>
        
    </div>
    
    <!-- Sidebar (Right) -->
    <div class="col-lg-4 ps-lg-4 mt-4 mt-lg-0">
        <div class="p-3 bg-light rounded border">
            <h6 class="fw-bold fs-14 mb-2 text-dark">KYC Status</h6>
            <p class="text-muted small mb-3">If you have completed KYC of the employee as per your company policy, mark the KYC done below.</p>
            <form action="<?php echo e(route('employee.profile.identity.update')); ?>" method="POST" id="kycForm">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" value="<?php echo e($employee->id); ?>">
                <input type="hidden" name="is_kyc_done_submit" value="1">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" name="is_kyc_done" id="is_kyc_done" value="1" <?php echo e(isset($identity) && $identity->is_kyc_done ? 'checked' : ''); ?> onchange="document.getElementById('kycForm').submit();">
                    <label class="form-check-label fs-13 fw-semibold" for="is_kyc_done">KYC Completed</label>
                </div>
            </form>
            <?php if(isset($identity) && $identity->is_kyc_done): ?>
                <div class="text-success small fw-bold"><i class="ri-checkbox-circle-fill me-1"></i> Status: KYC Verified</div>
            <?php else: ?>
                <div class="text-warning small fw-bold"><i class="ri-time-line me-1"></i> Status: KYC Pending</div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/identity.blade.php ENDPATH**/ ?>