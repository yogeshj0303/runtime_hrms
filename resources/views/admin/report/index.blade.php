@extends('layouts.master')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    outline:none;
}

body{
    background:#f6f8fb;
    font-family:'Poppins',sans-serif;
    color:#1f2937;
}

.report-page{
    padding:0 10px 30px;
}

.report-top{
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
    margin:0 0 4px;
}

.title-row p{
    font-size:14px;
    color:#667085;
    margin:0;
}

.report-wrap{
    display:flex;
    gap:24px;
    align-items:flex-start;
}

.report-sidebar{
    width:290px;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:16px;
    overflow:hidden;
    position:sticky;
    top:15px;
    box-shadow:0 4px 20px rgba(0,0,0,.04);
    flex-shrink:0;
}

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

.search-box span{
    width:50px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    color:#667085;
}

.search-box span:hover{
    color:#133C5A;
}

.menu{
    list-style:none;
    margin:0;
    padding:0 !important;
}

.menu li{
    padding:14px 18px;
    display:flex;
    align-items:center;
    gap:12px;
    cursor:pointer;
    border-bottom:1px solid #f2f4f7;
    transition:.3s;
    font-size:14px;
    font-weight:500;
    color:#344054;
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
}

.badge-new{
    margin-left:auto;
    background:#f59e0b;
    color:#fff;
    font-size:10px;
    padding:2px 7px;
    border-radius:10px;
}

.report-cards{
    flex:1;
}

.tab-content{
    display:none;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
}

.tab-content.active{
    display:grid;
}

