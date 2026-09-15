<?php echo $__env->yieldContent('css'); ?>
<!-- Layout config Js -->
<script src="<?php echo e(URL::asset('build/js/layout.js')); ?>"></script>
<!-- Bootstrap Css -->
<link href="<?php echo e(URL::asset('build/css/bootstrap.min.css')); ?>" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="<?php echo e(URL::asset('build/css/icons.min.css')); ?>" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="<?php echo e(URL::asset('build/css/app.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />
<!-- custom Css-->
<link href="<?php echo e(URL::asset('build/css/custom.min.css')); ?>" id="app-style" rel="stylesheet" type="text/css" />


<!-- PingHR Custom Theme Override -->
<style>
    :root, [data-bs-theme="light"], [data-bs-theme="dark"], [data-theme="default"], body {
        /* Primary Theme Color - PingHR Dark Navy */
        --vz-primary: #162d50;
        --vz-primary-rgb: 22, 45, 80;
        
        --bs-primary: #162d50;
        --bs-primary-rgb: 22, 45, 80;
        
        --vz-link-color: #162d50;
        --vz-link-hover-color: #0f1e36;
        
        --vz-primary-text-emphasis: #0e1d33;
        --vz-primary-bg-subtle: #d0d5dc;
        --vz-primary-border-subtle: #a1abc1;
    }

    /* Force primary buttons and backgrounds to adopt the new theme instantly */
    .btn-primary {
        --bs-btn-bg: #162d50;
        --bs-btn-border-color: #162d50;
        --bs-btn-hover-bg: #0f1e36;
        --bs-btn-hover-border-color: #0f1e36;
        --bs-btn-active-bg: #0f1e36;
        --bs-btn-active-border-color: #0f1e36;
    }
    
    .bg-primary, .badge.bg-primary {
        background-color: var(--vz-primary) !important;
    }
    
    .text-primary {
        color: var(--vz-primary) !important;
    }
    
    /* Ensure charts and other UI elements pick it up if they use classes */
    .border-primary {
        border-color: var(--vz-primary) !important;
    }
    
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        background-color: var(--vz-primary);
    }
    
    .progress-bar {
        background-color: var(--vz-primary);
    }
    
    /* Universal Validation Error Message Styling */
    .invalid-feedback.dynamic-error,
    .invalid-feedback {
        font-size: 12px !important;
        color: #dc3545 !important;
        margin-top: 4px !important;
        display: block !important;
        font-weight: 500 !important;
    }
    .form-control.is-invalid,
    .form-select.is-invalid,
    .salary-input-box.is-invalid,
    .form-check-input.is-invalid {
        border-color: #dc3545 !important;
    }
</style>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/layouts/head-css.blade.php ENDPATH**/ ?>