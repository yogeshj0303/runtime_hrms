document.addEventListener("DOMContentLoaded", function () {

    const recordTypes = document.querySelectorAll(".record-type");

    recordTypes.forEach(function (item) {
        item.addEventListener("change", function () {
            if (this.checked) {
                recordTypes.forEach(function (other) {
                    if (other !== item) {
                        other.checked = false;
                    }
                });
            }
        });
    });

});