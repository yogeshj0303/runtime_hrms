document.addEventListener("DOMContentLoaded", function () {
    const monthText = document.getElementById("monthText");
    const prevMonth = document.getElementById("prevMonth");
    const nextMonth = document.getElementById("nextMonth");

    let currentDate = new Date(2026, 4, 1);

    function updateMonthText() {
        const month = currentDate.toLocaleString("en-US", { month: "short" }).toUpperCase();
        const year = currentDate.getFullYear();
        monthText.textContent = `${month}-${year}`;
    }

    prevMonth.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() - 1);
        updateMonthText();
    });

    nextMonth.addEventListener("click", function () {
        currentDate.setMonth(currentDate.getMonth() + 1);
        updateMonthText();
    });

    updateMonthText();
});