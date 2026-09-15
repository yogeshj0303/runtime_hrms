<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="/" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/pinghr.jpeg') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/pinghr-removebg-preview.png') }}" alt="" height="100">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="/" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/pinghr-removebg-preview.png') }}" alt="" height="50">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/pinghr-removebg-preview.png') }}" alt="" height="100">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div class="dropdown sidebar-user m-1 rounded">
        <button type="button" class="btn material-shadow-none" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
            <span class="d-flex align-items-center gap-2">
                <img class="rounded header-profile-user"
                    src="@if (Auth::user()->avatar != ''){{ URL::asset('images/' . Auth::user()->avatar) }}@else{{ URL::asset('build/images/users/avatar-1.jpg') }}@endif"
                    alt="Header Avatar">
                <span class="text-start">
                    <span class="d-block fw-medium sidebar-user-name-text">{{Auth::user()->name}}</span>
                    <span class="d-block fs-14 sidebar-user-name-sub-text"><i
                            class="ri ri-circle-fill fs-10 text-success align-baseline"></i> <span
                            class="align-middle">Online</span></span>
                </span>
            </span>
        </button>
        <div class="dropdown-menu dropdown-menu-end">
            <!-- item-->
            <h6 class="dropdown-header">Welcome {{Auth::user()->name}}!</h6>
            <a class="dropdown-item" href="pages-profile"><i
                    class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Profile</span></a>
            <a class="dropdown-item" href="apps-chat"><i
                    class="mdi mdi-message-text-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Messages</span></a>
            <a class="dropdown-item" href="apps-tasks-kanban"><i
                    class="mdi mdi-calendar-check-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Taskboard</span></a>
            <a class="dropdown-item" href="pages-faqs"><i
                    class="mdi mdi-lifebuoy text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Help</span></a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="pages-profile"><i
                    class="mdi mdi-wallet text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Balance :
                    <b>$5971.67</b></span></a>
            <a class="dropdown-item" href="pages-profile-settings"><span
                    class="badge bg-success-subtle text-success mt-1 float-end">New</span><i
                    class="mdi mdi-cog-outline text-muted fs-16 align-middle me-1"></i> <span
                    class="align-middle">Settings</span></a>
            <a class="dropdown-item" href="auth-lockscreen-basic"><i
                    class="mdi mdi-lock text-muted fs-16 align-middle me-1"></i> <span class="align-middle">Lock
                    screen</span></a>

            <a class="dropdown-item " href="javascript:void();"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                    class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i> <span
                    key="t-logout">@lang('translation.logout')</span></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">

    <!-- Dashboard -->
    <li class="nav-item">
        <a class="nav-link menu-link {{ request()->routeIs('business.dashboard') || request()->routeIs('dashboards.*') || request()->routeIs('org-chart') ? 'active' : '' }}" href="#sidebarDashboard"
            data-bs-toggle="collapse" role="button"
            aria-expanded="{{ request()->routeIs('business.dashboard') || request()->routeIs('dashboards.*') || request()->routeIs('org-chart') ? 'true' : 'false' }}"
            aria-controls="sidebarDashboard">
            <i class="ri-dashboard-line"></i>
            <span>Dashboards</span>
        </a>

        <div class="collapse menu-dropdown {{ request()->routeIs('business.dashboard') || request()->routeIs('dashboards.*') || request()->routeIs('org-chart') ? 'show' : '' }}" id="sidebarDashboard">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('business.dashboard') }}" class="nav-link {{ request()->routeIs('business.dashboard') ? 'active' : '' }}">
                        Overview
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('dashboards.attendance') }}" class="nav-link {{ request()->routeIs('dashboards.attendance') ? 'active' : '' }}">
                        Attendance
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('dashboards.flight-risk') }}" class="nav-link {{ request()->routeIs('dashboards.flight-risk') ? 'active' : '' }}">
                        Flight Risk
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('org-chart') }}" class="nav-link {{ request()->routeIs('org-chart') ? 'active' : '' }}">
                        Org Chart
                    </a>
                </li>
            </ul>
        </div>
    </li>



    <!-- Helpdesk Tickets -->
    <li class="nav-item">
        <a class="nav-link menu-link" href="#sidebarHelpdesk" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarHelpdesk">
            <i class="ri-customer-service-2-line"></i> 
            <span>Helpdesk</span>
        </a>
        <div class="collapse menu-dropdown" id="sidebarHelpdesk">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('tickets.index') }}" class="nav-link"> Tickets </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('tickets.access_requests') }}" class="nav-link"> Access Requests </a>
                </li>
            </ul>
        </div>
    </li>

    <!-- Employees -->
    <li class="nav-item">
        <a href="{{route('business.employee')}}" class="nav-link">
            <i class="ri-team-line"></i>
            <span>All Employees</span>
        </a>
    </li>

    <!-- Onboarding -->
    <li class="nav-item">
        <a href="{{ route('onboarding.index') }}" class="nav-link">
            <i class="ri-user-add-line"></i>
            <span>Onboarding</span>
        </a>
    </li>

    <!-- Separation -->
    <li class="nav-item">
        <a href="#" class="nav-link">
            <i class="ri-user-unfollow-line"></i>
            <span>Separation</span>
        </a>
    </li>

    <!-- Attendance -->
    <li class="nav-item">
        <a class="nav-link menu-link {{ request()->routeIs('attendance.*') && !request()->routeIs('dashboards.attendance') ? 'active' : '' }}"
            href="#sidebarAttendance"
            data-bs-toggle="collapse" role="button"
            aria-expanded="{{ request()->routeIs('attendance.*') && !request()->routeIs('dashboards.attendance') ? 'true' : 'false' }}"
            aria-controls="sidebarAttendance">

            <i class="ri-calendar-check-line"></i>
            <span>Attendance</span>
        </a>

        <div class="collapse menu-dropdown {{ request()->routeIs('attendance.*') && !request()->routeIs('dashboards.attendance') ? 'show' : '' }}" id="sidebarAttendance">
            <ul class="nav nav-sm flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboards.attendance') }}" class="nav-link {{ request()->routeIs('dashboards.attendance') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('attendance.daily-punches') }}" class="nav-link">
                        Daily Punches
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('attendance.daily') }}" class="nav-link">
                        Daily Attendance
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('attendance.monthly') }}" class="nav-link">
                        Monthly Attendance
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('attendance.manual') }}" class="nav-link">
                        Manual Attendance
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        Leave Correction
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('attendance.shift-roster') }}" class="nav-link {{ request()->routeIs('attendance.shift-roster') ? 'active' : '' }}">
                        Shift Roster
                    </a>
                </li>

            </ul>

        </div>

    </li>


