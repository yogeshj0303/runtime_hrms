document.addEventListener("DOMContentLoaded", function () {

    const monthInput = document.getElementById("salaryMonth");
    const prevBtn = document.getElementById("prevMonth");
    const nextBtn = document.getElementById("nextMonth");

    function changeMonth(direction){

        let date = new Date(monthInput.value + "-01");

        if(direction === "prev"){
            date.setMonth(date.getMonth() - 1);
        }else{
            date.setMonth(date.getMonth() + 1);
        }

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");

        monthInput.value = `${year}-${month}`;
    }

    prevBtn.addEventListener("click", function(){
        changeMonth("prev");
    });

    nextBtn.addEventListener("click", function(){
        changeMonth("next");
    });

});