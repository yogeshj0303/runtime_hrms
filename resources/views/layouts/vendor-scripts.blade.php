<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/js/plugins.js') }}"></script>
@yield('script')
@yield('script-bottom')
<!-- App js -->
<script src="{{ URL::asset('build/js/app.js') }}"></script>

<!-- Global Form Validation Error Engine (Shows error messages below respective inputs across all modules) -->
<script>
(function() {
    function findInput(container, fieldName) {
        if (!container) container = document;
        
        // 1. Direct name / id match
        let input = container.querySelector(`[name="${fieldName}"], [name="${fieldName}[]"], #${fieldName}`);
        if (input) return input;
        
        // 2. Dot notation to array brackets: options.pf_min -> options[pf_min]
        if (fieldName.includes('.')) {
            let parts = fieldName.split('.');
            let bracketName = parts[0] + parts.slice(1).map(p => `[${p}]`).join('');
            input = container.querySelector(`[name="${bracketName}"], [name="${bracketName}[]"]`);
            if (input) return input;
            
            // Array with wildcard e.g. items.0.name -> items[][name] or items[]
            let regexBracket = parts[0] + parts.slice(1).map(p => !isNaN(p) ? '[]' : `[${p}]`).join('');
            input = container.querySelector(`[name="${regexBracket}"]`);
            if (input) return input;

            // Try underscore id
            let idUnderscore = fieldName.replace(/\./g, '_');
            input = container.querySelector(`#${idUnderscore}`);
            if (input) return input;
        }
        
        // 3. Fallback suffix match
        input = container.querySelector(`[name$="[${fieldName}]"]`);
        if (input) return input;

        return null;
    }

    function showFieldError(inputEl, message) {
        if (!inputEl) return;
        inputEl.classList.add('is-invalid');
        
        let parent = inputEl.parentElement;
        let target = inputEl;
        
        // Adjust insertion position if input is inside an input-group, select2, or form-check
        if (inputEl.closest('.input-group')) {
            target = inputEl.closest('.input-group');
        } else if (inputEl.nextElementSibling && inputEl.nextElementSibling.classList.contains('select2-container')) {
            target = inputEl.nextElementSibling;
        } else if (inputEl.closest('.form-check')) {
            target = inputEl.closest('.form-check');
        }
        
        // Remove existing error message if present
        let existing = parent.querySelector('.invalid-feedback.dynamic-error') || 
                       (target.nextElementSibling && target.nextElementSibling.classList.contains('dynamic-error') ? target.nextElementSibling : null);
        if (existing) existing.remove();
        
        // Create new error feedback below input
        const errDiv = document.createElement('div');
        errDiv.className = 'invalid-feedback d-block text-danger fs-12 mt-1 dynamic-error';
        errDiv.innerHTML = `<i class="ri-error-warning-line me-1"></i> ${message}`;
        target.insertAdjacentElement('afterend', errDiv);
        
        // Auto-remove error when user interacts with field
        const clearHandler = function() {
            inputEl.classList.remove('is-invalid');
            if (errDiv && errDiv.parentNode) {
                errDiv.remove();
            }
            inputEl.removeEventListener('input', clearHandler);
            inputEl.removeEventListener('change', clearHandler);
        };
        inputEl.addEventListener('input', clearHandler);
        inputEl.addEventListener('change', clearHandler);
    }

    function clearContainerErrors(container) {
        if (!container) container = document;
        container.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        container.querySelectorAll('.invalid-feedback.dynamic-error').forEach(el => el.remove());
    }

    // 1. Handle Server-Side Validation Errors from Laravel Session
    @if(isset($errors) && $errors->any())
        document.addEventListener('DOMContentLoaded', function() {
            const serverErrors = @json($errors->toArray());
            let firstInvalid = null;
            
            Object.keys(serverErrors).forEach(function(field) {
                const msg = Array.isArray(serverErrors[field]) ? serverErrors[field][0] : serverErrors[field];
                const input = findInput(document, field);
                if (input) {
                    showFieldError(input, msg);
                    if (!firstInvalid) firstInvalid = input;
                }
            });
            
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
            }
        });
    @endif

    // 2. Handle AJAX Validation Errors (jQuery AJAX global handler)
    if (typeof window.jQuery !== 'undefined') {
        $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
            if (jqXHR.status === 422 && jqXHR.responseJSON && jqXHR.responseJSON.errors) {
                const errors = jqXHR.responseJSON.errors;
                let activeForm = null;
                
                // Try to find the active submitting form or open modal
                if (document.activeElement && document.activeElement.closest('form')) {
                    activeForm = document.activeElement.closest('form');
                } else {
                    activeForm = document.querySelector('.modal.show form') || document.querySelector('form:not([style*="display: none"])');
                }
                
                clearContainerErrors(activeForm || document);
                
                let firstInvalid = null;
                Object.keys(errors).forEach(function(field) {
                    const msg = Array.isArray(errors[field]) ? errors[field][0] : errors[field];
                    const input = findInput(activeForm || document, field);
                    if (input) {
                        showFieldError(input, msg);
                        if (!firstInvalid) firstInvalid = input;
                    }
                });
                
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    if (typeof firstInvalid.focus === 'function') firstInvalid.focus();
                }
            }
        });
    }

    // Export helpers globally if needed by custom scripts
    window.HRMSValidator = {
        showError: showFieldError,
        clearErrors: clearContainerErrors,
        findInput: findInput
    };
})();
</script>
