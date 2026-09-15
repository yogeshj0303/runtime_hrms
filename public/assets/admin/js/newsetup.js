document.addEventListener("DOMContentLoaded", function () {

    console.log("Setup JS Loaded");

    const menuItems = document.querySelectorAll(".menu li");
    const tabs = document.querySelectorAll(".tab-content");

    menuItems.forEach(item => {

        item.addEventListener("click", function () {

            const tabId = this.getAttribute("data-tab");

            menuItems.forEach(li => {
                li.classList.remove("active");
            });

            tabs.forEach(tab => {
                tab.classList.remove("active");
            });

            this.classList.add("active");

            const activeTab = document.getElementById(tabId);

            if (activeTab) {
                activeTab.classList.add("active");
            }

        });

    });

    const search = document.getElementById("menuSearch");
    const clear = document.getElementById("clearSearch");

    if (search) {

        search.addEventListener("input", function () {

            const value = this.value.toLowerCase();

            menuItems.forEach(item => {

                item.style.display =
                    item.textContent.toLowerCase().includes(value)
                        ? "flex"
                        : "none";

            });

        });

    }

    if (clear) {

        clear.addEventListener("click", function () {

            if (search) {
                search.value = "";
            }

            menuItems.forEach(item => {
                item.style.display = "flex";
            });

        });

    }

});