<!-- DATA CAPTURE -->
<li class="nav-item">
    <a class="nav-link menu-link" href="#sidebarDataCapture" data-bs-toggle="collapse">
        <i class="ri-database-2-line"></i>
        <span>Data Capture</span>
    </a>

    <div class="collapse menu-dropdown" id="sidebarDataCapture">
        <ul class="nav nav-sm flex-column">

            <li class="nav-item"><a href="{{ route('capture.salaryvariable') }}" class="nav-link {{ request()->routeIs('capture.salaryvariable') ? 'active' : '' }}">Salary - Variable</a></li>
            <li class="nav-item"><a href="{{ route('capture.salaryunits') }}" class="nav-link {{ request()->routeIs('capture.salaryunits') ? 'active' : '' }}">Salary - Units</a></li>
            <li class="nav-item"><a href="{{ route('capture.deductionvariable') }}" class="nav-link {{ request()->routeIs('capture.deductionvariable') ? 'active' : '' }}">Deductions</a></li>

            <li class="nav-item"><a href="{{ route('capture.tds') }}" class="nav-link {{ request()->routeIs('capture.tds') ? 'active' : '' }}">Income Tax (TDS)</a></li>
            <li class="nav-item"><a href="{{ route('capture.extradays') }}" class="nav-link {{ request()->routeIs('capture.extradays') ? 'active' : '' }}">Extra Days</a></li>
            <li class="nav-item"><a href="{{ route('capture.extrahours') }}" class="nav-link {{ request()->routeIs('capture.extrahours') ? 'active' : '' }}">Extra Hours</a></li>
            <li class="nav-item"><a href="{{ route('capture.othours') }}" class="nav-link {{ request()->routeIs('capture.othours') ? 'active' : '' }}">OT Hours</a></li>
            <li class="nav-item"><a href="{{ route('capture.loans') }}" class="nav-link {{ request()->routeIs('capture.loans') ? 'active' : '' }}">Loans</a></li>
            <li class="nav-item"><a href="{{ route('capture.itdeclarations') }}" class="nav-link {{ request()->routeIs('capture.itdeclarations') ? 'active' : '' }}">IT Declarations</a></li>
            <li class="nav-item"><a href="{{ route('capture.itexemptions') }}" class="nav-link {{ request()->routeIs('capture.itexemptions') ? 'active' : '' }}">IT Exemptions</a></li>
            <li class="nav-item"><a href="{{ route('capture.tdschallans') }}" class="nav-link {{ request()->routeIs('capture.tdschallans') ? 'active' : '' }}">TDS Challans</a></li>
            <li class="nav-item"><a href="{{ route('capture.tdsreturns') }}" class="nav-link {{ request()->routeIs('capture.tdsreturns') ? 'active' : '' }}">TDS Returns</a></li>

        </ul>
    </div>
