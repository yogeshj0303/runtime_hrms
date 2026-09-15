document.addEventListener("DOMContentLoaded", function () {
    const dateInput = document.getElementById("punchDate");
    const prevDate = document.getElementById("prevDate");
    const nextDate = document.getElementById("nextDate");

    function parseDate(value){
        const parts = value.split("-");
        return new Date(parts[2], parts[1] - 1, parts[0]);
    }

    function formatDate(date){
        const dd = String(date.getDate()).padStart(2, "0");
        const mm = String(date.getMonth() + 1).padStart(2, "0");
        const yyyy = date.getFullYear();

        return `${dd}-${mm}-${yyyy}`;
    }

    if(prevDate && nextDate && dateInput){
        prevDate.addEventListener("click", function(){
            let d = parseDate(dateInput.value);
            d.setDate(d.getDate() - 1);
            dateInput.value = formatDate(d);
        });

        nextDate.addEventListener("click", function(){
            let d = parseDate(dateInput.value);
            d.setDate(d.getDate() + 1);
            dateInput.value = formatDate(d);
        });
    }
});