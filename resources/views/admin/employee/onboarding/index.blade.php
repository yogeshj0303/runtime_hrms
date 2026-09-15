@extends('layouts.master')

@section('title')
    Employee Onboarding Dashboard
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
<style>
    .metric-card {
        transition: transform 0.2s;
    }
    .metric-card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header mb-4">
    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Onboarding Dashboard</span>
    </div>

    <div class="header-content d-flex justify-content-between align-items-center">
        <div class="header-left">
            <h4>Onboarding Analytics</h4>
            <p class="text-muted mb-0">Overview of the hiring and onboarding pipeline.</p>
        </div>

        <div class="header-right d-flex flex-column align-items-end gap-2">
            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <a href="{{ route('onboarding.create') }}" class="btn btn-sm text-white" style="background-color: #162d50;">
                    <i class="ri-user-add-line align-middle me-1"></i> New Employee
                </a>
                <a href="{{ route('onboarding.forms.create') }}" class="btn btn-sm text-white" style="background-color: #162d50;">
                    <i class="ri-file-add-line align-middle me-1"></i> New Onboarding
                </a>
                <a href="#" class="btn btn-sm text-white" style="background-color: #162d50;">
                    <i class="ri-file-list-3-line align-middle me-1"></i> Onboarding Forms
                </a>
                <div class="dropdown">
                    <button class="btn btn-sm text-white dropdown-toggle" type="button" data-bs-toggle="dropdown" style="background-color: #162d50;">
                        More
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="ri-group-line text-muted me-2 align-bottom"></i> Bulk Onboarding</a></li>
                        <li><a class="dropdown-item" href="#"><i class="ri-file-text-line text-muted me-2 align-bottom"></i> Dynamic Letter</a></li>
                        <li><a class="dropdown-item" href="#"><i class="ri-check-double-line text-muted me-2 align-bottom"></i> Approve Additional</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#"><i class="ri-settings-3-line text-muted me-2 align-bottom"></i> Onboarding Settings</a></li>
                    </ul>
                </div>
                <button class="btn btn-success btn-sm px-3 rounded-pill" style="background-color: #0ab39c; border-color: #0ab39c;">
                    <i class="ri-question-line align-middle me-1"></i> Read Help
                </button>
            </div>
            
            <!-- Date Range Filter -->
            <form action="{{ route('onboarding.index') }}" method="GET" class="d-flex align-items-center gap-2 bg-white p-1 px-2 rounded border mt-1">
                <input type="date" name="start_date" class="form-control form-control-sm border-0" value="{{ $startDate }}">
                <span class="text-muted fs-12">to</span>
                <input type="date" name="end_date" class="form-control form-control-sm border-0" value="{{ $endDate }}">
                <button type="submit" class="btn btn-sm btn-primary py-0 px-2"><i class="ri-search-line"></i></button>
            </form>
        </div>
    </div>
</div>

