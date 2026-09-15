document.addEventListener('DOMContentLoaded', function () {

    const viewBtn = document.getElementById('viewBtn');
    const excelBtn = document.getElementById('excelBtn');
    const pdfBtn = document.getElementById('pdfBtn');

    viewBtn?.addEventListener('click', function () {
        console.log('Employee Exits View clicked');
    });

    excelBtn?.addEventListener('click', function () {
        console.log('Employee Exits Excel clicked');
    });

    pdfBtn?.addEventListener('click', function () {
        console.log('Employee Exits PDF clicked');
    });

});