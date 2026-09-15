document.addEventListener("DOMContentLoaded", function () {

    const monthBtn = document.getElementById("esiMonth");
    const prevBtn = document.getElementById("prevMonth");
    const nextBtn = document.getElementById("nextMonth");
    const actionBtns = document.querySelectorAll(".action-btn");

    let currentMonthValue = document.getElementById("monthInput").value; // e.g. "2026-06"
    let [year, month] = currentMonthValue.split('-');
    let currentDate = new Date(year, month - 1, 1);

    const months = [
        "JAN", "FEB", "MAR", "APR", "MAY", "JUN",
        "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"
    ];

    function updateMonth() {
        let m = currentDate.getMonth() + 1;
        let mStr = m < 10 ? '0' + m : m;
        document.getElementById("monthInput").value = currentDate.getFullYear() + "-" + mStr;
        document.getElementById("esiForm").submit();
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