document.addEventListener('DOMContentLoaded', function () {

    const viewBtn = document.getElementById('viewBtn');
    const excelBtn = document.getElementById('excelBtn');
    const pdfBtn = document.getElementById('pdfBtn');

    viewBtn.addEventListener('click', function () {

        console.log('View Clicked');

        // Backend AJAX call yaha add karna

    });

    excelBtn.addEventListener('click', function () {

        console.log('Excel Export');

        // window.location.href = '/reports/employee/joinings/excel';

    });

    pdfBtn.addEventListener('click', function () {

        console.log('PDF Export');

        // window.location.href = '/reports/employee/joinings/pdf';

    });

});