
const setupData = {

master: [

{
icon:'ri-building-4-line',
title:'Business Units',
desc:'Business Units within the organization'
},

{
icon:'ri-map-pin-line',
title:'Locations',
desc:'Office locations and Geo-Fencing'
},

{
icon:'ri-bank-line',
title:'Cost Centers',
desc:'Cost-centers in the organization'
},

{
icon:'ri-building-line',
title:'Departments',
desc:'Departments & department heads'
},

{
icon:'ri-upload-2-line',
title:'Grades',
desc:'Employee types and categories'
},

{
icon:'ri-id-card-line',
title:'Designations',
desc:'Employee titles and categories'
},

{
icon:'ri-information-line',
title:'General Settings',
desc:'General options and configuration settings'
},

{
icon:'ri-user-location-line',
title:'Visit Punch Options',
desc:'Visit types & mandatory fields for visit punch'
},

{
icon:'ri-question-line',
title:'Helpdesk',
desc:'View & manage helpdesk request categories'
},

{
icon:'ri-git-branch-line',
title:'Workflows',
desc:'Custom Workflows for approvals'
},

{
icon:'ri-door-open-line',
title:'Exit Reasons',
desc:'Customize employee exit reasons'
}

],

salary: [

{
    icon:'ri-add-circle-line',
    title:'Components',
    desc:'Fixed, variable, time based components'
},
{
    icon:'ri-scissors-line',
    title:'Deductions',
    desc:'Fixed, percent & calculated deductions'
},
{
    icon:'ri-wallet-3-line',
    title:'Salary Structures',
    desc:'Defined allocation set of salary components'
},
{
    icon:'ri-time-line',
    title:'Time Salary Calculation',
    desc:'Setup calculation rules for time-based (hourly) salary'
},
{
    icon:'ri-equalizer-line',
    title:'Overtime Policies',
    desc:'Configure overtime calculation rules & policies'
},
{
    icon:'ri-money-rupee-circle-line',
    title:'Claims & Reimbursements',
    desc:'Setup Claim & Reimbursement rules, limits and permissions.'
}
],

attendance: [
{
    icon:'ri-time-line',
    title:'Work Shifts',
    desc:'Work shifts with start/end time'
},

{
    icon:'ri-list-check-3',
    title:'Shift Policies',
    desc:'Rotating or fixed shifts by weekdays'
},

{
    icon:'ri-timer-line',
    title:'Time Rules',
    desc:'Configure actions for late coming, early going etc.'
},

{
    icon:'ri-calendar-2-line',
    title:'Week Off Policies',
    desc:'Fixed or alternating weekly offs'
},

{
    icon:'ri-calendar-check-line',
    title:'Attendance Settings',
    desc:'Default attendance and sandwich rules'
},

{
    icon:'ri-calendar-event-line',
    title:'Leave Types',
    desc:'Configure Leave Types & Settings'
},

{
    icon:'ri-calendar-todo-line',
    title:'Leave Policies',
    desc:'Auto leave grant and leave lapse rules'
},

{
    icon:'ri-calendar-line',
    title:'Holidays',
    desc:'Yearly holiday list for each location'
},

{
    icon:'ri-calendar-schedule-line',
    title:'Comp Off Rules',
    desc:'Auto-Comp Off grant rules and settings'
},

{
    icon:'ri-flashlight-line',
    title:'Strike Rules',
    desc:'Late coming, early going strike rules'
},

{
    icon:'ri-calendar-close-line',
    title:'Strike Adjustments',
    desc:'Auto half-day or absent based on strikes'
}
],

statutory: [
{
    icon:'ri-settings-3-line',
    title:'ESI Settings',
    desc:'Employee State Insurance configuration and setup'
},

{
    icon:'ri-settings-3-line',
    title:'EPF Settings',
    desc:'Provident Fund configuration and contribution setup'
},

{
    icon:'ri-settings-3-line',
    title:'Professional Tax',
    desc:'Professional tax slabs and deduction settings'
},

{
    icon:'ri-settings-3-line',
    title:'Labour Welfare Fund',
    desc:'Labour Welfare Fund contribution configuration'
},

{
    icon:'ri-settings-3-line',
    title:'Income Tax',
    desc:'Income tax calculation and TDS configuration'
},

{
    icon:'ri-information-line',
    title:'TDS 24Q Info',
    desc:'Quarterly TDS return information and settings'
},

{
    icon:'ri-information-line',
    title:'FORM-16 Info',
    desc:'FORM-16 details and employee tax information'
}
],

ess: [

{
    icon:'ri-calendar-check-line',
    title:'Approvals',
    desc:'Leaves & other approving authority and workflow'
},

{
    icon:'ri-calendar-schedule-line',
    title:'Comp Offs',
    desc:'Set compensatory off lapse rules'
},

{
    icon:'ri-bike-line',
    title:'Travel',
    desc:'Options for travel calculation and adjustments'
},

{
    icon:'ri-toggle-line',
    title:'Permissions',
    desc:'Set employee permissions for Workman app'
},

{
    icon:'ri-team-line',
    title:'User Management',
    desc:'Create users for Workman and Connect portal'
}
],

integration: [
{
    icon:'ri-mail-send-line',
    title:'E-Mail Integration',
    desc:'Use custom email to send email communications.'
},

{
    icon:'ri-fingerprint-line',
    title:'Biometric Sync',
    desc:'Sync data from on-premise biometric machines.'
},

{
    icon:'ri-qr-code-line',
    title:'Gatekeeper',
    desc:'Setup attendance tablet for live attendance.'
},

{
    icon:'ri-database-2-line',
    title:'SQL Server',
    desc:'Fetch attendance from public SQL Server.'
},

{
    icon:'ri-bank-card-line',
    title:'Zwitch Payments',
    desc:"Salary payouts to employees' bank accounts."
},

{
    icon:'ri-shield-check-line',
    title:'SpringVerify',
    desc:'Perform employee background verifications.'
},

{
    icon:'ri-microsoft-line',
    title:'Microsoft Entra ID',
    desc:'Sync users from Microsoft 365 to Runtime HRMS.'
},

{
    icon:'ri-file-transfer-line',
    title:'SAP Mapping',
    desc:'Map fields for SAP import template.'
},

{
    icon:'ri-links-line',
    title:'API Access',
    desc:'Custom integrations with Runtime HRMS API.'
}
],

advanced: [
{
    icon:'ri-tools-line',
    title:'Maintenance',
    desc:'Find & Fix common issues with data'
},

{
    icon:'ri-sort-number-asc',
    title:'Employee Code',
    desc:'Employee Code Prefix and Suffix Options'
},

{
    icon:'ri-arrow-left-right-line',
    title:'Employee Transfer',
    desc:'Transfer employees between your accounts'
}
]

};

