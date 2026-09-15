<?php $__env->startSection('profile_title', 'Work Profile'); ?>
<?php $__env->startSection('profile_description', "Specify employee's profile within organization."); ?>

<?php $__env->startSection('page_css'); ?>
<style>
    .work-profile-card {
        background: #ffffff;
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
        height: 100%;
    }

    .work-card-title {
        font-size: 17px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0;
    }

    .effective-select-box {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 5px 10px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .work-meta-row {
        margin-bottom: 18px;
    }

    .work-meta-label {
        font-size: 12px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 3px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .work-meta-value {
        font-size: 14px;
        font-weight: 700;
        color: #1e293b;
    }

    .manager-item-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        transition: all 0.15s ease;
    }

    .manager-item-box:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }

    .manager-item-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .manager-item-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background-color: #bde7f4;
        color: #0e3d50;
        font-size: 14px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .manager-item-avatar.avatar-undefined {
        background-color: #f1f5f9;
        color: #94a3b8;
        font-size: 16px;
    }

    .manager-item-name {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1px;
    }

    .manager-item-role {
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('raw_profile_content'); ?>
<?php
    $currentWork = $employee->workProfiles->where('is_current', true)->first();
    if(!$currentWork && $employee->workProfiles->count() > 0) {
        $currentWork = $employee->workProfiles->first();
    }
?>

<div class="row g-4 align-items-stretch">
    <!-- Left Card: Placement -->
    <div class="col-lg-6 col-md-12">
        <div class="work-profile-card">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="work-card-title">Placement</h5>
                <button type="button" class="btn-hrms-crimson" onclick="openAddModal()">
                    <i class="ri-add-line"></i> Add Revision
                </button>
            </div>

            <?php if($currentWork): ?>
                <!-- Effective From Row with Selector & Actions -->
                <div class="mb-4">
                    <div class="text-muted small fw-semibold mb-2">Effective From</div>
                    <div class="d-flex align-items-center gap-2">
                        <select class="form-select form-select-sm" style="max-width: 160px; font-weight: 600;" onchange="location.href='?id=<?php echo e($employee->id); ?>&revision_id=' + this.value">
                            <?php $__currentLoopData = $employee->workProfiles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($wp->id); ?>" <?php echo e($wp->id == $currentWork->id ? 'selected' : ''); ?>>
                                    <?php echo e(\Carbon\Carbon::parse($wp->effective_from)->format('M-Y')); ?> <?php echo e($wp->is_current ? '(Current)' : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <button type="button" class="btn btn-sm btn-secondary d-inline-flex align-items-center gap-1" onclick="openEditModal(<?php echo e($currentWork->id); ?>, '<?php echo e(\Carbon\Carbon::parse($currentWork->effective_from)->format('Y-m')); ?>', <?php echo e($currentWork->business_unit_id ?? 'null'); ?>, <?php echo e($currentWork->location_id ?? 'null'); ?>, <?php echo e($currentWork->cost_center_id ?? 'null'); ?>, <?php echo e($currentWork->department_id ?? 'null'); ?>, <?php echo e($currentWork->grade_id ?? 'null'); ?>, <?php echo e($currentWork->designation_id ?? 'null'); ?>, <?php echo e($currentWork->reporting_manager_id ?? 'null'); ?>, <?php echo e($currentWork->hr_manager_id ?? 'null'); ?>, <?php echo e($currentWork->indirect_manager_id ?? 'null'); ?>, <?php echo e($currentWork->is_promotion ? 'true' : 'false'); ?>)">
                            <i class="ri-pencil-line"></i> Edit
                        </button>
                        <form action="<?php echo e(route('employee.profile.work-profile.destroy', ['id' => $employee->id, 'work_profile_id' => $currentWork->id])); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'Are you sure you want to delete this revision?');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger d-inline-flex align-items-center">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Meta Rows -->
                <div class="work-meta-row">
                    <div class="work-meta-label">
                        <i class="ri-building-line text-muted"></i> Business Unit
                    </div>
                    <div class="work-meta-value text-uppercase">
                        <?php echo e(\App\Models\BusinessUnit::find($currentWork->business_unit_id)?->unit_name ?? \App\Models\BusinessUnit::find($currentWork->business_unit_id)?->business_name ?? 'SOMYA AUTOCAR PRIVATE LIMITED'); ?>

                    </div>
                </div>

                <div class="work-meta-row">
                    <div class="work-meta-label">
                        <i class="ri-map-pin-2-line" style="color: #e11d48;"></i> Location
                    </div>
                    <div class="work-meta-value text-uppercase" style="color: #1e293b;">
                        <?php echo e(\App\Models\Location::find($currentWork->location_id)?->name ?? 'INDORE WORKSHOP JAWA'); ?>

                    </div>
                </div>

                <div class="work-meta-row">
                    <div class="work-meta-label">
                        <i class="ri-bank-line text-warning"></i> Cost Center
                    </div>
                    <div class="work-meta-value">
                        <?php echo e(\App\Models\CostCenter::find($currentWork->cost_center_id)?->name ?? 'General Cost Center'); ?>

                    </div>
                </div>

                <div class="work-meta-row">
                    <div class="work-meta-label">
                        <i class="ri-building-4-line text-primary"></i> Department
                    </div>
                    <div class="work-meta-value">
                        <?php echo e(\App\Models\Department::find($currentWork->department_id)?->name ?? 'Service'); ?>

                    </div>
                </div>

                <div class="work-meta-row">
                    <div class="work-meta-label">
                        <i class="ri-user-star-line text-info"></i> Grade
                    </div>
                    <div class="work-meta-value">
                        <?php echo e(\App\Models\Grade::find($currentWork->grade_id)?->name ?? 'Executive'); ?>

                    </div>
                </div>

                <div class="work-meta-row mb-0">
                    <div class="work-meta-label">
                        <i class="ri-id-card-line text-success"></i> Designation
                    </div>
                    <div class="work-meta-value">
                        <?php echo e(\App\Models\Designation::find($currentWork->designation_id)?->name ?? 'CRE'); ?>

                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="ri-briefcase-line fs-1 text-muted"></i>
                    <p class="text-muted mt-2">No work profile record assigned yet.</p>
                    <button type="button" class="btn-hrms-crimson" onclick="openAddModal()">Assign Now</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Right Card: Managers -->
    <div class="col-lg-6 col-md-12">
        <div class="work-profile-card">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="work-card-title">Managers</h5>
            </div>

            <?php
                $repManager = $currentWork && $currentWork->reportingManager ? $currentWork->reportingManager : null;
                $hrManager = $currentWork && $currentWork->hrManager ? $currentWork->hrManager : null;
                $indirectManager = $currentWork && $currentWork->indirectManager ? $currentWork->indirectManager : null;
            ?>

            <!-- 1. Reporting Manager -->
            <div class="mb-4">
                <div class="manager-section-label">Reporting Manager</div>
                <div class="manager-item-box">
                    <div class="manager-item-left">
                        <?php if($repManager): ?>
                            <div class="manager-item-avatar">
                                <?php echo e(strtoupper(substr($repManager->first_name ?? 'N', 0, 1) . substr($repManager->last_name ?? 'B', 0, 1))); ?>

                            </div>
                            <div>
                                <div class="manager-item-name"><?php echo e(strtoupper($repManager->first_name . ' ' . $repManager->last_name)); ?></div>
                                <div class="manager-item-role"><?php echo e($repManager->designation ?? 'SERVICE MANAGER'); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="manager-item-avatar">NB</div>
                            <div>
                                <div class="manager-item-name">NEERAJ KUMAR BHATT</div>
                                <div class="manager-item-role">SERVICE MANAGER</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-circle-edit" title="Edit Manager" onclick="openAssignManagerModal('reporting')">
                            <i class="ri-pencil-line"></i>
                        </button>
                        <?php if($currentWork && $currentWork->reporting_manager_id): ?>
                            <form action="<?php echo e(route('employee.profile.work-profile.remove-manager', ['id' => $employee->id, 'work_profile_id' => $currentWork->id, 'type' => 'reporting'])); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'Are you sure you want to remove the reporting manager?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-circle-delete" title="Remove Manager">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn-circle-delete" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 2. HR Manager -->
            <div class="mb-4">
                <div class="manager-section-label">HR Manager</div>
                <div class="manager-item-box">
                    <div class="manager-item-left">
                        <?php if($hrManager): ?>
                            <div class="manager-item-avatar">
                                <?php echo e(strtoupper(substr($hrManager->first_name ?? 'N', 0, 1) . substr($hrManager->last_name ?? 'B', 0, 1))); ?>

                            </div>
                            <div>
                                <div class="manager-item-name"><?php echo e(strtoupper($hrManager->first_name . ' ' . $hrManager->last_name)); ?></div>
                                <div class="manager-item-role"><?php echo e($hrManager->designation ?? 'SERVICE MANAGER'); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="manager-item-avatar">NB</div>
                            <div>
                                <div class="manager-item-name">NEERAJ KUMAR BHATT</div>
                                <div class="manager-item-role">SERVICE MANAGER</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-circle-edit" title="Edit Manager" onclick="openAssignManagerModal('hr')">
                            <i class="ri-pencil-line"></i>
                        </button>
                        <?php if($currentWork && $currentWork->hr_manager_id): ?>
                            <form action="<?php echo e(route('employee.profile.work-profile.remove-manager', ['id' => $employee->id, 'work_profile_id' => $currentWork->id, 'type' => 'hr'])); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'Are you sure you want to remove the HR manager?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-circle-delete" title="Remove Manager">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn-circle-delete" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- 3. Indirect Manager -->
            <div>
                <div class="manager-section-label">Indirect Manager</div>
                <div class="manager-item-box">
                    <div class="manager-item-left">
                        <?php if($indirectManager): ?>
                            <div class="manager-item-avatar">
                                <?php echo e(strtoupper(substr($indirectManager->first_name ?? 'I', 0, 1) . substr($indirectManager->last_name ?? 'M', 0, 1))); ?>

                            </div>
                            <div>
                                <div class="manager-item-name"><?php echo e(strtoupper($indirectManager->first_name . ' ' . $indirectManager->last_name)); ?></div>
                                <div class="manager-item-role"><?php echo e($indirectManager->designation ?? 'Manager'); ?></div>
                            </div>
                        <?php else: ?>
                            <div class="manager-item-avatar avatar-undefined">
                                <i class="ri-user-3-line"></i>
                            </div>
                            <div>
                                <div class="manager-item-name text-muted">Not Defined</div>
                                <div class="manager-item-role text-muted">Not Defined</div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn-circle-edit" title="Edit Manager" onclick="openAssignManagerModal('indirect')">
                            <i class="ri-pencil-line"></i>
                        </button>
                        <?php if($currentWork && $currentWork->indirect_manager_id): ?>
                            <form action="<?php echo e(route('employee.profile.work-profile.remove-manager', ['id' => $employee->id, 'work_profile_id' => $currentWork->id, 'type' => 'indirect'])); ?>" method="POST" class="d-inline" onsubmit="return confirmDelete(event, this, 'Are you sure you want to remove the Indirect manager?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn-circle-delete" title="Remove Manager">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                        <?php else: ?>
                            <button type="button" class="btn-circle-delete" title="Delete">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add / Edit Work Profile -->
<div class="modal fade" id="workProfileModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="workProfileForm" method="POST" action="<?php echo e(route('employee.profile.work-profile.store', ['id' => $employee->id])); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Placement Revision</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Effective From (Month-Year) *</label>
                            <input type="month" class="form-control" name="effective_from" id="effective_from" required value="<?php echo e(date('Y-m')); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Business Unit *</label>
                            <select class="form-select" name="business_unit_id" id="business_unit_id" required>
                                <option value="">Select Unit</option>
                                <?php $__currentLoopData = $businessUnits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($bu->id); ?>"><?php echo e($bu->unit_name ?? $bu->business_name ?? 'Unit ' . $bu->id); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Location *</label>
                            <select class="form-select" name="location_id" id="location_id" required>
                                <option value="">Select Location</option>
                                <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $loc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($loc->id); ?>"><?php echo e($loc->name ?? 'Loc ' . $loc->id); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cost Center</label>
                            <select class="form-select" name="cost_center_id" id="cost_center_id">
                                <option value="">Select Cost Center</option>
                                <?php $__currentLoopData = $costCenters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cc->id); ?>"><?php echo e($cc->name ?? 'CC ' . $cc->id); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Department *</label>
                            <select class="form-select" name="department_id" id="department_id" required>
                                <option value="">Select Department</option>
                                <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($dept->id); ?>"><?php echo e($dept->name ?? 'Dept ' . $dept->id); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Grade</label>
                            <select class="form-select" name="grade_id" id="grade_id">
                                <option value="">Select Grade</option>
                                <?php $__currentLoopData = $grades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($gr->id); ?>"><?php echo e($gr->name ?? 'Grade ' . $gr->id); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Designation *</label>
                            <select class="form-select" name="designation_id" id="designation_id" required>
                                <option value="">Select Designation</option>
                                <?php $__currentLoopData = $designations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $des): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($des->id); ?>"><?php echo e($des->name ?? 'Desig ' . $des->id); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Reporting Manager</label>
                            <select class="form-select" name="reporting_manager_id" id="reporting_manager_id">
                                <option value="">Select Reporting Manager</option>
                                <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m->id); ?>"><?php echo e($m->first_name); ?> <?php echo e($m->last_name); ?> (<?php echo e($m->employee_code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">HR Manager</label>
                            <select class="form-select" name="hr_manager_id" id="hr_manager_id">
                                <option value="">Select HR Manager</option>
                                <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m->id); ?>"><?php echo e($m->first_name); ?> <?php echo e($m->last_name); ?> (<?php echo e($m->employee_code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Indirect Manager</label>
                            <select class="form-select" name="indirect_manager_id" id="indirect_manager_id">
                                <option value="">Select Indirect Manager</option>
                                <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m->id); ?>"><?php echo e($m->first_name); ?> <?php echo e($m->last_name); ?> (<?php echo e($m->employee_code); ?>)</option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_promotion" id="is_promotion" value="1">
                                <label class="form-check-label fw-semibold" for="is_promotion">Post promotion announcement on Company Wall 🎉</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-hrms-crimson">Save Placement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Assign Manager -->
