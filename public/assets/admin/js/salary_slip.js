document.addEventListener("DOMContentLoaded", function () {
    const salaryMonth = document.getElementById("salaryMonth");
    const prevMonth = document.getElementById("prevMonth");
    const nextMonth = document.getElementById("nextMonth");

    function changeMonth(type) {
        if (!salaryMonth) return;

        const currentValue = salaryMonth.value || new Date().toISOString().slice(0, 7);
        const date = new Date(currentValue + "-01");

        if (type === "prev") date.setMonth(date.getMonth() - 1);
        if (type === "next") date.setMonth(date.getMonth() + 1);

        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, "0");

        salaryMonth.value = `${year}-${month}`;
    }

    if (prevMonth) {
        prevMonth.addEventListener("click", function () {
            changeMonth("prev");
        });
    }

    if (nextMonth) {
        nextMonth.addEventListener("click", function () {
            changeMonth("next");
        });
    }

    const saveBtn = document.querySelector(".save-btn");

    if (saveBtn) {
        saveBtn.addEventListener("click", function () {
            alert("Salary slip options saved successfully!");
        });
    }
});