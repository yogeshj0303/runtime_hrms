<?php $__env->startSection('profile_title', 'Family Members'); ?>
<?php $__env->startSection('profile_description', 'View or update employee\'s family member details.'); ?>

<?php $__env->startSection('profile_actions'); ?>
<button class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addMemberModal">
    <i class="ri-add-line"></i> Add Member
</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('profile_content'); ?>

<?php if($employee->familyMembers && $employee->familyMembers->count() > 0): ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-muted small">
                <tr>
                    <th>RELATION & NAME</th>
                    <th>CONTACT DETAILS</th>
                    <th>NOTES</th>
                    <th>DATE OF BIRTH</th>
                    <th class="text-end">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $employee->familyMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <div class="mb-1">
                            <span class="badge bg-light text-dark border"><?php echo e($member->relation); ?></span>
                            <?php if($member->is_dependent): ?>
                                <span class="badge bg-soft-info text-info ms-1">Dependent</span>
                            <?php endif; ?>
                        </div>
                        <div class="fw-bold text-dark text-uppercase fs-13"><?php echo e($member->name); ?></div>
                    </td>
                    <td>
                        <?php if($member->phone): ?>
                            <div class="text-muted small mb-1"><i class="ri-phone-line text-success me-1"></i> <?php echo e($member->phone); ?></div>
                        <?php endif; ?>
                        <?php if($member->email): ?>
                            <div class="text-muted small"><i class="ri-mail-line text-info me-1"></i> <?php echo e($member->email); ?></div>
                        <?php endif; ?>
                        <?php if(!$member->phone && !$member->email): ?>
                            <span class="text-muted">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-muted small"><?php echo e($member->notes ?? '-'); ?></td>
                    <td><?php echo e($member->dob ? \Carbon\Carbon::parse($member->dob)->format('d M Y') : '-'); ?></td>
                    <td class="text-end">
                        <form action="<?php echo e(route('employee.profile.family.destroy', ['id' => $employee->id, 'member_id' => $member->id])); ?>" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this family member?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn-circle-delete" title="Delete Family Member">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="text-center text-muted p-5">
        <i class="ri-parent-line" style="font-size: 54px; color: #cbd5e1;"></i>
        <h6 class="mt-3 fw-bold text-secondary">0 family members added</h6>
        <p class="mb-0 text-muted small">Add family members to keep records updated.</p>
    </div>
<?php endif; ?>

<!-- Add Member Modal -->
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold fs-15">Add Family Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo e(route('employee.profile.family.store', ['id' => $employee->id])); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body p-4">
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Relation <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <select name="relation" class="form-select fs-13" required>
                                <option value="">- Select Relation -</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Spouse">Spouse</option>
                                <option value="Son">Son</option>
                                <option value="Daughter">Daughter</option>
                                <option value="Brother">Brother</option>
                                <option value="Sister">Sister</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Name <span class="text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" name="name" class="form-control fs-13" placeholder="Full Name" required>
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Phone Number</label>
                        <div class="col-sm-8">
                            <input type="text" name="phone" class="form-control fs-13" placeholder="Optional">
                        </div>
                    </div>

                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Email Address</label>
                        <div class="col-sm-8">
                            <input type="email" name="email" class="form-control fs-13" placeholder="Optional">
                        </div>
                    </div>
                    
                    <div class="mb-3 row align-items-center">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Date of Birth</label>
                        <div class="col-sm-8">
                            <input type="date" name="dob" class="form-control fs-13">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label text-muted fs-13">Notes</label>
                        <div class="col-sm-8">
                            <textarea name="notes" class="form-control fs-13" rows="2" placeholder="Any additional notes..."></textarea>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-sm-8 offset-sm-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_dependent" name="is_dependent" value="1">
                                <label class="form-check-label text-muted fs-13" for="is_dependent">Dependent <i class="ri-information-line text-primary ms-1" title="Check if this family member is dependent on the employee"></i></label>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none fw-medium" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save Member</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/family.blade.php ENDPATH**/ ?>