<div class="modal fade" id="assignManagerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?php echo e($currentWork ? route('employee.profile.work-profile.update', ['id' => $employee->id, 'work_profile_id' => $currentWork->id]) : route('employee.profile.work-profile.store', ['id' => $employee->id])); ?>">
                <?php echo csrf_field(); ?>
                <?php if($currentWork): ?>
                    <input type="hidden" name="effective_from" value="<?php echo e(\Carbon\Carbon::parse($currentWork->effective_from)->format('Y-m')); ?>">
                    <input type="hidden" name="business_unit_id" value="<?php echo e($currentWork->business_unit_id); ?>">
                    <input type="hidden" name="location_id" value="<?php echo e($currentWork->location_id); ?>">
                    <input type="hidden" name="cost_center_id" value="<?php echo e($currentWork->cost_center_id); ?>">
                    <input type="hidden" name="department_id" value="<?php echo e($currentWork->department_id); ?>">
                    <input type="hidden" name="grade_id" value="<?php echo e($currentWork->grade_id); ?>">
                    <input type="hidden" name="designation_id" value="<?php echo e($currentWork->designation_id); ?>">
                    <input type="hidden" name="reporting_manager_id" id="modal_rep_id" value="<?php echo e($currentWork->reporting_manager_id); ?>">
                    <input type="hidden" name="hr_manager_id" id="modal_hr_id" value="<?php echo e($currentWork->hr_manager_id); ?>">
                    <input type="hidden" name="indirect_manager_id" id="modal_ind_id" value="<?php echo e($currentWork->indirect_manager_id); ?>">
                <?php endif; ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="assignManagerTitle">Assign Manager</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label" id="assignManagerLabel">Select Manager</label>
                        <select class="form-select" id="manager_select_box" onchange="updateManagerHiddenField(this.value)">
                            <option value="">-- None / Remove --</option>
                            <?php $__currentLoopData = $managers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($m->id); ?>"><?php echo e($m->first_name); ?> <?php echo e($m->last_name); ?> (<?php echo e($m->employee_code); ?>)</option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-hrms-crimson">Update Manager</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('page_script'); ?>
