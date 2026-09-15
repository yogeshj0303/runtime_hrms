document.addEventListener('DOMContentLoaded', function () {

    const viewBtn = document.querySelector('.view-btn');

    if (viewBtn) {
        viewBtn.addEventListener('click', function () {
            const oldHtml = viewBtn.innerHTML;

            viewBtn.disabled = true;
            viewBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> View';

            setTimeout(function () {
                viewBtn.disabled = false;
                viewBtn.innerHTML = oldHtml;
            }, 700);
        });
    }

});