@extends('layouts.master')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<style>
/* ==========================================
GOOGLE FONT
Add in Head:
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
========================================== */

/* =========================
RESET
========================= */
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    outline:none;
    -webkit-tap-highlight-color:transparent;
}

body{
    background:#f6f8fb;
    font-family:'Poppins',sans-serif;
    color:#1f2937;
    line-height:1.5;
}

/* =========================
HEADER
========================= */
.setup-top{
    border-bottom:1px solid #d9e2ec;
    padding:18px 0;
    margin-bottom:20px;
}

.breadcrumb{
    font-size:13px;
    color:#667085;
    font-weight:500;
    margin-bottom:12px;
}

.title-row{
    display:flex;
    gap:14px;
    align-items:flex-start;
}

.title-row i{
    font-size:30px;
    color:#133C5A;
}

.title-row h2{
    font-size:22px;
    font-weight:700;
    color:#133C5A;
    margin-bottom:4px;
}

.title-row p{
    font-size:14px;
    color:#667085;
    font-weight:400;
}

/* =========================
LAYOUT
========================= */
.setup-wrap{
    display:flex;
    gap:24px;
    align-items:flex-start;
}

/* =========================
SIDEBAR
========================= */
.sidebar{
    width:290px;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:16px;
    overflow:hidden;
    position:sticky;
    top:15px;
    box-shadow:0 4px 20px rgba(0,0,0,.04);
}

/* =========================
SEARCH
========================= */
.search{
    padding:15px;
    border-bottom:1px solid #eef2f7;
}

.search-box{
    display:flex;
    border:1px solid #d0d5dd;
    border-radius:10px;
    overflow:hidden;
    background:#fff;
}

.search-box input{
    width:100%;
    padding:12px 14px;
    border:none;
    font-size:14px;
    font-family:'Poppins',sans-serif;
}

.search-box input::placeholder{
    color:#98a2b3;
}

.search-box span{
    width:50px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    color:#667085;
    transition:.3s;
}

.search-box span:hover{
    color:#133C5A;
}

/* =========================
MENU
========================= */
.menu{
    list-style:none;
    margin:0;
    padding:0;
}

.menu li{
    padding:14px 18px;
    display:flex;
    align-items:center;
    gap:12px;
    cursor:pointer;
    border-bottom:1px solid #f2f4f7;
    transition:all .3s ease;
    font-size:14px;
    font-weight:500;
    color:#344054;
    letter-spacing:.2px;
}

.menu li i{
    font-size:18px;
    min-width:20px;
}

.menu li:hover{
    background:#f8fbff;
    color:#133C5A;
    padding-left:24px;
}

