@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="row">
    <div class="col">

        <div class="h-100">

    <!-- Welcome -->
    <div class="row mb-4">

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold">EMPLOYEES</h4>
                    <i class="ri-team-fill text-primary fs-1"></i>
                </div>

                <div class="progress mt-3" style="height:8px;">
                    <div class="progress-bar bg-primary" style="width:{{ $totalEmployees > 0 ? ($activeEmployees / $totalEmployees) * 100 : 0 }}%"></div>
                </div>

                <div class="mt-3">
                    {{ $activeEmployees }} of {{ $totalEmployees }} active ({{ $inactiveEmployees }} inactive) | Max: {{ $maxEmployees }}
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">

                <div class="d-flex justify-content-between">
                    <h4 class="fw-bold">ACTIVE MOBILE USERS</h4>
                    <i class="ri-smartphone-fill text-success fs-1"></i>
                </div>

                <div class="progress mt-3" style="height:8px;">
                    <div class="progress-bar bg-success" style="width:{{ $totalEmployees > 0 ? ($activeMobileUsers / $totalEmployees) * 100 : 0 }}%"></div>
                </div>

                <div class="mt-3">
                    {{ $activeMobileUsers }} of {{ $totalEmployees }} (with mobile access)
                </div>

            </div>
        </div>
    </div>

</div>

    <!-- Top Cards -->
    <div class="row">

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Employees</h5>
                    <h2 class="text-primary">{{ $totalEmployees }}</h2>
                    <small>{{ $activeEmployees }} Active / Max Limit: {{ $maxEmployees }}</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Active Mobile Users</h5>
                    <h2 class="text-success">{{ $activeMobileUsers }}</h2>
                    <small>With Mobile Access</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Subscription Status</h5>
                    <h2 class="text-warning">{{ $subscriptionValidity }}</h2>
                    <small>Due Date: {{ $subscriptionDueDate }}</small>
                </div>
            </div>
        </div>

    </div>

    <!-- Attendance Chart -->
    <div class="row">

        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Attendance Trend</h4>
                    <select id="attendance-filter" class="form-select w-auto">
                        <option value="today" {{ $filter == 'today' ? 'selected' : '' }}>Today</option>
                        <option value="week" {{ $filter == 'week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $filter == 'month' ? 'selected' : '' }}>This Month</option>
                        <option value="year" {{ $filter == 'year' ? 'selected' : '' }}>This Year</option>
                    </select>
                </div>

                <div class="card-body">
                    <div id="attendance-chart" style="height:350px;"></div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bottom Section -->
    <div class="row">

        <div class="col-md-4">

            <div class="card">
                <div class="card-header">
                    <h5>Open Requests</h5>
                </div>

                <div class="card-body">

                    <a href="{{ route('hr.requests.attendance') }}" class="d-flex justify-content-between mb-3 text-decoration-none text-dark">
                        <span>Missed Punches</span>
                        <span class="badge bg-danger">{{ $missedPunches }}</span>
                    </a>

                    <a href="{{ route('hr.requests.helpdesk') }}" class="d-flex justify-content-between mb-3 text-decoration-none text-dark">
                        <span>Help Desk</span>
                        <span class="badge bg-info">{{ $helpdeskRequests }}</span>
                    </a>

                    <a href="{{ route('hr.requests.leave') }}" class="d-flex justify-content-between mb-3 text-decoration-none text-dark">
                        <span>Leaves</span>
                        <span class="badge bg-primary">{{ $pendingLeaves }}</span>
                    </a>

                </div>
            </div>

        </div>

        <div class="col-md-8">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Events</h5>
                    <a href="{{ route('employee_event') }}" class="btn btn-sm btn-outline-primary">Open Report</a>
                </div>

                <div class="card-body">

                    <ul class="list-group">
                        @forelse($upcomingEvents as $event)
                        <li class="list-group-item d-flex justify-content-between">
                            {{ $event['name'] }} ({{ $event['type'] }})
                            <span>{{ \Carbon\Carbon::parse($event['date'])->format('d M') }}</span>
                        </li>
                        @empty
                        <li class="list-group-item">No upcoming events this month.</li>
                        @endforelse
                    </ul>

                </div>
            </div>

        </div>

    </div>

</div>

    </div> <!-- end col -->

    
</div>

@endsection
@section('script')
    <!-- apexcharts -->
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <!-- dashboard init -->
    <script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>
    <script>
var options = {
    series: [{
        name: 'Present',
        data: @json($presentData)
    },{
        name: 'Absent',
        data: @json($absentData)
    },{
        name: 'Half Day',
        data: @json($halfDayData)
    },{
        name: 'Holiday',
        data: @json($holidayData)
    },{
        name: 'Week Off',
        data: @json($weekOffData)
    }],
    colors: ['#0ab39c', '#f06548', '#f7b84b', '#299cdb', '#878a99'], // Green, Red, Yellow, Blue, Gray
    chart: {
        type: 'bar',
        height: 350,
        stacked: true
    },
    dataLabels: {
        enabled: true,
        formatter: function (val) {
            return val + "%";
        }
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val + "%"
            }
        }
    },
    xaxis: {
        categories: @json($chartLabels)
    }
};

var chart = new ApexCharts(
    document.querySelector("#attendance-chart"),
    options
);

chart.render();

document.getElementById('attendance-filter').addEventListener('change', function() {
    var filter = this.value;
    var url = new URL(window.location.href);
    url.searchParams.set('chart_filter', filter);
    window.location.href = url.href;
});
</script>
@endsection
