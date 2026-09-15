document.addEventListener("DOMContentLoaded", function () {

    const monthBtn = document.getElementById("pfMonth");
    const prevBtn = document.getElementById("prevMonth");
    const nextBtn = document.getElementById("nextMonth");
    const actionBtns = document.querySelectorAll(".action-btn");

    let currentDate = new Date(2026, 5, 1);

    const months = [
        "JAN", "FEB", "MAR", "APR", "MAY", "JUN",
        "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"
    ];

    function updateMonth() {
        monthBtn.textContent = months[currentDate.getMonth()] + "-" + currentDate.getFullYear();
    }

    prevBtn.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateMonth();
    });

    nextBtn.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateMonth();
    });

    actionBtns.forEach(function (btn) {
        btn.addEventListener("click", function () {
            const oldHtml = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Loading';

            setTimeout(function () {
                btn.disabled = false;
                btn.innerHTML = oldHtml;
            }, 700);
        });
    });

});