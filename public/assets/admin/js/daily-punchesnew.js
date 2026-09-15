document.addEventListener("DOMContentLoaded", function () {
    const viewBtn = document.getElementById("viewBtn");
    const employeeSearch = document.getElementById("employeeSearch");

    if (viewBtn) {
        viewBtn.addEventListener("click", function () {
            console.log("Daily punches view clicked");
        });
    }

    if (employeeSearch) {
        employeeSearch.addEventListener("keyup", function () {
            const value = this.value.toLowerCase();
            const rows = document.querySelectorAll(".daily-table-card tbody tr");

            rows.forEach(function (row) {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(value) ? "" : "none";
            });
        });
    }
});