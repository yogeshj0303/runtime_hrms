document.addEventListener('DOMContentLoaded', function () {

    const viewBtn = document.getElementById('viewBtn');
    const excelBtn = document.getElementById('excelBtn');
    const inactiveUsers = document.getElementById('inactive_users');

    viewBtn?.addEventListener('click', function () {
        console.log('Workman View clicked');
        console.log('Inactive Users Only:', inactiveUsers.checked);
    });

    excelBtn?.addEventListener('click', function () {
        console.log('Workman Excel clicked');
    });

});