<script>
    let currentManagerType = 'reporting';

    function openAddModal() {
        document.getElementById('modalTitle').innerText = 'Add Placement Revision';
        document.getElementById('workProfileForm').action = "<?php echo e(route('employee.profile.work-profile.store', ['id' => $employee->id])); ?>";
        $('#workProfileModal').modal('show');
    }

    function openEditModal(id, effectiveFrom, buId, locId, ccId, deptId, grId, desId, repId, hrId, indId, isPromo) {
        document.getElementById('modalTitle').innerText = 'Edit Placement Revision';
        document.getElementById('workProfileForm').action = "/employee/profile/work-profile/" + id + "?id=<?php echo e($employee->id); ?>";
        
        document.getElementById('effective_from').value = effectiveFrom;
        document.getElementById('business_unit_id').value = buId || '';
        document.getElementById('location_id').value = locId || '';
        document.getElementById('cost_center_id').value = ccId || '';
        document.getElementById('department_id').value = deptId || '';
        document.getElementById('grade_id').value = grId || '';
        document.getElementById('designation_id').value = desId || '';
        document.getElementById('reporting_manager_id').value = repId || '';
        document.getElementById('hr_manager_id').value = hrId || '';
        document.getElementById('indirect_manager_id').value = indId || '';
        document.getElementById('is_promotion').checked = isPromo;

        $('#workProfileModal').modal('show');
    }

    function openAssignManagerModal(type) {
        currentManagerType = type;
        const select = document.getElementById('manager_select_box');
        if (type === 'reporting') {
            document.getElementById('assignManagerTitle').innerText = 'Assign Reporting Manager';
            document.getElementById('assignManagerLabel').innerText = 'Select Reporting Manager';
            select.value = document.getElementById('modal_rep_id') ? document.getElementById('modal_rep_id').value : '';
        } else if (type === 'hr') {
            document.getElementById('assignManagerTitle').innerText = 'Assign HR Manager';
            document.getElementById('assignManagerLabel').innerText = 'Select HR Manager';
            select.value = document.getElementById('modal_hr_id') ? document.getElementById('modal_hr_id').value : '';
        } else {
            document.getElementById('assignManagerTitle').innerText = 'Assign Indirect Manager';
            document.getElementById('assignManagerLabel').innerText = 'Select Indirect Manager';
            select.value = document.getElementById('modal_ind_id') ? document.getElementById('modal_ind_id').value : '';
        }
        $('#assignManagerModal').modal('show');
    }

    function updateManagerHiddenField(val) {
        if (currentManagerType === 'reporting') {
            if (document.getElementById('modal_rep_id')) document.getElementById('modal_rep_id').value = val;
        } else if (currentManagerType === 'hr') {
            if (document.getElementById('modal_hr_id')) document.getElementById('modal_hr_id').value = val;
        } else {
            if (document.getElementById('modal_ind_id')) document.getElementById('modal_ind_id').value = val;
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/work-profile.blade.php ENDPATH**/ ?>