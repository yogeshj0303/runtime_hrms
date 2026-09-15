<?php $__env->startSection('profile_title', 'Permissions'); ?>
<?php $__env->startSection('profile_description', 'Manage employee rights and options for Punches, Travel and Performance.'); ?>

<?php $__env->startSection('profile_actions'); ?>
<button type="submit" form="permissionsForm" class="btn-hrms-crimson">
    <i class="ri-save-line"></i> Save Permissions
</button>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('profile_content'); ?>
<form id="permissionsForm" action="<?php echo e(route('employee.profile.permissions.update', ['id' => $employee->id])); ?>" method="POST">
    <?php echo csrf_field(); ?>

    <?php
        $perm = $employee->permission;
    ?>

    <div class="row">
        <!-- Column 1 -->
        <div class="col-md-4 mb-4">
            <h6 class="fw-bold mb-3 fs-13">Attendance & Punches</h6>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="selfie_punch" name="selfie_punch" value="1" <?php echo e($perm && $perm->selfie_punch ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="selfie_punch">Selfie Punch</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="face_recognition" name="face_recognition" value="1" <?php echo e($perm && $perm->face_recognition ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="face_recognition">Selfie Face Recognition <i class="ri-information-line text-primary ms-1" title="Enable Face Recognition for selfie punches"></i></label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="selfie_at_all_locations" name="selfie_at_all_locations" value="1" <?php echo e($perm && $perm->selfie_at_all_locations ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="selfie_at_all_locations">Selfie at All Locations</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="remote_punch" name="remote_punch" value="1" <?php echo e($perm && $perm->remote_punch ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="remote_punch">Remote Punch</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="missed_punch" name="missed_punch" value="1" <?php echo e($perm && $perm->missed_punch ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="missed_punch">Missed Punch <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="web_chat_punch" name="web_chat_punch" value="1" <?php echo e($perm && $perm->web_chat_punch ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="web_chat_punch">Web/Chat Punch</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="time_relaxation" name="time_relaxation" value="1" <?php echo e($perm && $perm->time_relaxation ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="time_relaxation">Time Relaxation</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="scan_at_all_locations" name="scan_at_all_locations" value="1" <?php echo e($perm && $perm->scan_at_all_locations ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="scan_at_all_locations">Scan at all locations <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="ignore_time_strikes" name="ignore_time_strikes" value="1" <?php echo e($perm && $perm->ignore_time_strikes ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="ignore_time_strikes">Ignore Time Strikes <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
            
            <div class="form-check form-switch mb-4">
                <input class="form-check-input" type="checkbox" role="switch" id="auto_punch_in_out" name="auto_punch_in_out" value="1" <?php echo e($perm && $perm->auto_punch_in_out ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="auto_punch_in_out">Auto Punch In/Out <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
            
            <h6 class="fw-bold mb-3 fs-13">Request Limits</h6>
            
            <div class="mb-3">
                <label class="form-label fs-13 text-muted mb-1">Missed Punch Limit</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="number" name="missed_punch_limit" class="form-control form-control-sm text-center" style="width: 70px;" value="<?php echo e($perm->missed_punch_limit ?? 1); ?>">
                    <span class="fs-13 text-muted">per month</span>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fs-13 text-muted mb-1">Strike Exemption Limit</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="number" name="strike_exemption_limit" class="form-control form-control-sm text-center" style="width: 70px;" value="<?php echo e($perm->strike_exemption_limit ?? 0); ?>">
                    <span class="fs-13 text-muted">per month</span>
                </div>
            </div>
        </div>
        
        <!-- Column 2 -->
        <div class="col-md-4 mb-4">
            <h6 class="fw-bold mb-1 fs-13">Travel & Visit Tracking</h6>
            <p class="text-muted" style="font-size: 11px;">(Requires Travel Addon)</p>
            
            <div class="form-check form-switch mb-2 mt-2">
                <input class="form-check-input" type="checkbox" role="switch" id="visit_punch" name="visit_punch" value="1" <?php echo e($perm && $perm->visit_punch ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="visit_punch">Visit Punch</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="visit_punch_approval" name="visit_punch_approval" value="1" <?php echo e($perm && $perm->visit_punch_approval ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="visit_punch_approval">Visit Punch Approval <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="visit_punch_attendance" name="visit_punch_attendance" value="1" <?php echo e($perm && $perm->visit_punch_attendance ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="visit_punch_attendance">Visit Punch Attendance <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="live_travel" name="live_travel" value="1" <?php echo e($perm && $perm->live_travel ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="live_travel">Live Travel</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="live_travel_attendance" name="live_travel_attendance" value="1" <?php echo e($perm && $perm->live_travel_attendance ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="live_travel_attendance">Live Travel Attendance <i class="ri-information-line text-primary ms-1"></i></label>
            </div>
        </div>

        <!-- Column 3 -->
        <div class="col-md-4 mb-4">
            <h6 class="fw-bold mb-3 fs-13">Rewards and Recognition</h6>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="give_badges" name="give_badges" value="1" <?php echo e($perm && $perm->give_badges ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="give_badges">Give Badges</label>
            </div>
            
            <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" role="switch" id="give_rewards" name="give_rewards" value="1" <?php echo e($perm && $perm->give_rewards ? 'checked' : ''); ?>>
                <label class="form-check-label fs-13" for="give_rewards">Give Rewards</label>
            </div>
        </div>
    </div>
    
    <div class="mt-4 pt-3 border-top text-end">
        <button type="submit" class="btn-hrms-crimson"><i class="ri-save-line"></i> Save Permissions</button>
    </div>

</form>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/permissions.blade.php ENDPATH**/ ?>