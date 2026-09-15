document.addEventListener('DOMContentLoaded', function () {
    const warrantyToggle = document.getElementById('warrantyToggle');

    if (warrantyToggle) {
        warrantyToggle.addEventListener('change', function () {
            console.log('Warranty Expired Only:', this.checked);
        });
    }
});