<!-- Metrics Row -->
<div class="row mb-4">
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-primary-subtle text-primary rounded p-3 me-3">
                    <i class="ri-file-list-3-line fs-24"></i>
                </div>
                <div>
                    <h5 class="fs-18 mb-1">{{ $totalForms }}</h5>
                    <p class="text-muted mb-0">Total Forms</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-info-subtle text-info rounded p-3 me-3">
                    <i class="ri-mail-send-line fs-24"></i>
                </div>
                <div>
                    <h5 class="fs-18 mb-1">{{ $offersSent }}</h5>
                    <p class="text-muted mb-0">Offers Sent</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-warning-subtle text-warning rounded p-3 me-3">
                    <i class="ri-time-line fs-24"></i>
                </div>
                <div>
                    <h5 class="fs-18 mb-1">{{ $inProgress }}</h5>
                    <p class="text-muted mb-0">In Progress (Draft)</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-sm-6">
        <div class="card metric-card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="flex-shrink-0 bg-success-subtle text-success rounded p-3 me-3">
                    <i class="ri-user-follow-line fs-24"></i>
                </div>
                <div>
                    <h5 class="fs-18 mb-1">{{ $totalHires }}</h5>
                    <p class="text-muted mb-0">Total Hires (Approved)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Onboarding Overview (Donut) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="card-title mb-0 fs-14 fw-semibold text-muted"><i class="ri-pie-chart-2-line me-1 text-primary"></i> Onboarding Overview</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                @if(array_sum($completionData) > 0)
                    <div id="onboardingOverviewChart" data-colors='["#0ab39c", "#299cdb", "#f7b84b"]'></div>
                @else
                    <div class="text-center text-muted">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                0%
                            </div>
                        </div>
                        <p class="fs-12">No data available.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Onboarding Forms List -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0 fs-14 fw-semibold text-muted"><i class="ri-file-list-3-line me-1 text-warning"></i> Onboarding Forms</h6>
            </div>
            <div class="card-body p-0 mt-3" style="max-height: 250px; overflow-y: auto;">
                <ul class="list-group list-group-flush">
                    @forelse($forms->take(5) as $form)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-4 py-3">
                            <div>
                                <h6 class="fs-13 mb-1 text-dark fw-semibold">{{ $form->form_name ?? ($form->employee->first_name . ' ' . $form->employee->last_name) }}</h6>
                                <p class="fs-11 text-muted mb-0">Created: {{ $form->created_at->format('d-M-Y') }}</p>
                            </div>
                            <div>
                                @if($form->status == 'Approved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Approved</span>
                                @elseif($form->status == 'Sent')
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">Sent</span>
                                @else
                                    <span class="badge bg-light text-secondary border px-2 py-1">Draft</span>
                                @endif
                            </div>
                        </li>
                    @empty
                        <li class="list-group-item text-center py-4 border-0">
                            <i class="ri-file-text-line fs-24 text-light mb-2 d-block"></i>
                            <span class="text-muted fs-12">No recent forms found.</span>
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    <!-- Hires by Department (Pie) -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0">
                <h6 class="card-title mb-0 fs-14 fw-semibold text-muted"><i class="ri-group-line me-1 text-info"></i> Hires by Department</h6>
            </div>
            <div class="card-body d-flex justify-content-center align-items-center">
                @if(count($deptCounts) > 0)
                    <div id="hiresByDeptChart" data-colors='["#162d50", "#0ab39c", "#f7b84b", "#878a99", "#299cdb"]'></div>
                @else
                    <div class="text-center text-muted">
                        <i class="ri-pie-chart-fill fs-1 display-4 text-light mb-3 d-block"></i>
                        <p class="fs-12">No department data.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Trend Chart Row -->
<div class="row mb-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom-0 pt-3 pb-0 text-center">
                <h6 class="card-title mb-0 fs-13 fw-semibold text-muted">Hiring Trend (Last 15 Months)</h6>
            </div>
            <div class="card-body">
                @if(array_sum($trendCounts) > 0)
                    <div id="hiringTrendChart" data-colors='["#299cdb"]'></div>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="ri-line-chart-line fs-1 display-4 text-light mb-3 d-block"></i>
                        <p class="fs-13">No hiring activity in the selected period.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Data Table Section -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="card-title mb-0 fs-15 fw-semibold text-dark">All Onboarding Forms</h5>
    </div>
    
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table id="formsTable" class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Form Name</th>
                        <th>Employee</th>
                        <th>Employee Code</th>
                        <th>Date Created</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($forms as $index => $form)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $form->form_name }}</strong></td>
                        <td>
                            {{ $form->employee->first_name }} {{ $form->employee->last_name }}
                            <br><small class="text-muted">{{ $form->employee->email }}</small>
                        </td>
                        <td>{{ $form->employee->employee_code }}</td>
                        <td>{{ $form->created_at->format('d-M-Y') }}</td>
                        <td>
                            @if($form->status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($form->status == 'Sent')
                                <span class="badge bg-info">Sent</span>
                            @else
                                <span class="badge bg-warning text-dark">Draft</span>
                            @endif
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-soft-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                    Options
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('employee.profile.summary', ['id' => $form->employee_id]) }}" class="dropdown-item">
                                            <i class="ri-eye-line me-2"></i> View Profile
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No onboarding forms found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
    $(document).ready(function() {
        $('#formsTable').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search forms..."
            }
        });

        // 1. Onboarding Overview Donut Chart
        if (document.getElementById("onboardingOverviewChart")) {
            var overviewColors = JSON.parse(document.getElementById("onboardingOverviewChart").getAttribute("data-colors"));
            var totalCount = {!! array_sum($completionData) !!};
            var percentComplete = totalCount > 0 ? Math.round(({!! $completionData[0] !!} / totalCount) * 100) : 0;
            
            var overviewOptions = {
                series: {!! json_encode($completionData) !!},
                chart: {
                    type: 'donut',
                    height: 250
                },
                labels: ['Approved', 'Sent', 'Draft'],
                colors: overviewColors,
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                            labels: {
                                show: true,
                                name: { show: false },
                                value: {
                                    show: true,
                                    fontSize: '24px',
                                    fontWeight: 'bold',
                                    color: '#405189',
                                    formatter: function (val) {
                                        return percentComplete + "%";
                                    }
                                },
                                total: {
                                    show: true,
                                    showAlways: true,
                                    label: 'Completion',
                                    formatter: function (w) {
                                        return percentComplete + "%";
                                    }
                                }
                            }
                        }
                    }
                },
                stroke: { width: 0 },
                legend: { show: false },
                dataLabels: { enabled: false }
            };
            var overviewChart = new ApexCharts(document.querySelector("#onboardingOverviewChart"), overviewOptions);
            overviewChart.render();
        }

        // 2. Hires by Department Pie Chart
        if (document.getElementById("hiresByDeptChart")) {
            var deptColors = JSON.parse(document.getElementById("hiresByDeptChart").getAttribute("data-colors"));
            var deptNames = {!! json_encode($deptNames) !!};
            var deptCounts = {!! json_encode($deptCounts) !!};
            
            var deptOptions = {
                series: deptCounts,
                chart: {
                    type: 'pie',
                    height: 220
                },
                labels: deptNames,
                colors: deptColors,
                legend: { show: false },
                stroke: { width: 0 },
                dataLabels: { enabled: false }
            };
            var deptChart = new ApexCharts(document.querySelector("#hiresByDeptChart"), deptOptions);
            deptChart.render();
        }

        // 3. Hiring Trend Line Chart
        if (document.getElementById("hiringTrendChart")) {
            var trendColors = JSON.parse(document.getElementById("hiringTrendChart").getAttribute("data-colors"));
            var trendOptions = {
                series: [{
                    name: 'New Joinings',
                    data: {!! json_encode($trendCounts) !!}
                }],
                chart: {
                    type: 'line',
                    height: 250,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },
                stroke: {
                    curve: 'straight',
                    width: 2
                },
                markers: {
                    size: 4,
                    colors: trendColors,
                    strokeColors: '#fff',
                    strokeWidth: 2,
                    hover: { size: 6 }
                },
                colors: trendColors,
                dataLabels: { enabled: false },
                xaxis: {
                    categories: {!! json_encode($trendMonths) !!},
                    labels: { style: { colors: '#878a99', fontSize: '10px' } },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    min: 0,
                    tickAmount: 4,
                    labels: { style: { colors: '#878a99', fontSize: '10px' } }
                },
                grid: {
                    borderColor: '#f0f2f7',
                    strokeDashArray: 3
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'center'
                }
            };
            var trendChart = new ApexCharts(document.querySelector("#hiringTrendChart"), trendOptions);
            trendChart.render();
        }
    });
</script>
@endsection