</li>

<!-- BULK UPDATES -->
<li class="nav-item">
    <a class="nav-link menu-link {{ request()->routeIs('bulk-upload') || request()->routeIs('bulk-upload-address') || request()->routeIs('bulk-upload-bank') || request()->routeIs('bulk-upload-work-profile') ? 'active' : '' }}" href="#sidebarBulkUpdates" data-bs-toggle="collapse"
       aria-expanded="{{ request()->routeIs('bulk-upload') || request()->routeIs('bulk-upload-address') || request()->routeIs('bulk-upload-bank') || request()->routeIs('bulk-upload-work-profile') ? 'true' : 'false' }}">
        <i class="ri-upload-cloud-line"></i>
        <span>Bulk Updates</span>
    </a>

    <div class="collapse menu-dropdown {{ request()->routeIs('bulk-upload') || request()->routeIs('bulk-upload-address') || request()->routeIs('bulk-upload-bank') || request()->routeIs('bulk-upload-work-profile') ? 'show' : '' }}" id="sidebarBulkUpdates">
        <ul class="nav nav-sm flex-column">

            <li class="nav-item"><a href="{{ route('bulk-upload') }}" class="nav-link {{ request()->routeIs('bulk-upload') ? 'active' : '' }}">Employee Import</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Employee Options</a></li>
            <li class="nav-item"><a href="{{ route('bulk-upload-address') }}" class="nav-link {{ request()->routeIs('bulk-upload-address') ? 'active' : '' }}">Employee Address</a></li>
            <li class="nav-item"><a href="{{ route('bulk-upload-bank') }}" class="nav-link {{ request()->routeIs('bulk-upload-bank') ? 'active' : '' }}">Bank Details</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Salary</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Deductions</a></li>
            <li class="nav-item"><a href="{{ route('bulk-upload-work-profile') }}" class="nav-link {{ request()->routeIs('bulk-upload-work-profile') ? 'active' : '' }}">Work Profile</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Biometric Codes</a></li>

        </ul>
    </div>
</li>

