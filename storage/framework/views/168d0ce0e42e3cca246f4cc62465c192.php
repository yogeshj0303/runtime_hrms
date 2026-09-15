<?php $__env->startSection('title'); ?>
    Employee Profile - <?php echo $__env->yieldContent('profile_title', 'Summary'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('css'); ?>
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    :root {
        --hrms-rose: #d93850;
        --hrms-rose-hover: #c52b42;
        --hrms-rose-light: #fdebee;
        --hrms-rose-border: #fecdd3;
        --hrms-plum: #581c3f;
        --hrms-slate: #1e293b;
        --hrms-muted: #64748b;
        --hrms-bg-card: #ffffff;
        --hrms-border: #e8ecf1;
    }

    body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Top Navigation / Breadcrumb Row */
    .emp-top-nav-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
        padding-top: 4px;
    }

    .emp-breadcrumb-area {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13.5px;
        font-weight: 500;
    }

    .emp-breadcrumb-back {
        color: var(--hrms-rose);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-weight: 600;
        transition: color 0.15s;
    }

    .emp-breadcrumb-back:hover {
        color: var(--hrms-rose-hover);
        text-decoration: underline;
    }

    .emp-breadcrumb-divider {
        color: #cbd5e1;
        font-weight: 400;
    }

    .emp-breadcrumb-name {
        color: var(--hrms-slate);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .emp-breadcrumb-current {
        color: var(--hrms-muted);
        font-weight: 500;
    }

    .emp-top-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-emp-nav {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-emp-nav:hover:not(.disabled) {
        background: #f1f5f9;
        color: var(--hrms-rose);
        border-color: #cbd5e1;
    }

    .btn-emp-nav.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .btn-location-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        font-size: 12.5px;
        font-weight: 500;
        color: #334155;
        text-decoration: none;
    }

    .btn-help-video {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #5d6778;
        color: #ffffff !important;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.15s ease;
    }

    .btn-help-video:hover {
        background: #475263;
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-help-read {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #00b894;
        color: #ffffff !important;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        transition: all 0.15s ease;
    }

    .btn-help-read:hover {
        background: #00a383;
        transform: translateY(-1px);
        color: #fff;
    }

    /* Left Sidebar Navigation */
    .profile-sidebar-wrapper {
        position: sticky;
        top: 80px;
    }

    .profile-header-info {
        padding: 4px 6px 14px 6px;
    }

    .profile-header-name {
        font-size: 15px;
        font-weight: 700;
        color: var(--hrms-slate);
        letter-spacing: 0.3px;
        margin-bottom: 2px;
    }

    .profile-header-code {
        font-size: 13px;
        font-weight: 500;
        color: var(--hrms-muted);
    }

    .profile-sidebar-card {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid var(--hrms-border);
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
        padding: 6px;
    }

    .profile-sidebar-nav .nav-link {
        color: #c93b51;
        padding: 8px 12px;
        border-radius: 6px;
        font-weight: 500;
        font-size: 13px;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }

    .profile-sidebar-nav .nav-link:hover {
        background: #fff5f6;
        color: var(--hrms-rose);
    }

    .profile-sidebar-nav .nav-link.active {
        background: var(--hrms-rose-light);
        color: var(--hrms-rose);
        font-weight: 700;
        border: 1px solid var(--hrms-rose-border);
    }

    .profile-sidebar-nav .nav-link.nav-deactivate {
        margin-top: 10px;
        background: #fff1f2;
        color: #e11d48;
        font-weight: 600;
        border: 1px solid #ffe4e6;
    }

    .profile-sidebar-nav .nav-link.nav-deactivate:hover {
        background: #ffe4e6;
        color: #be123c;
    }

    .profile-sidebar-nav .nav-link.nav-deactivate.active {
        background: #fecdd3;
        color: #9f1239;
        border-color: #fda4af;
    }

    /* Content Area */
    .profile-main-title {
        color: var(--hrms-plum);
        font-size: 19px;
        font-weight: 700;
        margin-bottom: 2px;
        font-family: 'Outfit', 'Plus Jakarta Sans', sans-serif;
    }

    .profile-main-subtitle {
        color: #64748b;
        font-size: 13px;
        margin-bottom: 0;
    }

    .profile-card-container {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--hrms-border);
        box-shadow: 0 1px 4px rgba(0,0,0,0.02);
        padding: 24px;
    }

    /* Common Buttons & Actions matching reference */
    .btn-hrms-crimson {
        background: #b83a4b;
        color: #ffffff !important;
        border-radius: 6px;
        padding: 6px 14px;
        font-size: 12.5px;
        font-weight: 600;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .btn-hrms-crimson:hover {
        background: #a12f3e;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(184, 58, 75, 0.25);
    }

    .btn-circle-edit {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #b83a4b;
        color: #ffffff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-circle-edit:hover {
        background: #9d2e3d;
        transform: scale(1.08);
    }

    .btn-circle-delete {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #e04f64;
        color: #ffffff !important;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-circle-delete:hover {
        background: #c93b51;
        transform: scale(1.08);
    }

    /* Top-Right Corner Toast */
    .hrms-toast-container {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 999999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }

    .hrms-toast {
        pointer-events: auto;
        min-width: 300px;
        max-width: 420px;
        background: #ffffff;
        border-radius: 10px;
        padding: 14px 18px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12), 0 2px 6px rgba(0,0,0,0.06);
        border-left: 4px solid #10b981;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        animation: slideInRight 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        transition: opacity 0.3s, transform 0.3s;
    }

    .hrms-toast.toast-error {
        border-left-color: #ef4444;
    }

    .hrms-toast-content {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 13.5px;
        font-weight: 600;
        color: #1e293b;
    }

    .hrms-toast-icon {
        font-size: 20px;
        color: #10b981;
        flex-shrink: 0;
    }

    .hrms-toast.toast-error .hrms-toast-icon {
        color: #ef4444;
    }

    .hrms-toast-close {
        background: none;
        border: none;
        color: #94a3b8;
        font-size: 16px;
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
    }

    .hrms-toast-close:hover {
        color: #475569;
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
</style>
<?php echo $__env->yieldContent('page_css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $currentWork = $employee->workProfiles->where('is_current', true)->first();
    $currentLocationName = $currentWork && $currentWork->location ? $currentWork->location->name : null;
?>

<!-- Top Breadcrumbs & Action Bar -->
<div class="emp-top-nav-bar">
    <div class="emp-breadcrumb-area">
        <a href="<?php echo e(route('business.employee')); ?>" class="emp-breadcrumb-back">
            <i class="ri-arrow-left-s-line" style="font-size: 16px;"></i> All Employees
        </a>
        <span class="emp-breadcrumb-divider">/</span>
        <span class="emp-breadcrumb-name"><?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?></span>
        <span class="emp-breadcrumb-divider">/</span>
        <span class="emp-breadcrumb-current"><?php echo $__env->yieldContent('profile_title', 'Summary'); ?></span>
    </div>

    <div class="emp-top-actions">
        <!-- Prev/Next navigation -->
        <?php if(isset($prevEmployee) && $prevEmployee): ?>
            <a href="<?php echo e(route('employee.profile.summary', ['id' => $prevEmployee->id])); ?>" class="btn-emp-nav" title="Previous: <?php echo e($prevEmployee->first_name); ?> <?php echo e($prevEmployee->last_name); ?>">
                <i class="ri-skip-back-line"></i>
            </a>
        <?php else: ?>
            <span class="btn-emp-nav disabled"><i class="ri-skip-back-line"></i></span>
        <?php endif; ?>

        <?php if(isset($nextEmployee) && $nextEmployee): ?>
            <a href="<?php echo e(route('employee.profile.summary', ['id' => $nextEmployee->id])); ?>" class="btn-emp-nav" title="Next: <?php echo e($nextEmployee->first_name); ?> <?php echo e($nextEmployee->last_name); ?>">
                <i class="ri-skip-forward-line"></i>
            </a>
        <?php else: ?>
            <span class="btn-emp-nav disabled"><i class="ri-skip-forward-line"></i></span>
        <?php endif; ?>

        <!-- Location Badge -->
        <div class="btn-location-pill">
            <i class="ri-menu-line" style="color: #64748b;"></i>
            <span><?php echo e($currentLocationName ? $currentLocationName : 'All Locations'); ?></span>
            <i class="ri-close-circle-line" style="color: var(--hrms-rose); cursor: pointer;" title="Clear filter"></i>
        </div>

        <!-- Video Help Button -->
        <a href="javascript:void(0);" class="btn-help-video" onclick="window.open('https://youtube.com', '_blank')">
            <i class="ri-youtube-fill" style="color: #ff4757; font-size: 15px;"></i> Video Help
        </a>

        <!-- Read Help Button -->
        <a href="javascript:void(0);" class="btn-help-read">
            <i class="ri-question-line" style="font-size: 14px;"></i> Read Help
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Left Profile Sidebar -->
    <div class="col-lg-3 col-md-4">
        <div class="profile-sidebar-wrapper">
            <div class="profile-header-info">
                <div class="profile-header-name"><?php echo e(strtoupper($employee->first_name . ' ' . $employee->last_name)); ?></div>
                <div class="profile-header-code"><?php echo e($employee->employee_code ?? 'EMP001'); ?></div>
            </div>

            <div class="profile-sidebar-card">
                <nav class="nav flex-column profile-sidebar-nav">
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.summary') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.summary', ['id' => $employee->id])); ?>">Summary</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.basic') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.basic', ['id' => $employee->id])); ?>">Basic Info</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.work-profile') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.work-profile', ['id' => $employee->id])); ?>">Work Profile</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.policies') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.policies', ['id' => $employee->id])); ?>">Policies</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.salary') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.salary', ['id' => $employee->id])); ?>">Salary</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.identity') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.identity', ['id' => $employee->id])); ?>">Identity</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.bg-check') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.bg-check', ['id' => $employee->id])); ?>">BG Check</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.address') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.address', ['id' => $employee->id])); ?>">Addresses</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.documents') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.documents', ['id' => $employee->id])); ?>">Documents</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.assets') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.assets', ['id' => $employee->id])); ?>">Assets</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.family') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.family', ['id' => $employee->id])); ?>">Family Members</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.permissions') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.permissions', ['id' => $employee->id])); ?>">Permissions</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.login-access') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.login-access', ['id' => $employee->id])); ?>">Login & Access</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.additional-info') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.additional-info', ['id' => $employee->id])); ?>">Additional Info</a>
                    <a class="nav-link <?php echo e(request()->routeIs('employee.profile.activity-logs') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.activity-logs', ['id' => $employee->id])); ?>">Activity Logs</a>
                    <a class="nav-link nav-deactivate <?php echo e(request()->routeIs('employee.profile.deactivate') ? 'active' : ''); ?>" href="<?php echo e(route('employee.profile.deactivate', ['id' => $employee->id])); ?>">Deactivate/Delete</a>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- Main Content Area -->
    <div class="col-lg-9 col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h4 class="profile-main-title"><?php echo $__env->yieldContent('profile_title'); ?></h4>
                <p class="profile-main-subtitle"><?php echo $__env->yieldContent('profile_description', "Summary of employee's record (read-only mode)."); ?></p>
            </div>
            <div>
                <?php echo $__env->yieldContent('profile_actions'); ?>
            </div>
        </div>
        
        <?php if (! empty(trim($__env->yieldContent('raw_profile_content')))): ?>
            <?php echo $__env->yieldContent('raw_profile_content'); ?>
        <?php else: ?>
            <div class="profile-card-container">
                <?php echo $__env->yieldContent('profile_content'); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Top-Right Floating Toast Alerts -->
