document.addEventListener("DOMContentLoaded", function () {

    const monthBtn = document.getElementById("esiMonth");
    const prevBtn = document.getElementById("prevMonth");
    const nextBtn = document.getElementById("nextMonth");
    const viewBtn = document.querySelector(".view-btn");

    let currentDate = new Date();
    if (window.currentMonthStr) {
        currentDate = new Date(window.currentMonthStr + "-01");
    }

    const months = [
        "JAN", "FEB", "MAR", "APR", "MAY", "JUN",
        "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"
    ];

    function updateMonth() {
        const d = new Date(currentDate);
        const y = d.getFullYear();
        let m = d.getMonth() + 1;
        if(m < 10) m = '0' + m;
        
        document.getElementById("monthInput").value = y + "-" + m;
        
        monthBtn.textContent = months[d.getMonth()] + "-" + y;
    }

    prevBtn.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateMonth();
        document.getElementById("esiForm").submit();
    });

    nextBtn.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateMonth();
        document.getElementById("esiForm").submit();
    });

    viewBtn.addEventListener("click", function () {
        const oldHtml = viewBtn.innerHTML;

        viewBtn.disabled = true;
        viewBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> View';

        setTimeout(function () {
            viewBtn.disabled = false;
            viewBtn.innerHTML = oldHtml;
        }, 700);
    });

});