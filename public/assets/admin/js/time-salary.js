document.addEventListener('DOMContentLoaded', function () {
    const monthInput = document.getElementById('salaryMonth');
    const prevBtn = document.getElementById('prevMonth');
    const nextBtn = document.getElementById('nextMonth');

    prevBtn.addEventListener('click', function () {
        let date = new Date(monthInput.value + '-01');
        date.setMonth(date.getMonth() - 1);
        monthInput.value = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
    });

    nextBtn.addEventListener('click', function () {
        let date = new Date(monthInput.value + '-01');
        date.setMonth(date.getMonth() + 1);
        monthInput.value = date.getFullYear() + '-' + String(date.getMonth() + 1).padStart(2, '0');
    });
});