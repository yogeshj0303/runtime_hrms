document.addEventListener('DOMContentLoaded', function () {
    const activeEmployeeToggle = document.getElementById('activeEmployeeToggle');

    if (activeEmployeeToggle) {
        activeEmployeeToggle.addEventListener('change', function () {
            console.log('Active Employees Only:', this.checked);
        });
    }
});