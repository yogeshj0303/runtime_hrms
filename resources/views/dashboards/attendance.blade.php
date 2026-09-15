@extends('layouts.master')
@section('title')
    Attendance Dashboard
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="row">
    <div class="col">
        <div class="h-100">
            <!-- Header & Filters -->
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <h4 class="fs-16 mb-1">Attendance</h4>
                            <p class="text-muted mb-0">Monitor Daily Attendance Status</p>
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <form action="{{ route('dashboards.attendance') }}" method="GET" class="d-flex align-items-center gap-2">
                                <select name="location_id" class="form-select w-auto">
                                    <option value="">All Locations</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ $locationId == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                                
                                <select name="department_id" class="form-select w-auto">
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $departmentId == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                                
                                <input type="date" name="date" class="form-control w-auto" value="{{ $dateFilter }}">
                                
                                <select name="top_x" class="form-select w-auto">
                                    <option value="10" {{ $topX == 10 ? 'selected' : '' }}>Top 10</option>
                                    <option value="20" {{ $topX == 20 ? 'selected' : '' }}>Top 20</option>
                                    <option value="50" {{ $topX == 50 ? 'selected' : '' }}>Top 50</option>
                                </select>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="ri-refresh-line align-middle"></i> Load
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="row">
                <!-- Attendance Overview -->
                <div class="col-xl-4 col-md-6">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1 text-center text-uppercase fs-13">ATTENDANCE</h4>
                        </div>
                        <div class="card-body">
                            <div id="attendance-pie-chart" data-colors='["#4b38b3", "#f7b84b", "#f06548", "#878a99", "#0ab39c"]' class="apex-charts" dir="ltr"></div>
                            
                            <div class="mt-3 text-center">
                                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                                    <div><i class="mdi mdi-circle text-primary me-1"></i> Presents ({{ $attendanceData[0] }})</div>
                                    <div><i class="mdi mdi-circle text-warning me-1"></i> Leaves ({{ $attendanceData[1] }})</div>
                                    <div><i class="mdi mdi-circle text-danger me-1"></i> Absents ({{ $attendanceData[2] }})</div>
                                </div>
                                <div class="d-flex justify-content-center align-items-center gap-3 mt-2">
                                    <div><i class="mdi mdi-circle text-muted me-1"></i> WeekOffs ({{ $attendanceData[3] }})</div>
                                    <div><i class="mdi mdi-circle text-success me-1"></i> Holidays ({{ $attendanceData[4] }})</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Late Comers -->
                <div class="col-xl-4 col-md-6">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1 text-center text-uppercase fs-13">LATE COMERS</h4>
                        </div>
                        <div class="card-body">
                            <div id="late-comers-chart" data-colors='["#4b38b3", "#f7b84b"]' class="apex-charts" dir="ltr"></div>
                            
                            <div class="mt-3 text-center">
                                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                                    <div><i class="mdi mdi-circle text-primary me-1"></i> On-Time ({{ $lateComersData[0] }})</div>
                                    <div><i class="mdi mdi-circle text-warning me-1"></i> Late Comers ({{ $lateComersData[1] }})</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Early Goers -->
                <div class="col-xl-4 col-md-6">
                    <div class="card card-height-100">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1 text-center text-uppercase fs-13">EARLY GOERS</h4>
                        </div>
                        <div class="card-body">
                            <div id="early-goers-chart" data-colors='["#4b38b3", "#f7b84b"]' class="apex-charts" dir="ltr"></div>
                            
                            <div class="mt-3 text-center">
                                <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
                                    <div><i class="mdi mdi-circle text-primary me-1"></i> On-Time ({{ $earlyGoersData[0] }})</div>
                                    <div><i class="mdi mdi-circle text-warning me-1"></i> Early Goers ({{ $earlyGoersData[1] }})</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end row -->

            <!-- Employee Lists Tabs Row -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex border-bottom-dashed">
                            <h4 class="card-title mb-0 flex-grow-1 text-uppercase fs-13">EMPLOYEE LISTS (Top {{ $topX }})</h4>
                        </div>
                        <div class="card-header border-0">
                            <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#tab_absentees" role="tab">Absentees</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_leaves" role="tab">On Leave/Week Off</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_late" role="tab">Late Comers</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#tab_early" role="tab">Early Goers</a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body p-0">
                            <div class="tab-content">
                                @php
                                $tabs = [
                                    ['id' => 'tab_absentees', 'active' => true, 'data' => $topAbsentees, 'status' => 'Absent', 'color' => 'danger'],
                                    ['id' => 'tab_leaves', 'active' => false, 'data' => $leavesList ?? [], 'status' => 'Leave/Off', 'color' => 'warning'],
                                    ['id' => 'tab_late', 'active' => false, 'data' => $lateComersList ?? [], 'status' => 'Late', 'color' => 'secondary'],
                                    ['id' => 'tab_early', 'active' => false, 'data' => $earlyGoersList ?? [], 'status' => 'Early', 'color' => 'primary'],
                                ];
                                @endphp

                                @foreach($tabs as $tab)
                                <div class="tab-pane {{ $tab['active'] ? 'active' : '' }}" id="{{ $tab['id'] }}" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-nowrap mb-0 align-middle">
                                            <thead class="text-muted table-light">
                                                <tr>
                                                    <th scope="col">EMPLOYEE</th>
                                                    <th scope="col">DEPUTATION</th>
                                                    <th scope="col">SHIFT</th>
                                                    <th scope="col">TIME IN/OUT</th>
                                                    <th scope="col">ATTENDANCE</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($tab['data'] as $emp)
                                                    @php
                                                        $workProfile = $emp->workProfiles->first();
                                                        $locationName = $workProfile && $workProfile->location ? $workProfile->location->name : 'N/A';
                                                        $departmentName = $workProfile && $workProfile->department ? $workProfile->department->name : 'N/A';
                                                        $shift = $emp->policy->shiftPolicy->defaultShift ?? null;
                                                        $shiftDetails = $shift ? $shift->name . ' (' . \Carbon\Carbon::parse($shift->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($shift->end_time)->format('H:i') . ')' : 'Standard Shift';
                                                    @endphp
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0 me-2">
                                                                <div class="avatar-sm">
                                                                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                        {{ substr($emp->first_name, 0, 1) }}{{ substr($emp->last_name, 0, 1) }}
                                                                    </span>
                                                                </div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h5 class="fs-13 mb-1"><a href="#" class="text-body">{{ $emp->first_name }} {{ $emp->last_name }}</a></h5>
                                                                <p class="text-muted mb-0">{{ $emp->employee_code }}</p>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <p class="mb-1 text-danger"><i class="ri-map-pin-line me-1"></i> {{ $locationName }}</p>
                                                        <p class="text-muted mb-0"><i class="ri-briefcase-line me-1"></i> {{ $departmentName }}</p>
                                                    </td>
                                                    <td>{{ $shiftDetails }}</td>
                                                    <td>{{ $emp->time_in ?? '-' }} / {{ $emp->time_out ?? '-' }}</td>
                                                    <td><span class="badge bg-{{ $tab['color'] }}-subtle text-{{ $tab['color'] }} px-2 py-1 fs-11">{{ $tab['status'] }}</span></td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="5" class="text-center text-muted">No records found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- end row -->

        </div>
    </div> <!-- end col -->
</div>

@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    
    <script>
        // Pie Chart Options function
        function getDonutOptions(seriesData, colors) {
            return {
                series: seriesData,
                chart: {
                    type: 'donut',
                    height: 250,
                },
                labels: [], // Labels managed manually below charts
                colors: colors,
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                dataLabels: {
                    enabled: false
                },
                legend: {
                    show: false
                },
                stroke: {
                    show: true,
                    colors: ['transparent']
                }
            };
        }

        // Attendance Pie Chart
        var attendanceData = @json($attendanceData);
        var attendanceColors = ["#4b38b3", "#f7b84b", "#f06548", "#878a99", "#0ab39c"]; // Presents, Leaves, Absents, WeekOffs, Holidays
        var attendanceChart = new ApexCharts(document.querySelector("#attendance-pie-chart"), getDonutOptions(attendanceData, attendanceColors));
        attendanceChart.render();

        // Late Comers Chart
        var lateComersData = @json($lateComersData);
        var timeColors = ["#4b38b3", "#f7b84b"]; // On-time, Late/Early
        var lateComersChart = new ApexCharts(document.querySelector("#late-comers-chart"), getDonutOptions(lateComersData, timeColors));
        lateComersChart.render();

        // Early Goers Chart
        var earlyGoersData = @json($earlyGoersData);
        var earlyGoersChart = new ApexCharts(document.querySelector("#early-goers-chart"), getDonutOptions(earlyGoersData, timeColors));
        earlyGoersChart.render();
    </script>
@endsection
