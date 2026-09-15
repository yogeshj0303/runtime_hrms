document.addEventListener("DOMContentLoaded", function () {
    const radios = document.querySelectorAll('input[name="revision_mode"]');

    radios.forEach(radio => {
        radio.addEventListener("change", function () {
            radios.forEach(r => {
                r.closest(".radio-row").classList.remove("active");
            });

            this.closest(".radio-row").classList.add("active");
        });
    });

    const downloadBtn = document.getElementById("downloadBtn");
    const exportFormat = document.getElementById("exportFormat");
    const exportType = document.getElementById("exportType");
    const ctcForm = document.getElementById("ctcForm");

    if (downloadBtn) {
        downloadBtn.addEventListener("click", function () {
            exportType.value = exportFormat.value;

            const oldHtml = downloadBtn.innerHTML;
            downloadBtn.disabled = true;
            downloadBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Downloading';

            setTimeout(function () {
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = oldHtml;
                ctcForm.submit();
            }, 800);
        });
    }
});