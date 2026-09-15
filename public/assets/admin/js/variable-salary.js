document.addEventListener('DOMContentLoaded', function () {

    const monthInput = document.getElementById('salaryMonth');

    document.getElementById('prevMonth').addEventListener('click', () => {
        let date = new Date(monthInput.value + "-01");
        date.setMonth(date.getMonth() - 1);

        monthInput.value =
            date.getFullYear() + "-" +
            String(date.getMonth() + 1).padStart(2, '0');
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        let date = new Date(monthInput.value + "-01");
        date.setMonth(date.getMonth() + 1);

        monthInput.value =
            date.getFullYear() + "-" +
            String(date.getMonth() + 1).padStart(2, '0');
    });

});