<!-- HR MANAGEMENT -->
<li class="nav-item">
    <a class="nav-link menu-link {{ request()->routeIs('wall.index') || request()->routeIs('hr.*') ? 'active' : '' }}" href="#sidebarHRManagement" data-bs-toggle="collapse"
       aria-expanded="{{ request()->routeIs('wall.index') || request()->routeIs('hr.*') ? 'true' : 'false' }}">
        <i class="ri-briefcase-line"></i>
        <span>HR Management</span>
    </a>

    <div class="collapse menu-dropdown {{ request()->routeIs('wall.index') || request()->routeIs('hr.*') ? 'show' : '' }}" id="sidebarHRManagement">
        <ul class="nav nav-sm flex-column">

            <li class="nav-item">
                <a class="nav-link menu-link" href="#sidebarHRRequests" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarHRRequests">
                    Requests
                </a>
                <div class="collapse menu-dropdown" id="sidebarHRRequests">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="{{ route('hr.requests.attendance') }}" class="nav-link">Attendance Requests</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hr.requests.leave') }}" class="nav-link">Leave Requests</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('hr.requests.helpdesk') }}" class="nav-link">Helpdesk Requests</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item"><a href="{{ route('wall.index') }}" class="nav-link {{ request()->routeIs('wall.index') ? 'active' : '' }}">Greetings</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Notifications</a></li>
            <li class="nav-item"><a href="{{ route('hr.policies') }}" class="nav-link">Policies</a></li>
            <li class="nav-item"><a href="{{ route('hr.letters.index') }}" class="nav-link {{ request()->routeIs('hr.letters.*') ? 'active' : '' }}">Letters</a></li>
            <li class="nav-item"><a href="{{ route('alerts.dashboard') }}" class="nav-link {{ request()->routeIs('alerts.*') ? 'active' : '' }}">Alerts</a></li>
            <li class="nav-item"><a href="{{ route('hr.alerts.broadcast') }}" class="nav-link {{ request()->routeIs('hr.alerts.broadcast') ? 'active' : '' }}">Broadcast Alert</a></li>
            <li class="nav-item"><a href="#" class="nav-link">BG Checks</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Confirmations</a></li>

        </ul>
    </div>
</li>

<!-- REWARDS -->
<li class="nav-item">
    <a class="nav-link menu-link" href="#sidebarRewards" data-bs-toggle="collapse">
        <i class="ri-medal-line"></i>
        <span>Rewards</span>
    </a>

    <div class="collapse menu-dropdown" id="sidebarRewards">
        <ul class="nav nav-sm flex-column">

            <li class="nav-item"><a href="#" class="nav-link">Leaderboard</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Badges</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Gifts</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Cheers</a></li>

        </ul>
    </div>
</li>

<!-- PERFORMANCE -->
<li class="nav-item">
    <a class="nav-link menu-link" href="#sidebarPerformance" data-bs-toggle="collapse">
        <i class="ri-line-chart-line"></i>
        <span>Performance</span>
    </a>

    <div class="collapse menu-dropdown" id="sidebarPerformance">
        <ul class="nav nav-sm flex-column">

            <li class="nav-item"><a href="#" class="nav-link">Dashboard</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Manage Ratings</a></li>
            <li class="nav-item"><a href="#" class="nav-link">KRA Library</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Goals Library</a></li>
            <li class="nav-item"><a href="#" class="nav-link">360° Feedback</a></li>
            <li class="nav-item"><a href="#" class="nav-link">Appraisal Periods</a></li>

        </ul>
    </div>
</li>

  <!-- REPORTS -->
    <li class="nav-item">
        <a href="{{ route('report') }}" class="nav-link {{ request()->routeIs('report') ? 'active' : '' }}">
            <i class="ri-bar-chart-line"></i>
            <span>Reports</span>
        </a>
    </li>

    <!-- SETUP -->
    <li class="nav-item">
        <a href="{{ route('setup') }}" class="nav-link">
            <i class="ri-settings-3-line"></i>
            <span>Setup</span>
        </a>
    </li>

    

</ul>
            <!-- <ul class="navbar-nav" id="navbar-nav"> -->
                <!-- <li class="menu-title"><span>@lang('translation.menu')</span></li> -->
             

              
                <!-- <li class="nav-item">

                    <a class="nav-link menu-link" href="{{ route('root') }}">
                        <i class="ri-dashboard-2-line"></i>

                        <span>
                            Dashboard
                        </span>
                    </a>

                </li> -->
<!-- <li class="nav-item">
    <a class="nav-link menu-link" href="{{ route('setup') }}">
        <i class="ri-settings-3-line"></i>
        <span>Setup</span>
    </a>
</li> -->
            <!-- </ul> -->
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>