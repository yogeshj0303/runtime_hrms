document.addEventListener("DOMContentLoaded", function () {

    const downloadBtn = document.getElementById("downloadBtn");
    const exportForm = document.getElementById("exportRecordsForm");

    if (downloadBtn) {
        downloadBtn.addEventListener("click", function () {
            const oldHtml = downloadBtn.innerHTML;

            downloadBtn.disabled = true;
            downloadBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Downloading';

            setTimeout(function () {
                downloadBtn.disabled = false;
                downloadBtn.innerHTML = oldHtml;

                // Backend form submit ke liye ye line uncomment karna
                exportForm.submit();

            }, 800);
        });
    }

});