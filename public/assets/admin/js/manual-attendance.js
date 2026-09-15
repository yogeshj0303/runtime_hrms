document.addEventListener("DOMContentLoaded", function () {
    const months = ["JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC"];
    let monthIndex = 4;
    let year = 2026;

    const monthInput = document.getElementById("monthInput");
    const prevMonth = document.getElementById("prevMonth");
    const nextMonth = document.getElementById("nextMonth");

    function updateMonth(){
        monthInput.value = months[monthIndex] + "-" + year;
    }

    if(prevMonth){
        prevMonth.addEventListener("click", function () {
            monthIndex--;

            if(monthIndex < 0){
                monthIndex = 11;
                year--;
            }

            updateMonth();
        });
    }

    if(nextMonth){
        nextMonth.addEventListener("click", function () {
            monthIndex++;

            if(monthIndex > 11){
                monthIndex = 0;
                year++;
            }

            updateMonth();
        });
    }

    document.querySelectorAll(".manual-table tbody tr").forEach(function(row){
        const inputs = row.querySelectorAll(".calc-input");
        const totalCell = row.querySelector(".total-text");

        function calculateTotal(){
            let total = 0;

            inputs.forEach(function(input){
                total += Number(input.value) || 0;
            });

            totalCell.textContent = total;
        }

        inputs.forEach(function(input){
            input.addEventListener("input", calculateTotal);
        });
    });
});