.menu li.active{
    background:linear-gradient(135deg,#133C5A,#1d567f);
    color:#fff;
    font-weight:600;
    box-shadow:0 4px 12px rgba(19,60,90,.2);
}

/* =========================
CARDS WRAPPER
========================= */
.cards{
    flex:1;
}

/* =========================
TAB CONTENT
========================= */
.tab-content{
    display:none;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    width:100%;
    opacity:0;
    transform:translateY(10px);
    transition:.3s ease;
}

.tab-content.active{
    display:grid;
    opacity:1;
    transform:translateY(0);
}

/* =========================
CARD DESIGN
========================= */
.cardx{
    background:#fff;
    border:1px solid #e4e7ec;
    border-radius:18px;
    min-height:230px;
    padding:22px;
    display:flex;
    flex-direction:column;
    text-align:center;
    transition:all .35s ease;
    position:relative;
    overflow:hidden;
}

.cardx::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:4px;
    background:linear-gradient(90deg,#133C5A,#2f80ed);
}

.cardx:hover{
    transform:translateY(-6px);
    border-color:#133C5A;
    box-shadow:0 18px 40px rgba(0,0,0,.08);
}

/* =========================
CARD CONTENT
========================= */
.card-content{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

/* =========================
ICON
========================= */
.cardx .icon{
    width:72px;
    height:72px;
    display:flex;
    align-items:center;
    justify-content:center;
    border-radius:50%;
    background:rgba(19,60,90,.08);
    color:#133C5A;
    font-size:34px;
    margin-bottom:16px;
    transition:.3s;
}

.cardx:hover .icon{
    transform:scale(1.08);
}

/* =========================
CARD TEXT
========================= */
.cardx h4{
    font-size:17px;
    font-weight:600;
    color:#133C5A;
    margin-bottom:8px;
    line-height:1.4;
}

.cardx p{
    font-size:13px;
    color:#667085;
    line-height:1.7;
    font-weight:400;
}

/* =========================
BUTTON
========================= */
.btn-open{
    margin-top:18px;
    padding:11px 14px;
    border-radius:10px;
    text-decoration:none;
    text-align:center;
    color:#fff;
    font-size:13px;
    font-weight:600;
    letter-spacing:.4px;
    background:linear-gradient(135deg,#133C5A,#1d567f);
    transition:.3s ease;
}

.btn-open:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(19,60,90,.25);
}

/* =========================
SCROLLBAR
========================= */
::-webkit-scrollbar{
    width:8px;
}

::-webkit-scrollbar-track{
    background:#f1f5f9;
}

::-webkit-scrollbar-thumb{
    background:#cbd5e1;
    border-radius:10px;
}

::-webkit-scrollbar-thumb:hover{
    background:#94a3b8;
}

/* =========================
RESPONSIVE
========================= */
@media(max-width:1400px){
    .tab-content{
        grid-template-columns:repeat(3,1fr);
    }
}

@media(max-width:992px){

    .setup-wrap{
        flex-direction:column;
    }

    .sidebar{
        width:100%;
        position:relative;
        top:0;
    }

    .tab-content{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:768px){

    .title-row{
        flex-direction:column;
        gap:8px;
    }

    .title-row h2{
        font-size:20px;
    }

    .cardx{
        min-height:210px;
    }
}

@media(max-width:576px){

    .tab-content{
        grid-template-columns:1fr;
    }

    .sidebar{
        border-radius:12px;
    }

    .cardx{
        min-height:auto;
        padding:20px;
    }

    .cardx .icon{
        width:65px;
        height:65px;
        font-size:30px;
    }
}

/* REMOVE DEFAULT UL SPACE */
ul{
    padding-left:0 !important;
}
</style>

<div class="setup-page">

    <!-- HEADER -->
    <div class="setup-top">
        <div class="breadcrumb">Setup / All Setup Options</div>
        <div class="title-row">
            <i class="ri-settings-3-line"></i>
            <div>
                <h2>Setup</h2>
                <p>Manage application settings and options.</p>
            </div>
        </div>
    </div>

    <div class="setup-wrap">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <div class="search">
                <div class="search-box">
                    <input type="text" id="menuSearch" placeholder="Search Settings">
                    <span id="clearSearch"><i class="ri-close-line"></i></span>
                </div>
            </div>

            <ul class="menu">
                <li class="active" data-tab="master"><i class="ri-tools-line"></i> Master Setup</li>
                <li data-tab="salary"><i class="ri-money-dollar-circle-line"></i> Salary</li>
                <li data-tab="attendance"><i class="ri-calendar-check-line"></i> Attendance</li>
                <li data-tab="statutory"><i class="ri-folder-line"></i> Statutory</li>
                <li data-tab="ess"><i class="ri-user-line"></i> ESS</li>
                <li data-tab="integration"><i class="ri-global-line"></i> Integration</li>
                <li data-tab="advanced"><i class="ri-settings-4-line"></i> Advanced</li>
            </ul>

        </div>

        <!-- CARDS -->
        <div class="cards">

           <div class="tab-content active" id="master">

    <!-- Business Units -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-building-4-line"></i>
            </div>
            <h4>Business Units</h4>
            <p>Organization units</p>
        </div>
        <a href="{{route('business-units.index')}}" class="btn-open">Open</a>
    </div>

    <!-- Locations -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-map-pin-line"></i>
            </div>
            <h4>Locations</h4>
            <p>Office locations</p>
        </div>
        <a href="{{ route('locations.index') }}" class="btn-open">Open</a>
    </div>

    <!-- Cost Centers -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-bank-line"></i>
            </div>
            <h4>Cost Centers</h4>
            <p>Cost tracking</p>
        </div>
        <a href="{{route('cost-centers.index')}}" class="btn-open">Open</a>
    </div>

    <!-- Departments -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-team-line"></i>
            </div>
            <h4>Departments</h4>
            <p>Manage departments</p>
        </div>
        <a href="{{route('departments.index')}}" class="btn-open">Open</a>
    </div>

    <!-- Grades -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-award-line"></i>
            </div>
            <h4>Grades</h4>
            <p>Employee grades</p>
        </div>
        <a href="{{route('grades.index')}}" class="btn-open">Open</a>
    </div>

    <!-- Designation -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-user-star-line"></i>
            </div>
            <h4>Designation</h4>
            <p>Job positions</p>
        </div>
        <a href="{{ route('designations.index') }}" class="btn-open">Open</a>
    </div>

    <!-- General Settings -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-settings-3-line"></i>
            </div>
            <h4>General Settings</h4>
            <p>System configuration</p>
        </div>
        <a href="#" class="btn-open">Open</a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-settings-3-line"></i>
            </div>
            <h4>Visit Type</h4>
            <p>Visit types & mandatory fields for visit punche</p>
        </div>
        <a href="{{ route('visit-types.index') }}" class="btn-open">Open</a>
    </div>

    <!-- Help Desk -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-customer-service-2-line"></i>
            </div>
            <h4>Help Desk</h4>
            <p>Support management</p>
        </div>
        <a href="{{route('helpdesk-categories.index')}}" class="btn-open">Open</a>
    </div>

    <!-- Workflows -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-git-branch-line"></i>
            </div>
            <h4>Workflows</h4>
            <p>Process automation</p>
        </div>
        <a href="{{route('workflows.index')}}" class="btn-open">Open</a>
    </div>

    <!-- Exit Reasons -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-logout-box-r-line"></i>
            </div>
            <h4>Exit Reasons</h4>
            <p>Employee separation reasons</p>
        </div>
        <a href="{{route('exit-reasons.index')}}" class="btn-open">Open</a>
    </div>

    <!-- Letter Templates -->
    <div class="cardx">
        <div class="card-content">
            <div class="icon">
                <i class="ri-file-text-line"></i>
            </div>
            <h4>Letter Templates</h4>
            <p>Design HR letters dynamically</p>
        </div>
        <a href="{{route('setup.letter-templates.index')}}" class="btn-open">Open</a>
    </div>

</div>

            <div class="tab-content" id="salary">
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-scissors-line"></i></div>
                        <h4>Components</h4>
                        <p>Fixed, variable, time based components</p>
                    </div>
                    <a href="{{route('salary.components.index')}}" class="btn-open">Open</a>
                </div>
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-scissors-line"></i></div>
                        <h4>Deductions</h4>
                        <p>Salary deductions</p>
                    </div>
                    <a href="{{route('salary.deductions.index')}}" class="btn-open">Open</a>
                </div>
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-scissors-line"></i></div>
                        <h4>Salary Structure</h4>
                        <p>Defined allocation set of salary components</p>
                    </div>
                    <a href="{{route('salary.structures.index')}}" class="btn-open">Open</a>
                </div>
                   <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-scissors-line"></i></div>
                        <h4>Overtime Policies</h4>
                        <p>Configure overtime calculation rules & policies</p>
                    </div>
                    <a href="{{route('overtime.index')}}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-refund-2-line"></i></div>
                        <h4>Claims & Reimbursements</h4>
                        <p>Setup claim rules, limits and permissions</p>
                    </div>
                    <a href="{{ route('salary.claims.index') }}" class="btn-open">Open</a>
                </div>
            </div>

            <div class="tab-content" id="attendance">
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-time-line"></i></div>
                        <h4>Work Shifts</h4>
                        <p>Shift timing setup</p>
                    </div>
                    <a href="{{ route('shifts.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-calendar-event-line"></i></div>
                        <h4>Shift Policies</h4>
                        <p>Define default and rotating shift policies</p>
                    </div>
                    <a href="{{ route('shift-policies.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-timer-line"></i></div>
                        <h4>Time Rules</h4>
                        <p>Manage late coming, early going rules</p>
                    </div>
                    <a href="{{ route('time-rules.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-calendar-2-line"></i></div>
                        <h4>Week Off Policies</h4>
                        <p>Define general and alternating week off rules</p>
                    </div>
                    <a href="{{ route('week-off-policies.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-settings-4-line"></i></div>
                        <h4>Attendance Settings</h4>
                        <p>Configure Sandwich Rules, Manual Attendance</p>
                    </div>
                    <a href="{{ route('attendance-settings.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-calendar-todo-line"></i></div>
                        <h4>Leave Types</h4>
                        <p>Create and manage leave types</p>
                    </div>
                    <a href="{{ route('leave-types.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-file-list-3-line"></i></div>
                        <h4>Leave Policies</h4>
                        <p>Create and assign leave policies</p>
                    </div>
                    <a href="{{ route('leave-policies.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-calendar-event-line"></i></div>
                        <h4>Holidays</h4>
                        <p>Define holidays for different locations</p>
                    </div>
                    <a href="{{ route('holidays.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-swap-line"></i></div>
                        <h4>Comp Off Rules</h4>
                        <p>Configure comp off grant and lapse rules</p>
                    </div>
                    <a href="{{ route('comp-off-rules.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-error-warning-line"></i></div>
                        <h4>Strike Rules</h4>
                        <p>Configure early/late coming, going and lunch strike rules</p>
                    </div>
                    <a href="{{ route('strike-rules.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-heart-pulse-line"></i></div>
                        <h4>Maternity Leave Policy</h4>
                        <p>Configure maternity leave rules for each child type</p>
                    </div>
                    <a href="{{ route('maternity-leave-policy.index') }}" class="btn-open">Open</a>
                </div>
            </div>

            <div class="tab-content" id="statutory">
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-settings-3-line"></i></div>
                        <h4>ESI Settings</h4>
                        <p>Compliance settings</p>
                    </div>
                    <a href="{{ route('esi-settings.index') }}" class="btn-open">Open</a>
                </div>
                
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-settings-3-line"></i></div>
                        <h4>EPF Settings</h4>
                        <p>Compliance settings</p>
                    </div>
                    <a href="{{ route('epf-settings.index') }}" class="btn-open">Open</a>
                </div>
                
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-settings-3-line"></i></div>
                        <h4>Professional Tax</h4>
                        <p>Configure PT Rules</p>
                    </div>
                    <a href="{{ route('ptax-settings.index') }}" class="btn-open">Open</a>
                </div>
                
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-settings-3-line"></i></div>
                        <h4>Labour Welfare Fund</h4>
                        <p>Configure LWF Rules</p>
                    </div>
                    <a href="{{ route('setup.statutory.lwf.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-settings-3-line"></i></div>
                        <h4>Income Tax</h4>
                        <p>Configure Income Tax settings</p>
                    </div>
                    <a href="{{ route('setup.statutory.itax.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-information-line"></i></div>
                        <h4>TDS 24Q Info</h4>
                        <p>Configure TDS 24Q employer information</p>
                    </div>
                    <a href="{{ route('setup.statutory.tds24q.index') }}" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-information-line"></i></div>
                        <h4>Form 16 Info</h4>
                        <p>Configure Form 16 employer information</p>
                    </div>
                    <a href="{{ route('setup.statutory.form16.index') }}" class="btn-open">Open</a>
                </div>
            </div>

            <div class="tab-content" id="ess">
                <div class="cardx">
                    <div class="icon"><i class="ri-user-line"></i></div>
                    <h4>Approvals</h4>
                    <p>Workflow approvals</p>
                </div>
            </div>

            <div class="tab-content" id="integration">
                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-mail-send-line"></i></div>
                        <h4>Email</h4>
                        <p>Email integration</p>
                    </div>
                    <a href="#" class="btn-open">Open</a>
                </div>

                <div class="cardx">
                    <div class="card-content">
                        <div class="icon"><i class="ri-notification-3-line"></i></div>
                        <h4>Alert Rules</h4>
                        <p>Configure automated system alerts</p>
                    </div>
                    <a href="{{ route('alerts.rules') }}" class="btn-open">Open</a>
                </div>
            </div>

            <div class="tab-content" id="advanced">
                <div class="cardx">
                    <div class="icon"><i class="ri-tools-line"></i></div>
                    <h4>Maintenance</h4>
                    <p>System tools</p>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const menuItems = document.querySelectorAll(".menu li");
    const tabs = document.querySelectorAll(".tab-content");

    menuItems.forEach(item => {
        item.addEventListener("click", function () {

            menuItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");

            const tab = this.dataset.tab;

            tabs.forEach(t => t.classList.remove("active"));
            document.getElementById(tab).classList.add("active");

        });
    });

    // search
    const search = document.getElementById("menuSearch");
    const clear = document.getElementById("clearSearch");

    search.addEventListener("input", function () {
        const val = this.value.toLowerCase();

        menuItems.forEach(i => {
            i.style.display = i.textContent.toLowerCase().includes(val) ? "flex" : "none";
        });
    });

    clear.addEventListener("click", function () {
        search.value = "";
        menuItems.forEach(i => i.style.display = "flex");
    });

});
</script>

@endsection