<?php $__env->startSection('profile_title', 'Summary'); ?>
<?php $__env->startSection('profile_description', "Summary of employee's record (read-only mode)."); ?>

<?php $__env->startSection('page_css'); ?>
<style>
    /* Summary Cards & Columns */
    .summary-left-card,
    .summary-right-card {
        background: #ffffff;
        border: 1px solid #e8ecf1;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
        height: 100%;
    }

    /* Avatar & Profile Meta */
    .summary-avatar {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        background-color: #bde7f4;
        color: #0e3d50;
        font-size: 30px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        letter-spacing: 0.5px;
    }

    .summary-avatar img {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        object-fit: cover;
    }

    .summary-emp-name {
        font-size: 19px;
        font-weight: 700;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 2px;
    }

    .summary-emp-code {
        font-size: 13.5px;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 8px;
    }

    .summary-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        font-weight: 500;
        color: #334155;
        margin-bottom: 5px;
    }

    .summary-meta-item i {
        font-size: 15px;
    }

    /* Section Divider with centered text */
    .summary-section-divider {
        position: relative;
        text-align: center;
        margin: 22px 0 18px 0;
    }

    .summary-section-divider::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: #eef2f6;
        z-index: 1;
    }

    .summary-section-divider span {
        position: relative;
        background: #ffffff;
        padding: 0 16px;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        z-index: 2;
    }

    /* Contact Row */
    .summary-contact-grid {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 4px 6px;
    }

    .summary-contact-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
    }

    .summary-contact-item .contact-icon {
        font-size: 15px;
        color: #64748b;
    }

    .summary-contact-email {
        color: #0284c7;
        font-weight: 500;
    }

    .summary-contact-phone {
        color: #d93850;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    .btn-copy-mini {
        background: none;
        border: none;
        padding: 0;
        color: #94a3b8;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        transition: color 0.15s;
    }

    .btn-copy-mini:hover {
        color: #d93850;
    }

    /* Manager Boxes */
    .manager-section-label {
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
    }

    .manager-box-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 9px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 14px;
        transition: all 0.15s ease;
    }

    .manager-box-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
    }

    .manager-box-card.box-undefined {
        background: #f8fafc;
    }

    .manager-avatar-mini {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #bde7f4;
        color: #0e3d50;
        font-size: 12.5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .manager-avatar-undefined {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background-color: #f1f5f9;
        color: #94a3b8;
        font-size: 15px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .manager-name-link {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .manager-name-link i {
        color: #d93850;
        font-size: 13px;
    }

    .manager-name-link:hover {
        color: #d93850;
    }

    .manager-code-subtitle {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
    }

    /* Right Column Sections */
    .summary-group-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .summary-group-title {
        font-size: 13.5px;
        font-weight: 700;
        color: #1e293b;
        white-space: nowrap;
        margin-bottom: 0;
    }

    .summary-group-line {
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .summary-field-label {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 4px;
    }

    .summary-field-value {
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .summary-field-value.val-muted {
        color: #334155;
        font-weight: 500;
    }

    .reportee-item-card {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 8px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .reportee-item-card:hover {
        background: #ffffff;
        border-color: #cbd5e1;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('raw_profile_content'); ?>
<?php
    $currentWork = $employee->workProfiles->where('is_current', true)->first();
    $designationName = $currentWork && $currentWork->designation ? $currentWork->designation->name : ($employee->designation ?? 'Not Assigned');
    $departmentName = $currentWork && $currentWork->department ? $currentWork->department->name : ($employee->department ?? 'Not Assigned');
    $locationName = $currentWork && $currentWork->location ? $currentWork->location->name : 'INDORE WORKSHOP JAWA';

    $repManager = $currentWork && $currentWork->reportingManager ? $currentWork->reportingManager : null;
    $hrManager = $currentWork && $currentWork->hrManager ? $currentWork->hrManager : null;
    $indirectManager = $currentWork && $currentWork->indirectManager ? $currentWork->indirectManager : null;

    $joiningDateFormatted = $employee->joining_date 
        ? $employee->joining_date->format('d-M-Y') 
        : ($employee->profile?->joining_date ? \Carbon\Carbon::parse($employee->profile->joining_date)->format('d-M-Y') : '25-Mar-2026');

    $confDateFormatted = $employee->confirmation_date 
        ? $employee->confirmation_date->format('d-M-Y') 
        : ($employee->profile?->confirmation_date ? \Carbon\Carbon::parse($employee->profile->confirmation_date)->format('d-M-Y') : '25-Jun-2026');

    $dobFormatted = $employee->dob 
        ? $employee->dob->format('d-M-Y') 
        : ($employee->profile?->dob ? \Carbon\Carbon::parse($employee->profile->dob)->format('d-M-Y') : '09-Dec-2003');

    $officialEmail = $employee->profile?->official_email ?? $employee->email ?? 'Not added';
    $phoneDisplay = $employee->profile?->office_phone ?? $employee->phone ?? '8817505485';
?>

<div class="row g-4 align-items-stretch">
    <!-- Left Column: Employee Profile Card -->
    <div class="col-lg-7 col-md-12">
        <div class="summary-left-card">
            <!-- Header Section with Avatar -->
            <div class="d-flex align-items-center gap-4 mb-4">
                <div class="summary-avatar">
                    <?php if($employee->profile && $employee->profile->profile_photo): ?>
                        <img src="<?php echo e(asset('storage/' . $employee->profile->profile_photo)); ?>" alt="<?php echo e($employee->first_name); ?>">
                    <?php else: ?>
                        <?php echo e(strtoupper(substr($employee->first_name ?? 'A', 0, 1) . substr($employee->last_name ?? 'Y', 0, 1))); ?>

                    <?php endif; ?>
                </div>
                <div>
                    <h5 class="summary-emp-name"><?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?></h5>
                    <div class="summary-emp-code"><?php echo e($employee->employee_code ?? '812'); ?></div>
                    
                    <div class="summary-meta-item">
                        <i class="ri-contacts-book-2-line" style="color: #0d9488;"></i>
                        <span><?php echo e($designationName); ?></span>
                    </div>
                    <div class="summary-meta-item">
                        <i class="ri-building-line" style="color: #7c3aed;"></i>
                        <span><?php echo e($departmentName); ?></span>
                    </div>
                    <div class="summary-meta-item">
                        <i class="ri-map-pin-user-line" style="color: #e11d48;"></i>
                        <span><?php echo e($locationName); ?></span>
                    </div>
                </div>
            </div>

            <!-- Divider: Contact Info -->
            <div class="summary-section-divider">
                <span>Contact Info</span>
            </div>

            <!-- Contact Info Grid -->
            <div class="summary-contact-grid mb-3">
                <div class="summary-contact-item">
                    <span class="contact-icon fw-bold">@</span>
                    <span class="<?php echo e($officialEmail !== 'Not added' ? 'summary-contact-email' : 'text-muted'); ?>"><?php echo e($officialEmail); ?></span>
                    <?php if($officialEmail !== 'Not added'): ?>
                        <button class="btn-copy-mini" title="Copy Email" onclick="copyToClipboard('<?php echo e($officialEmail); ?>', this)">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    <?php endif; ?>
                </div>
                <div class="summary-contact-item">
                    <i class="ri-smartphone-line contact-icon"></i>
                    <span class="summary-contact-phone"><?php echo e($phoneDisplay); ?></span>
                    <?php if($phoneDisplay !== 'Not added'): ?>
                        <button class="btn-copy-mini" title="Copy Phone" onclick="copyToClipboard('<?php echo e($phoneDisplay); ?>', this)">
                            <i class="ri-file-copy-line"></i>
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Divider: Managers -->
            <div class="summary-section-divider">
                <span>Managers</span>
            </div>

            <!-- Managers List -->
            <!-- 1. Reporting Manager -->
            <div class="mb-3">
                <div class="manager-section-label">Reporting Manager</div>
                <?php if($repManager): ?>
                    <?php
                        $repInitials = strtoupper(substr($repManager->first_name ?? 'N', 0, 1) . substr($repManager->last_name ?? 'B', 0, 1));
                    ?>
                    <div class="manager-box-card">
                        <div class="manager-avatar-mini"><?php echo e($repInitials); ?></div>
                        <div>
                            <div>
                                <a href="<?php echo e(route('employee.profile.summary', ['id' => $repManager->id])); ?>" class="manager-name-link">
                                    <?php echo e(strtoupper($repManager->first_name . ' ' . $repManager->last_name)); ?>

                                    <i class="ri-external-link-line"></i>
                                </a>
                            </div>
                            <div class="manager-code-subtitle"><?php echo e($repManager->employee_code ?? '804'); ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="manager-box-card">
                        <div class="manager-avatar-mini">NB</div>
                        <div>
                            <div>
                                <a href="javascript:void(0);" class="manager-name-link">
                                    NEERAJ KUMAR BHATT
                                    <i class="ri-external-link-line"></i>
                                </a>
                            </div>
                            <div class="manager-code-subtitle">804</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 2. HR Manager -->
            <div class="mb-3">
                <div class="manager-section-label">HR Manager</div>
                <?php if($hrManager): ?>
                    <?php
                        $hrInitials = strtoupper(substr($hrManager->first_name ?? 'N', 0, 1) . substr($hrManager->last_name ?? 'B', 0, 1));
                    ?>
                    <div class="manager-box-card">
                        <div class="manager-avatar-mini"><?php echo e($hrInitials); ?></div>
                        <div>
                            <div>
                                <a href="<?php echo e(route('employee.profile.summary', ['id' => $hrManager->id])); ?>" class="manager-name-link">
                                    <?php echo e(strtoupper($hrManager->first_name . ' ' . $hrManager->last_name)); ?>

                                    <i class="ri-external-link-line"></i>
                                </a>
                            </div>
                            <div class="manager-code-subtitle"><?php echo e($hrManager->employee_code ?? '804'); ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="manager-box-card">
                        <div class="manager-avatar-mini">NB</div>
                        <div>
                            <div>
                                <a href="javascript:void(0);" class="manager-name-link">
                                    NEERAJ KUMAR BHATT
                                    <i class="ri-external-link-line"></i>
                                </a>
                            </div>
                            <div class="manager-code-subtitle">804</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- 3. Indirect Manager -->
            <div>
                <div class="manager-section-label">Indirect Manager</div>
                <?php if($indirectManager): ?>
                    <?php
                        $indInitials = strtoupper(substr($indirectManager->first_name ?? 'I', 0, 1) . substr($indirectManager->last_name ?? 'M', 0, 1));
                    ?>
                    <div class="manager-box-card">
                        <div class="manager-avatar-mini"><?php echo e($indInitials); ?></div>
                        <div>
                            <div>
                                <a href="<?php echo e(route('employee.profile.summary', ['id' => $indirectManager->id])); ?>" class="manager-name-link">
                                    <?php echo e(strtoupper($indirectManager->first_name . ' ' . $indirectManager->last_name)); ?>

                                    <i class="ri-external-link-line"></i>
                                </a>
                            </div>
                            <div class="manager-code-subtitle"><?php echo e($indirectManager->employee_code); ?></div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="manager-box-card box-undefined">
                        <div class="manager-avatar-undefined">?</div>
                        <div>
                            <div class="text-muted fw-semibold" style="font-size: 13.5px;">Not Defined</div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column: Direct Reports, HR Record, Contact Details in White Card -->
    <div class="col-lg-5 col-md-12">
        <div class="summary-right-card">
            <!-- 1. Direct Reports -->
            <div class="mb-5">
                <div class="summary-group-header">
                    <h6 class="summary-group-title">Direct Reports</h6>
                    <div class="summary-group-line"></div>
                </div>
                <?php if(isset($directReports) && $directReports->count() > 0): ?>
                    <?php $__currentLoopData = $directReports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $report): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($report->employee): ?>
                            <a href="<?php echo e(route('employee.profile.summary', ['id' => $report->employee->id])); ?>" class="reportee-item-card">
                                <div class="manager-avatar-mini" style="width: 32px; height: 32px; font-size: 11px;">
                                    <?php echo e(strtoupper(substr($report->employee->first_name, 0, 1) . substr($report->employee->last_name ?? '', 0, 1))); ?>

                                </div>
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 13px;"><?php echo e($report->employee->first_name); ?> <?php echo e($report->employee->last_name); ?></div>
                                    <div class="text-muted" style="font-size: 11.5px;"><?php echo e($report->designation ? $report->designation->name : ($report->employee->employee_code ?? '')); ?></div>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="text-muted fst-italic py-1" style="font-size: 13.5px; color: #64748b;">No direct reports found</div>
                <?php endif; ?>
            </div>

            <!-- 2. HR Record -->
            <div class="mb-5">
                <div class="summary-group-header">
                    <h6 class="summary-group-title">HR Record</h6>
                    <div class="summary-group-line"></div>
                </div>
                <div class="row g-4">
                    <div class="col-6">
                        <div class="summary-field-label">Date of Joining</div>
                        <div class="summary-field-value">
                            <i class="ri-calendar-event-line" style="color: #8b5cf6; font-size: 16px;"></i>
                            <span><?php echo e($joiningDateFormatted); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="summary-field-label">Date of Conf.</div>
                        <div class="summary-field-value">
                            <i class="ri-checkbox-circle-fill" style="color: #10b981; font-size: 16px;"></i>
                            <span><?php echo e($confDateFormatted); ?></span>
                            <i class="ri-alert-fill" style="color: #ef4444; font-size: 15px;" title="Probation / Confirmation Status Alert"></i>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="summary-field-label">Date of Birth</div>
                        <div class="summary-field-value">
                            <span><?php echo e($dobFormatted); ?></span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="summary-field-label">Notice Period</div>
                        <div class="summary-field-value">
                            <span><?php echo e($employee->profile?->notice_period ?? 0); ?> day(s)</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Contact Details -->
            <div>
                <div class="summary-group-header">
                    <h6 class="summary-group-title">Contact Details</h6>
                    <div class="summary-group-line"></div>
                </div>
                <div class="row g-4">
                    <div class="col-6">
                        <div class="summary-field-label">Office Phone</div>
                        <div class="summary-field-value val-muted">
                            <?php echo e($employee->profile?->office_phone ?? 'Not Added'); ?>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="summary-field-label">Emergency Contact</div>
                        <div class="summary-field-value val-muted">
                            <?php echo e($employee->profile?->emergency_contact ?? 'Not Added'); ?>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="summary-field-label">Home Phone</div>
                        <div class="summary-field-value val-muted">
                            <?php echo e($employee->profile?->personal_phone ?? 'Not Added'); ?>

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="summary-field-label">Personal E-Mail</div>
                        <div class="summary-field-value val-muted">
                            <?php echo e($employee->profile?->personal_email ?? 'Not Added'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.employee.profile.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/summary.blade.php ENDPATH**/ ?>