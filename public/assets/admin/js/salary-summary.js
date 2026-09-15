document.addEventListener('DOMContentLoaded', function () {

    const salaryMonth = document.getElementById('salaryMonth');
    const prevMonth = document.getElementById('prevMonth');
    const nextMonth = document.getElementById('nextMonth');
    const viewReport = document.getElementById('viewReport');

    function changeMonth(type) {

        let date = new Date(salaryMonth.value + '-01');

        if (type === 'prev') {
            date.setMonth(date.getMonth() - 1);
        } else {
            date.setMonth(date.getMonth() + 1);
        }

        let year = date.getFullYear();
        let month = String(date.getMonth() + 1).padStart(2, '0');

        salaryMonth.value = `${year}-${month}`;
    }

    prevMonth.addEventListener('click', function () {
        changeMonth('prev');
    });

    nextMonth.addEventListener('click', function () {
        changeMonth('next');
    });

    salaryMonth.addEventListener('click', function () {
        if (this.showPicker) {
            this.showPicker();
        }
    });

    viewReport.addEventListener('click', function () {
        alert('Selected Month : ' + salaryMonth.value);
    });

    document.querySelectorAll('.export-summary').forEach(function(btn){

        btn.addEventListener('click', function(){

            alert(
                'Export Summary : ' +
                this.dataset.section +
                ' | Month : ' +
                salaryMonth.value
            );

        });

    });

    document.querySelectorAll('.export-detail').forEach(function(btn){

        btn.addEventListener('click', function(){

            alert(
                'Export Detail : ' +
                this.dataset.section +
                ' | Month : ' +
                salaryMonth.value
            );

        });

    });

});