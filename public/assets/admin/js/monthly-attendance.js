document.addEventListener("DOMContentLoaded", function () {
    const months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

    let currentDate = new Date(2026, 5, 1);

    const monthInput = document.getElementById("monthInput");
    const prevMonth = document.getElementById("prevMonth");
    const nextMonth = document.getElementById("nextMonth");
    const employeeSelect = document.getElementById("employeeSelect");
    const empName = document.getElementById("empName");
    const refreshBtn = document.getElementById("refreshBtn");

    function updateMonth() {
        monthInput.value = months[currentDate.getMonth()] + "-" + currentDate.getFullYear();
    }

    if (prevMonth) {
        prevMonth.addEventListener("click", function () {
            currentDate.setMonth(currentDate.getMonth() - 1);
            updateMonth();
        });
    }

    if (nextMonth) {
        nextMonth.addEventListener("click", function () {
            currentDate.setMonth(currentDate.getMonth() + 1);
            updateMonth();
        });
    }

    if (employeeSelect && empName) {
        employeeSelect.addEventListener("change", function () {
            empName.textContent = this.value;
        });
    }

    if (refreshBtn) {
        refreshBtn.addEventListener("click", function () {
            alert("Attendance recalculated successfully.");
        });
    }

    const faqItems = document.querySelectorAll(".faq-item");

    faqItems.forEach(function (item) {
        const question = item.querySelector(".faq-question");

        question.addEventListener("click", function () {
            faqItems.forEach(function (otherItem) {
                if (otherItem !== item) {
                    otherItem.classList.remove("active");
                    const otherIcon = otherItem.querySelector(".faq-question i");
                    if (otherIcon) {
                        otherIcon.className = "ri-add-line";
                    }
                }
            });

            item.classList.toggle("active");

            const icon = item.querySelector(".faq-question i");
            if (item.classList.contains("active")) {
                icon.className = "ri-subtract-line";
            } else {
                icon.className = "ri-add-line";
            }
        });
    });
});