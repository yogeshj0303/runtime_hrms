document.addEventListener("DOMContentLoaded", function () {

    const viewBtn = document.querySelector(".view-btn");

    if(viewBtn){
        viewBtn.addEventListener("click", function(){
            console.log("Employee Events View clicked");
        });
    }

});