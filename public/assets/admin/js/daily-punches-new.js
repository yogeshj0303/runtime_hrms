document.addEventListener("DOMContentLoaded", function () {
    const allData = [
        {sn:1, employee:"AAYUSHI YADAV", code:"812", designation:"CRE", start:"09:27:12"},
        {sn:2, employee:"AJAY SARVAA", code:"800", designation:"TECHNICIAN", start:"09:13:04"},
        {sn:3, employee:"CHETAN BAI BERWAD", code:"810", designation:"HK LADY", start:"09:28:40"},
        {sn:4, employee:"GOURAV PATEL", code:"807", designation:"SPARE PARTS EXE", start:"09:28:27"},
        {sn:5, employee:"HARI SHANKAR MAHAWAR", code:"3", designation:"WASHING BOY", start:"09:59:26"},
        {sn:6, employee:"HARSHIT ROSHAN", code:"914", designation:"SALES COUNSLTANT", start:"10:21:12"},
        {sn:7, employee:"KUNAL VERMA", code:"802", designation:"SERVICE ADVISOR", start:"10:01:12"},
        {sn:8, employee:"MAYUR HANS", code:"905", designation:"OFFICE BOY", start:"10:02:15"},
        {sn:9, employee:"MUKESH THAKUR", code:"803", designation:"TECHNICIAN", start:"10:03:06"},
        {sn:10, employee:"NEERAJ KUMAR BHATT", code:"804", designation:"SERVICE MANAGER", start:"10:00:23"},
        {sn:11, employee:"RAHUL SHARMA", code:"815", designation:"TECHNICIAN", start:"10:04:17"},
        {sn:12, employee:"VIKAS SEN", code:"820", designation:"ADVISOR", start:"10:06:42"}
    ];

    let currentPage = 1;
    const perPage = 10;

    const tableBody = document.getElementById("punchTableBody");
    const searchInput = document.getElementById("employeeSearch");
    const pageButtons = document.querySelectorAll(".page-btn");
    const prevPageBtn = document.getElementById("prevPage");
    const nextPageBtn = document.getElementById("nextPage");

    function getFilteredData() {
        const searchValue = searchInput.value.toLowerCase();

        return allData.filter(function (item) {
            return item.employee.toLowerCase().includes(searchValue)
                || item.code.toLowerCase().includes(searchValue)
                || item.designation.toLowerCase().includes(searchValue);
        });
    }

    function renderTable() {
        const filteredData = getFilteredData();
        const totalPages = Math.ceil(filteredData.length / perPage) || 1;

        if (currentPage > totalPages) {
            currentPage = totalPages;
        }

        const startIndex = (currentPage - 1) * perPage;
        const pageData = filteredData.slice(startIndex, startIndex + perPage);

        tableBody.innerHTML = "";

        pageData.forEach(function (item) {
            const row = document.createElement("tr");

            row.innerHTML = `
                <td>${item.sn}</td>
                <td>
                    <span class="emp-name">${item.employee}</span>
                    <span class="emp-code">${item.code}</span>
                </td>
                <td>${item.designation}</td>
                <td>
                    <span class="start-time">
                        <i class="ri-fingerprint-line"></i> ${item.start}
                    </span>
                </td>
                <td></td>
                <td>0:00</td>
                <td><span class="attendance-badge">A</span></td>
                <td>
                    <button type="button" class="action-icon blue">
                        <i class="ri-eye-line"></i>
                    </button>
                    <button type="button" class="action-icon gray">
                        <i class="ri-more-fill"></i>
                    </button>
                </td>
            `;

            tableBody.appendChild(row);
        });

        pageButtons.forEach(function (btn) {
            const page = Number(btn.dataset.page);
            btn.classList.toggle("active", page === currentPage);
            btn.style.display = page <= totalPages ? "inline-flex" : "none";
        });

        prevPageBtn.disabled = currentPage === 1;
        nextPageBtn.disabled = currentPage === totalPages;
    }

    pageButtons.forEach(function (btn) {
        btn.addEventListener("click", function () {
            currentPage = Number(this.dataset.page);
            renderTable();
        });
    });

    prevPageBtn.addEventListener("click", function () {
        if (currentPage > 1) {
            currentPage--;
            renderTable();
        }
    });

    nextPageBtn.addEventListener("click", function () {
        const totalPages = Math.ceil(getFilteredData().length / perPage) || 1;

        if (currentPage < totalPages) {
            currentPage++;
            renderTable();
        }
    });

    searchInput.addEventListener("keyup", function () {
        currentPage = 1;
        renderTable();
    });

    function changeDate(days) {
        const input = document.getElementById("punchDate");
        const parts = input.value.split("-");
        let date = new Date(parts[2], parts[1] - 1, parts[0]);

        date.setDate(date.getDate() + days);

        const dd = String(date.getDate()).padStart(2, "0");
        const mm = String(date.getMonth() + 1).padStart(2, "0");
        const yyyy = date.getFullYear();

        input.value = dd + "-" + mm + "-" + yyyy;
    }

    document.getElementById("prevDate").addEventListener("click", function () {
        changeDate(-1);
    });

    document.getElementById("nextDate").addEventListener("click", function () {
        changeDate(1);
    });

    renderTable();
});