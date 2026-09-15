@extends('layouts.master')

@section('title')
    Separation Dashboard
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<style>
    .metric-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 6px rgba(0,0,0,0.04);
    }
    .metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    .icon-box {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header mb-4">
    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Separation & Offboarding</span>
    </div>

    <div class="header-content d-flex justify-content-between align-items-center">
        <div class="header-left">
            <h4>Separation Analytics</h4>
            <p class="text-muted mb-0">Monitor employee exits, tenure, and retention efforts.</p>
        </div>

        <div class="header-right d-flex gap-2">
            <form action="{{ route('separation.dashboard') }}" method="GET" class="d-flex gap-2 align-items-center bg-white p-2 rounded border shadow-sm">
                <input type="date" name="start_date" class="form-control form-control-sm" value="{{ $startDate }}">
                <span>to</span>
                <input type="date" name="end_date" class="form-control form-control-sm" value="{{ $endDate }}">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
            </form>
            <a href="#" class="btn btn-primary d-flex align-items-center shadow-sm">
                <i class="ri-user-unfollow-line me-2"></i> Initiate Exit
            </a>
        </div>
    </div>
</div>

<!-- Key Metrics Row -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card metric-card h-100 bg-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Total Exits</p>
                        <h3 class="mb-0">{{ $totalExits }}</h3>
                    </div>
                    <div class="icon-box bg-danger-subtle text-danger">
                        <i class="ri-logout-box-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3 mb-xl-0">
        <div class="card metric-card h-100 bg-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Avg Tenure (Months)</p>
                        <h3 class="mb-0">{{ $avgTenureMonths }}</h3>
                    </div>
                    <div class="icon-box bg-info-subtle text-info">
                        <i class="ri-time-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-3 mb-md-0">
        <div class="card metric-card h-100 bg-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Retention Attempts</p>
                        <h3 class="mb-0">{{ $retentionPercentage }}%</h3>
                    </div>
                    <div class="icon-box bg-success-subtle text-success">
                        <i class="ri-shield-user-line"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card metric-card h-100 bg-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <p class="text-muted fw-medium mb-2">Pre-Confirmation Exits</p>
                        <h3 class="mb-0">{{ $preConfirmationExits }}</h3>
                    </div>
                    <div class="icon-box bg-warning-subtle text-warning">
                        <i class="ri-error-warning-line"></i>
                    </div>
                </div>
                <small class="text-muted d-block mt-2">Exits within first 6 months</small>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 1 -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h6 class="mb-0">Monthly Exit Trend (Last 6 Months)</h6>
            </div>
            <div class="card-body">
                <div id="trendChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h6 class="mb-0">Top Exit Reasons</h6>
            </div>
            <div class="card-body">
                <div id="reasonsChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row 2 -->
<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h6 class="mb-0">Tenure Analysis</h6>
            </div>
            <div class="card-body">
                <div id="tenureChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                <h6 class="mb-0">Exits by Department</h6>
            </div>
            <div class="card-body">
                <div id="deptChart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Data Table Row -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom pt-4 pb-3">
        <h6 class="mb-0">Recently Logged & Pending Exits</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="separationsTable" class="table table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>Department</th>
                        <th>Exit Date</th>
                        <th>Reason</th>
                        <th>Retention Attempted</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($separations as $sep)
                    <tr>
                        <td>
                            <strong>{{ $sep->employee->first_name }} {{ $sep->employee->last_name }}</strong><br>
                            <small class="text-muted">{{ $sep->employee->employee_code }}</small>
                        </td>
                        <td>{{ $sep->employee->department ?? 'N/A' }}</td>
                        <td>{{ $sep->exit_date->format('d M, Y') }}</td>
                        <td>{{ $sep->exit_reason }}</td>
                        <td>
                            @if($sep->retention_attempted)
                                <span class="badge bg-success-subtle text-success">Yes</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($sep->status == 'Approved')
                                <span class="badge bg-success">Approved</span>
                            @elseif($sep->status == 'Rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-sm btn-soft-primary"><i class="ri-eye-line"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">No separation records found in this period.</td>
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
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#separationsTable').DataTable({
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search exits..."
            },
            order: [[2, 'desc']] // Sort by Exit Date by default
        });

        // Setup ApexCharts theme colors
        const colors = ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6610f2'];

        // 1. Trend Chart (Bar)
        const trendMonths = @json($trendMonths);
        const trendCounts = @json($trendCounts);
        
        var trendOptions = {
            series: [{
                name: 'Exits',
                data: trendCounts
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false }
            },
            colors: ['#dc3545'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    columnWidth: '40%',
                }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: trendMonths,
            }
        };
        var trendChart = new ApexCharts(document.querySelector("#trendChart"), trendOptions);
        trendChart.render();

        // 2. Reasons Chart (Pie)
        const reasonLabels = @json($reasonLabels);
        const reasonSeries = @json($reasonSeries);
        
        var reasonOptions = {
            series: reasonSeries,
            chart: {
                type: 'pie',
                height: 300,
            },
            labels: reasonLabels,
            colors: colors,
            legend: { position: 'bottom' }
        };
        var reasonChart = new ApexCharts(document.querySelector("#reasonsChart"), reasonOptions);
        if(reasonSeries.length > 0) reasonChart.render();

        // 3. Tenure Analysis (Bar/Column)
        const tenureLabels = @json($tenureLabels);
        const tenureSeries = @json($tenureSeries);
        
        var tenureOptions = {
            series: [{
                name: 'Employees',
                data: tenureSeries
            }],
            chart: {
                type: 'bar',
                height: 300,
                toolbar: { show: false }
            },
            colors: ['#ffc107'],
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: true,
                }
            },
            dataLabels: { enabled: true },
            xaxis: {
                categories: tenureLabels,
            }
        };
        var tenureChart = new ApexCharts(document.querySelector("#tenureChart"), tenureOptions);
        tenureChart.render();

        // 4. Exits by Department (Donut)
        const deptLabels = @json($deptLabels);
        const deptSeries = @json($deptSeries);
        
        var deptOptions = {
            series: deptSeries,
            chart: {
                type: 'donut',
                height: 300,
            },
            labels: deptLabels,
            colors: ['#0dcaf0', '#0d6efd', '#6610f2', '#198754'],
            legend: { position: 'bottom' }
        };
        var deptChart = new ApexCharts(document.querySelector("#deptChart"), deptOptions);
        if(deptSeries.length > 0) deptChart.render();
    });
</script>
@endsection
