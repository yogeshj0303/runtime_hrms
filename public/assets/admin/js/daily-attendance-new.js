document.addEventListener("DOMContentLoaded", function () {
    const attendanceData = [
        {
            name:"AAYUSHI YADAV", code:"812", date:"11-Jun-2026", shift:"Morning Shift",
            start:"09:27A", left:"03:30A", mid:"09:30A", end:"06:30P", extra:"12:30A",
            line:"Absent on total time < 269 mins for 4 time(s) in month (Rule # 3172)",
            location:"INDORE WORKSHOP JAWA", cost:"General Cost Center", dept:"Service"
        },
        {
            name:"AJAY SARVAA", code:"800", date:"11-Jun-2026", shift:"SALES",
            start:"09:13A", left:"04:00A", mid:"10:00A", end:"07:00P", extra:"01:00A",
            line:"Absent on total time < 269 mins for 2 time(s) in month (Rule # 3172)",
            location:"INDORE WORKSHOP JAWA", cost:"General Cost Center", dept:"Service"
        },
        {
            name:"CHETAN BAI BERWAD", code:"810", date:"11-Jun-2026", shift:"Morning Shift",
            start:"09:28A", left:"03:30A", mid:"09:30A", end:"06:30P", extra:"12:30A",
            line:"Absent on total time < 269 mins for 1 time(s) in month (Rule # 3172)",
            location:"INDORE WORKSHOP JAWA", cost:"General Cost Center", dept:"Service"
        },
        {
            name:"GOURAV PATEL", code:"807", date:"11-Jun-2026", shift:"Morning Shift",
            start:"09:28A", left:"03:30A", mid:"09:30A", end:"06:30P", extra:"12:30A",
            line:"Absent on total time < 269 mins for 2 time(s) in month (Rule # 3172)",
            location:"INDORE WORKSHOP JAWA", cost:"General Cost Center", dept:"Spare Parts"
        },
        {
            name:"HARI SHANKAR MAHAWAR", code:"3", date:"11-Jun-2026", shift:"SALES",
            start:"09:59A", left:"04:00A", mid:"10:00A", end:"07:00P", extra:"01:00A",
            line:"Absent on total time < 269 mins for 1 time(s) in month (Rule # 3172)",
            location:"INDORE WORKSHOP JAWA", cost:"General Cost Center", dept:"Service"
        },
        {
            name:"HARSHIT ROSHAN", code:"914", date:"11-Jun-2026", shift:"SALES",
            start:"10:21A", left:"04:00A", mid:"10:00A", end:"07:00P", extra:"01:00A",
            line:"Absent on total time < 269 mins for 1 time(s) in month (Rule # 3172)",
            location:"INDORE WORKSHOP JAWA", cost:"General Cost Center", dept:"Sales"
        },
        {
            name:"KUNAL VERMA", code:"802", date:"11-Jun-2026", shift:"Morning Shift",
            start:"10:01A", left:"03:30A", mid:"09:30A", end:"06:30P", extra:"12:30A",
            line:"Absent on total time < 269 mins for 3 time(s) in month (Rule # 3172)",
            location:"INDORE WORKSHOP JAWA", cost:"General Cost Center", dept:"Service"
        }
    ];

    let currentPage = 1;
    const perPage = 5;

    const list = document.getElementById("attendanceList");
    const search = document.getElementById("employeeSearch");
    const pageButtons = document.querySelectorAll(".page-btn");
    const prevPage = document.getElementById("prevPage");
    const nextPage = document.getElementById("nextPage");

    function getFilteredData(){
        const value = search.value.toLowerCase();

        if(value === ""){
            return attendanceData;
        }

        return attendanceData.filter(function(item){
            return item.name.toLowerCase().includes(value);
        });
    }

    function renderCards(){
        const data = getFilteredData();
        const totalPages = Math.ceil(data.length / perPage) || 1;

        if(currentPage > totalPages){
            currentPage = totalPages;
        }

        const start = (currentPage - 1) * perPage;
        const pageData = data.slice(start, start + perPage);

        list.innerHTML = "";

        pageData.forEach(function(item){
            const card = document.createElement("div");
            card.className = "attendance-card";

            card.innerHTML = `
                <div class="card-top">
                    <div class="emp-title">${item.name} <span>(${item.code})</span></div>

                    <div class="meta-row">
                        <span><i class="ri-map-pin-user-fill meta-location"></i> ${item.location}</span>
                        <span><i class="ri-bank-line meta-cost"></i> ${item.cost}</span>
                        <span><i class="ri-building-2-line meta-dept"></i> ${item.dept}</span>
                    </div>
                </div>

                <div class="card-body-row">
                    <div>
                        <a href="javascript:void(0)" class="date-link">${item.date}</a>
                        <div class="absent-text">Absent</div>
                        <div class="rule-text">${item.line}</div>
                    </div>

                    <div class="timeline-wrap">
                        <div class="shift-title">${item.shift}</div>

                        <div class="timeline-labels">
                            <span>${item.left}</span>
                            <span>${item.mid}</span>
                            <span>${item.end}</span>
                            <span>${item.extra}</span>
                        </div>

                        <div class="timeline-bar">
                            <div class="bar-green"></div>
                            <div class="bar-blue"></div>
                            <div class="bar-orange"></div>
                        </div>

                        <div class="punch-time">${item.start} <i class="ri-fingerprint-line"></i></div>
                    </div>

                    <div class="in-time">In-Time: <b>0 m</b></div>

                    <div class="card-actions">
                        <button type="button" class="round-btn blue">
                            <i class="ri-eye-line"></i>
                        </button>

                        <button type="button" class="round-btn gray">
                            <i class="ri-more-fill"></i>
                        </button>
                    </div>
                </div>
            `;

            list.appendChild(card);
        });

        pageButtons.forEach(function(btn){
            const page = Number(btn.dataset.page);
            btn.classList.toggle("active", page === currentPage);
            btn.style.display = page <= totalPages ? "inline-flex" : "none";
        });

        prevPage.disabled = currentPage === 1;
        nextPage.disabled = currentPage === totalPages;
    }

    pageButtons.forEach(function(btn){
        btn.addEventListener("click", function(){
            currentPage = Number(this.dataset.page);
            renderCards();
        });
    });

    prevPage.addEventListener("click", function(){
        if(currentPage > 1){
            currentPage--;
            renderCards();
        }
    });

    nextPage.addEventListener("click", function(){
        const totalPages = Math.ceil(getFilteredData().length / perPage) || 1;

        if(currentPage < totalPages){
            currentPage++;
            renderCards();
        }
    });

    search.addEventListener("change", function(){
        currentPage = 1;
        renderCards();
    });

    function changeDate(days){
        const input = document.getElementById("attendanceDate");
        const parts = input.value.split("-");
        const date = new Date(parts[2], parts[1] - 1, parts[0]);

        date.setDate(date.getDate() + days);

        const dd = String(date.getDate()).padStart(2, "0");
        const mm = String(date.getMonth() + 1).padStart(2, "0");
        const yyyy = date.getFullYear();

        input.value = dd + "-" + mm + "-" + yyyy;
    }

    document.getElementById("prevDate").addEventListener("click", function(){
        changeDate(-1);
    });

    document.getElementById("nextDate").addEventListener("click", function(){
        changeDate(1);
    });

    renderCards();
});