<div class="hrms-toast-container" id="hrmsToastContainer">
    <?php if(session('success')): ?>
        <div class="hrms-toast toast-success" id="successToast">
            <div class="hrms-toast-content">
                <i class="ri-checkbox-circle-fill hrms-toast-icon"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
            <button type="button" class="hrms-toast-close" onclick="this.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="hrms-toast toast-error" id="errorToast">
            <div class="hrms-toast-content">
                <i class="ri-error-warning-fill hrms-toast-icon"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
            <button type="button" class="hrms-toast-close" onclick="this.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    <?php endif; ?>
    <?php if(isset($errors) && $errors->any()): ?>
        <div class="hrms-toast toast-error" id="validationToast">
            <div class="hrms-toast-content">
                <i class="ri-error-warning-fill hrms-toast-icon"></i>
                <span><?php echo e($errors->first()); ?></span>
            </div>
            <button type="button" class="hrms-toast-close" onclick="this.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        </div>
    <?php endif; ?>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Quick copy to clipboard helper
    function copyToClipboard(text, element) {
        if (!text || text === 'Not added' || text === 'Not Defined') return;
        navigator.clipboard.writeText(text).then(function() {
            const originalTitle = element.getAttribute('title') || '';
            element.setAttribute('title', 'Copied!');
            const icon = element.querySelector('i');
            if (icon) {
                const origClass = icon.className;
                icon.className = 'ri-check-line text-success';
                setTimeout(() => {
                    icon.className = origClass;
                    element.setAttribute('title', originalTitle);
                }, 1500);
            }
        });
    }

    // Dynamic toast notification trigger
    function showToast(message, type = 'success') {
        const container = document.getElementById('hrmsToastContainer');
        if (!container) return;
        
        const toast = document.createElement('div');
        toast.className = `hrms-toast ${type === 'error' ? 'toast-error' : 'toast-success'}`;
        const iconClass = type === 'error' ? 'ri-error-warning-fill' : 'ri-checkbox-circle-fill';
        
        toast.innerHTML = `
            <div class="hrms-toast-content">
                <i class="${iconClass} hrms-toast-icon"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="hrms-toast-close" onclick="this.parentElement.remove()">
                <i class="ri-close-line"></i>
            </button>
        `;
        
        container.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(50px)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    }

    // Auto-dismiss session toasts after 4 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.hrms-toast');
        toasts.forEach(toast => {
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(50px)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        });
    });
</script>
<?php echo $__env->yieldContent('page_script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/admin/employee/profile/layout.blade.php ENDPATH**/ ?>