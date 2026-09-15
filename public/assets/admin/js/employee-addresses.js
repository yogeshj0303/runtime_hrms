document.addEventListener('DOMContentLoaded', function () {

    const viewBtn = document.getElementById('viewBtn');
    const excelBtn = document.getElementById('excelBtn');
    const pdfBtn = document.getElementById('pdfBtn');

    if(viewBtn){
        viewBtn.addEventListener('click', function(){
            console.log('Employee address view clicked');
        });
    }

    if(excelBtn){
        excelBtn.addEventListener('click', function(){
            console.log('Excel download clicked');
        });
    }

    if(pdfBtn){
        pdfBtn.addEventListener('click', function(){
            console.log('PDF download clicked');
        });
    }

});