function renderCards(tab){

const container = document.getElementById('cardsContainer');

container.innerHTML = setupData[tab].map(card => `
<div class="cardx">
    <div class="card-content">
        <div class="icon">
            <i class="${card.icon}"></i>
        </div>
        <h4>${card.title}</h4>
        <p>${card.desc}</p>
    </div>
    <button class="btn-open">Open</button>
</div>
`).join('');

}

document.querySelectorAll('.menu li').forEach(item=>{

item.addEventListener('click',function(){

document
.querySelectorAll('.menu li')
.forEach(li=>li.classList.remove('active'));

this.classList.add('active');

renderCards(this.dataset.tab);

});

});

renderCards('master');






// 



document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("menuSearch");
    const clearBtn = document.getElementById("clearSearch");
    const menuItems = document.querySelectorAll(".menu li");

    searchInput.addEventListener("input", function () {

        let searchText = this.value.toLowerCase().trim();

        menuItems.forEach(item => {
            let text = item.textContent.toLowerCase();

            if (text.includes(searchText)) {
                item.style.display = "flex";
            } else {
                item.style.display = "none";
            }
        });

    });

    clearBtn.addEventListener("click", function () {
        searchInput.value = "";

        menuItems.forEach(item => {
            item.style.display = "flex";
        });

        searchInput.focus();
    });

});