.cardx{
    background:#fff;
    border:1px solid #e4e7ec;
    border-radius:18px;
    min-height:230px;
    padding:22px;
    display:flex;
    flex-direction:column;
    text-align:center;
    transition:.35s;
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

.card-content{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:center;
    align-items:center;
}

.report-icons{
    width:72px;
    height:72px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:4px;
    border-radius:50%;
    background:rgba(19,60,90,.08);
    color:#133C5A;
    font-size:28px;
    margin:0 auto 16px;
}

.cardx:hover .report-icons{
    transform:scale(1.08);
}

.cardx h4{
    font-size:17px;
    font-weight:600;
    color:#133C5A;
    margin:0 0 8px;
    line-height:1.4;
}

.cardx p{
    font-size:13px;
    color:#667085;
    line-height:1.7;
    margin:0;
}

.btn-open{
    margin-top:18px;
    padding:11px 14px;
    border-radius:10px;
    text-decoration:none;
    text-align:center;
    color:#fff;
    font-size:13px;
    font-weight:600;
    background:linear-gradient(135deg,#133C5A,#1d567f);
    transition:.3s;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:6px;
}

.btn-open:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(19,60,90,.25);
}

.blue{color:#2563eb;}
.excel{color:#16a34a;}
.pdf{color:#dc2626;}

@media(max-width:1400px){
    .tab-content{
        grid-template-columns:repeat(3,1fr);
    }
}

@media(max-width:992px){
    .report-wrap{
        flex-direction:column;
    }

    .report-sidebar{
        width:100%;
        position:relative;
        top:0;
    }

    .tab-content{
        grid-template-columns:repeat(2,1fr);
    }
}

@media(max-width:576px){
    .tab-content{
        grid-template-columns:1fr;
    }

    .cardx{
        min-height:auto;
    }
}



/**/


#ai{
    display:none;
}

#ai.active{
    display:block;
}

.ai-report-box{
    width:100%;
    background:#fff;
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:24px;
}

.ai-report-box h3{
    font-size:22px;
    font-weight:700;
    color:#133C5A;
    margin-bottom:8px;
}

.ai-report-box p{
    font-size:14px;
    color:#667085;
    margin-bottom:18px;
}

.ai-input-wrap{
    display:flex;
    width:100%;
    border:1px solid #d0d5dd;
    border-radius:10px;
    overflow:hidden;
    margin-bottom:14px;
}

.ai-input-wrap input{
    flex:1;
    border:none;
    padding:14px 16px;
    font-size:14px;
    font-family:'Poppins',sans-serif;
}

.ai-input-wrap button{
    border:none;
    background:#6b7280;
    color:#fff;
    padding:0 24px;
    cursor:pointer;
    display:flex;
    align-items:center;
    gap:8px;
    font-weight:600;
}

.ai-input-wrap button:hover{
    background:#133C5A;
}

.ai-footer{
    font-size:13px;
    color:#667085;
}

.ai-footer a{
    color:#2563eb;
    text-decoration:none;
    font-weight:600;
}

.ai-footer a:hover{
    text-decoration:underline;
}
</style>

<div class="report-page">

    <div class="report-top">
        <div class="breadcrumb">Reports / All Reports</div>

        <div class="title-row">
            <i class="ri-bar-chart-line"></i>
            <div>
                <h2>Reports</h2>
                <p>Generate and analyze from <b>50+</b> reports related to salary, attendance, payroll, compliance and more.</p>
            </div>
        </div>
    </div>

    <div class="report-wrap">

        <div class="report-sidebar">
            <div class="search">
                <div class="search-box">
                    <input type="text" id="menuSearch" placeholder="Search Reports">
                    <span id="clearSearch"><i class="ri-close-line"></i></span>
                </div>
            </div>

            <ul class="menu">
                <li data-tab="ai"><i class="ri-robot-2-line"></i> AI Reporting <span class="badge-new">New</span></li>
                <li class="active" data-tab="salary"><i class="ri-money-rupee-circle-line"></i> Salary Reports</li>
                <li data-tab="attendance"><i class="ri-calendar-check-line"></i> Attendance Reports</li>
                <li data-tab="employee"><i class="ri-user-line"></i> Employee Reports</li>
                <li data-tab="statutory"><i class="ri-folder-line"></i> Statutory Reports</li>
                <li data-tab="annual"><i class="ri-calendar-2-line"></i> Annual Reports</li>
                <li data-tab="performance"><i class="ri-dashboard-line"></i> Performance</li>
                <li data-tab="other"><i class="ri-information-line"></i> Other Reports</li>
            </ul>
        </div>

        <div class="report-cards">
<div class="tab-content" id="ai">

    <div class="ai-report-box">

        <h3>AI Reporting</h3>

        <p>
            Ask your query and click Run to generate a report
        </p>

        <div class="ai-input-wrap">
            <input type="text"
                   placeholder="Show me list of employees who joined this year.">

            <button type="button">
                <i class="ri-send-plane-fill"></i>
                Run
            </button>
        </div>

        <div class="ai-footer">
            New to AI Reports?
            <a href="#">Read this blog post</a>
            to catch up.
        </div>

    </div>

</div>
            <div class="tab-content active" id="salary">

    <!-- Salary Summary -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-fill blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Salary Summary</h4>
            <p>Salary summary by location, department etc.</p>
        </div>
     <a href="{{ route('salary_summary') }}" class="btn-open">
    Open <i class="ri-arrow-right-circle-line"></i>
</a>
    </div>

    <!-- Salary Register -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-excel-2-fill excel"></i>
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>Salary Register</h4>
            <p>Customizable salary register with filters.</p>
        </div>
         <a href="{{ route('salary_register') }}" class="btn-open">
        Open <i class="ri-arrow-right-circle-line"></i>
    </a>
    </div>

    <!-- Salary Slips -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>Salary Slips</h4>
            <p>Customizable salary slips for employees.</p>
        </div>
        <a href="{{ route('salary_slip') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Bank Transfer Letter -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-bank-card-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Bank Transfer Letter</h4>
            <p>Excel summary of net payable salary.</p>
        </div>
        <a href="#" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

<!-- Overtime Register -->
<div class="cardx">
    <div class="card-content">
        <div class="report-icons">
            <i class="ri-time-line blue"></i>
            <i class="ri-file-excel-2-fill excel"></i>
        </div>
        <h4>Overtime Register</h4>
        <p>Overtime hours and amount for a period.</p>
    </div>

    <a href="{{ route('overtime_register') }}" class="btn-open">
        Open <i class="ri-arrow-right-circle-line"></i>
    </a>
</div>

    <!-- Cost To Company -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-money-dollar-circle-line blue"></i>
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>Cost To Company</h4>
            <p>Employee level cost to company (CTC).</p>
        </div>
        <a href="{{ route('cost_to_company') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Variable Salary -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-funds-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Variable Salary</h4>
            <p>Details of manually captured salary.</p>
        </div>
        <a href="{{ route('variable_salary') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Time Salary -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-calendar-schedule-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Time Salary</h4>
            <p>Details of time based salary earnings.</p>
        </div>
        <a href="{{ route('time_salary') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Rate Salary -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-line-chart-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Rate Salary</h4>
            <p>Details of rate based salary calculations.</p>
        </div>
        <a href="{{ route('rate_salary') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Leave Encashment -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-briefcase-4-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Leave Encashment</h4>
            <p>Leave encashment processed in a period.</p>
        </div>
        <a href="{{ route('leave_encashment') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Statutory Bonus -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-award-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Statutory Bonus</h4>
            <p>Statutory bonus processed in a period.</p>
        </div>

        <a href="{{ route('statutory_bonus') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Salary Deductions -->
    <div class="cardx">


        <div class="card-content">
            <div class="report-icons">
                <i class="ri-subtract-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Salary Deductions</h4>
            <p>Details of calculated and variable deductions.</p>
        </div>
        <a href="{{ route('salary_deductions') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Time Deductions -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-timer-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Time Deductions</h4>
            <p>Details of time based deductions.</p>
        </div>
        <a href="{{ route('time_deductions') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- Employee Loans -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-hand-coin-line blue"></i>
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>Employee Loans</h4>
            <p>List of loans issued to employees with status.</p>
        </div>
        <a href="{{ route('employee_loans') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <!-- SAP Export -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-download-cloud-2-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>SAP Export</h4>
            <p>Export salary details to import in SAP.</p>
        </div>
        <a href="{{ route('sap_export') }}" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

</div>

          <div class="tab-content" id="attendance">

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Attendance Register</h4>
            <p>Details of daily attendance during a date range</p>
        </div>
        <a href="{{ route('attendance_register') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Leave Register</h4>
            <p>Leave activity report for a given period.</p>
        </div>
        <a href="{{ route('leave_register') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
                <i class="ri-file-pdf-2-line red"></i>
            </div>
            <h4>Time Register</h4>
            <p>Daily breakup of employee time like late coming</p>
        </div>
        <a href="{{ route('time_register') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
                <i class="ri-file-pdf-2-line red"></i>
            </div>
            <h4>Time Rules Register</h4>
            <p>Applicability of time rules and effect on attendance</p>
        </div>
        <a href="{{ route('time_rules_egister') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
                <i class="ri-file-pdf-2-line red"></i>
            </div>
            <h4>Strike Register</h4>
            <p>Strikes recorded on employees attendance</p>
        </div>
        <a href="{{ route('strike_register') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
                <i class="ri-file-pdf-2-line red"></i>
            </div>
            <h4>Travel Register</h4>
            <p>Details of travels & distance travelled by employees</p>
        </div>
        <a href="" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Daily Attendance</h4>
            <p>Attendance with punch details for a date range</p>
        </div>
        <a href="{{ route('daily_attendance') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Punch Details</h4>
            <p>Details of punches with address and location</p>
        </div>
        <a href="{{ route('punch_details') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-excel-2-line green"></i>
                <i class="ri-file-pdf-2-line red"></i>
            </div>
            <h4>Manual Updates</h4>
            <p>Manual changes made to employee attendance</p>
        </div>
        <a href="{{ route('manual_updates') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

</div>

            <div class="tab-content" id="employee">

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Employee Register</h4>
            <p>Download employee records with customized field options</p>
        </div>
        <a href="{{ route('employee_register') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Employee Addresses</h4>
            <p>Download list of all employee addresses in excel</p>
        </div>
        <a href="{{ route('employee_addresses') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
                <i class="ri-file-pdf-2-line red"></i>
            </div>
            <h4>Employee Events</h4>
            <p>List of birthdays, anniversaries in a period</p>
        </div>
        <a href="{{ route('employee_event') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Promotion Ageing</h4>
            <p>Identify employees due for promotion</p>
        </div>
        <a href="{{ route('promotion_ageing') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Increment Ageing</h4>
            <p>Identify employees due for increment</p>
        </div>
        <a href="{{ route('increment_ageing') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
                <i class="ri-file-pdf-2-line red"></i>
            </div>
            <h4>Employee Joinings</h4>
            <p>List of employees who joined in a date range</p>
        </div>
        <a href="{{ route('employee_joinings') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
                <i class="ri-file-pdf-2-line red"></i>
            </div>
            <h4>Employee Exits</h4>
            <p>List of employees who exited in a date range</p>
        </div>
        <a href="{{ route('employee_exits') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Workman Status</h4>
            <p>Workman install and version status</p>
        </div>
        <a href="{{ route('workman_status') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Employee Assets</h4>
            <p>View list of assets issued to employees</p>
        </div>
        <a href="{{ route('employee_assets') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Employee Relatives</h4>
            <p>View list of employee's relatives</p>
        </div>
        <a href="{{ route('employee_relatives') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
            </div>
            <h4>Inactive Employees</h4>

            <p>List of inactive employees</p>
        </div>
        <a href="{{ route('inactive_employees') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-excel-2-line green"></i>
            </div>
            <h4>Export Records</h4>
            <p>Download employee records excel for bulk update</p>
        </div>

        <a href="{{ route('export_records') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

</div>
           <div class="tab-content" id="statutory">

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>ESI Deduction</h4>
            <p>Employee salary and computed ESI deduction</p>
        </div>
        <a href="{{ route('esi_deduction') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
            </div>
            <h4>ESI Coverage</h4>
            <p>List of employees covered in ESI for a month</p>
        </div>
        <a href="{{ route('esi_coverage') }}" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
                <i class="ri-file-list-3-line dark"></i>
            </div>
            <h4>PF Deduction</h4>
            <p>Employee salary and computed PF deduction</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
            </div>
            <h4>PF Coverage</h4>
            <p>List of employees covered in PF for a month</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Professional Tax</h4>
            <p>Employee salary, state and computed Prof. Tax</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Labour Welfare Fund</h4>
            <p>Employee salary, state and deductions</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Overtime Register</h4>
            <p>Overtime register as per FORM XXIII, Rule 78(1)(a)(iii)</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Register of Leaves</h4>
            <p>Register or leaves as per FORM No. 18, Rule 94</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Attendance Register</h4>
            <p>Attendance Register as per FORM-D</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>IT Deduction</h4>
            <p>Summary of employee wise salary and computed tax</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>IT Declarations</h4>
            <p>List of declarations submitted by employees</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>IT Computation</h4>
            <p>Summary of annual tax computation</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>IT Form 16</h4>
            <p>FORM16 containing details of income and tax deductions</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-list-3-line dark"></i>
            </div>
            <h4>TDS Return</h4>
            <p>FORM 24Q for quarterly TDS return filing</p>
        </div>
        <a href="#" class="btn-open">Open <i class="ri-arrow-right-circle-line"></i></a>
    </div>

</div>

            <div class="tab-content" id="annual">

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-excel-2-fill excel"></i>
            </div>
            <h4>Annual Salary Summary</h4>
            <p>Multi-period salary and deductions at org. level</p>
        </div>
        <a href="#" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>Annual Salary Statement</h4>
            <p>Multi-period salary summary for a single employee</p>
        </div>
        <a href="#" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>Annual Attendance</h4>
            <p>Multi-period attendance register at employee level</p>
        </div>
        <a href="#" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-table-line blue"></i>
                <i class="ri-file-pdf-2-fill pdf"></i>
            </div>
            <h4>Annual Leaves</h4>
            <p>Multi-period leave register with employee level summary</p>
        </div>
        <a href="#" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

</div>

           <div class="tab-content" id="performance">
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-chart-line blue"></i>
            </div>
            <h4>Summary Report</h4>
            <p>Employee summary report with overall workforce insights.</p>
        </div>
        <a href="#" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>
</div>

         <div class="tab-content" id="other">

    <!-- Activity Logs -->
    <div class="cardx">
        <div class="card-content">
            <div class="report-icons">
                <i class="ri-file-list-3-line green"></i>
            </div>
            <h4>Activity Logs</h4>
            <p>View log of activities performed by all users.</p>
        </div>
        <a href="#" class="btn-open">
            Open <i class="ri-arrow-right-circle-line"></i>
        </a>
    </div>

</div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const menuItems = document.querySelectorAll(".menu li");
    const tabs = document.querySelectorAll(".tab-content");
    const search = document.getElementById("menuSearch");
    const clear = document.getElementById("clearSearch");

    menuItems.forEach(item => {
        item.addEventListener("click", function () {
            menuItems.forEach(i => i.classList.remove("active"));
            this.classList.add("active");

            tabs.forEach(tab => tab.classList.remove("active"));

            const tabId = this.getAttribute("data-tab");
            const activeTab = document.getElementById(tabId);

            if(activeTab){
                activeTab.classList.add("active");
            }
        });
    });

    search.addEventListener("input", function () {
        const value = this.value.toLowerCase();

        menuItems.forEach(item => {
            const text = item.textContent.toLowerCase();
            item.style.display = text.includes(value) ? "flex" : "none";
        });
    });

    clear.addEventListener("click", function () {
        search.value = "";
        menuItems.forEach(item => item.style.display = "flex");
        search.focus();
    });
});
</script>

@endsection