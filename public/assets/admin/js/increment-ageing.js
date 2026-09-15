document.addEventListener("DOMContentLoaded", function () {
    const viewBtn = document.querySelector(".view-btn");
    const exportBtn = document.querySelector(".export-btn");

    if(viewBtn){
        viewBtn.addEventListener("click", function(){
            console.log("Increment Ageing View clicked");
        });
    }

    if(exportBtn){
        exportBtn.addEventListener("click", function(){
            console.log("Increment Ageing Export clicked");
        });
    }
});