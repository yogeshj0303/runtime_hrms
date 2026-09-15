<?php $__env->startSection('title'); ?>
    <?php echo app('translator')->get('translation.dashboards'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link href="<?php echo e(URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.css')); ?>" rel="stylesheet" type="text/css" />
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

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
                    <div class="progress-bar bg-primary" style="width:<?php echo e($totalEmployees > 0 ? ($activeEmployees / $totalEmployees) * 100 : 0); ?>%"></div>
                </div>

                <div class="mt-3">
                    <?php echo e($activeEmployees); ?> of <?php echo e($totalEmployees); ?> active (<?php echo e($inactiveEmployees); ?> inactive) | Max: <?php echo e($maxEmployees); ?>

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
                    <div class="progress-bar bg-success" style="width:<?php echo e($totalEmployees > 0 ? ($activeMobileUsers / $totalEmployees) * 100 : 0); ?>%"></div>
                </div>

                <div class="mt-3">
                    <?php echo e($activeMobileUsers); ?> of <?php echo e($totalEmployees); ?> (with mobile access)
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
                    <h2 class="text-primary"><?php echo e($totalEmployees); ?></h2>
                    <small><?php echo e($activeEmployees); ?> Active / Max Limit: <?php echo e($maxEmployees); ?></small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Active Mobile Users</h5>
                    <h2 class="text-success"><?php echo e($activeMobileUsers); ?></h2>
                    <small>With Mobile Access</small>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Subscription Status</h5>
                    <h2 class="text-warning"><?php echo e($subscriptionValidity); ?></h2>
                    <small>Due Date: <?php echo e($subscriptionDueDate); ?></small>
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
                        <option value="today" <?php echo e($filter == 'today' ? 'selected' : ''); ?>>Today</option>
                        <option value="week" <?php echo e($filter == 'week' ? 'selected' : ''); ?>>This Week</option>
                        <option value="month" <?php echo e($filter == 'month' ? 'selected' : ''); ?>>This Month</option>
                        <option value="year" <?php echo e($filter == 'year' ? 'selected' : ''); ?>>This Year</option>
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

                    <a href="<?php echo e(route('hr.requests.attendance')); ?>" class="d-flex justify-content-between mb-3 text-decoration-none text-dark">
                        <span>Missed Punches</span>
                        <span class="badge bg-danger"><?php echo e($missedPunches); ?></span>
                    </a>

                    <a href="<?php echo e(route('hr.requests.helpdesk')); ?>" class="d-flex justify-content-between mb-3 text-decoration-none text-dark">
                        <span>Help Desk</span>
                        <span class="badge bg-info"><?php echo e($helpdeskRequests); ?></span>
                    </a>

                    <a href="<?php echo e(route('hr.requests.leave')); ?>" class="d-flex justify-content-between mb-3 text-decoration-none text-dark">
                        <span>Leaves</span>
                        <span class="badge bg-primary"><?php echo e($pendingLeaves); ?></span>
                    </a>

                </div>
            </div>

        </div>

        <div class="col-md-8">

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Upcoming Events</h5>
                    <a href="<?php echo e(route('employee_event')); ?>" class="btn btn-sm btn-outline-primary">Open Report</a>
                </div>

                <div class="card-body">

                    <ul class="list-group">
                        <?php $__empty_1 = true; $__currentLoopData = $upcomingEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <?php echo e($event['name']); ?> (<?php echo e($event['type']); ?>)
                            <span><?php echo e(\Carbon\Carbon::parse($event['date'])->format('d M')); ?></span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="list-group-item">No upcoming events this month.</li>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>

        </div>

    </div>

</div>

    </div> <!-- end col -->

    
</div>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <!-- apexcharts -->
    <script src="<?php echo e(URL::asset('build/libs/apexcharts/apexcharts.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/jsvectormap/maps/world-merc.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/libs/swiper/swiper-bundle.min.js')); ?>"></script>
    <!-- dashboard init -->
    <script src="<?php echo e(URL::asset('build/js/pages/dashboard-ecommerce.init.js')); ?>"></script>
    <script src="<?php echo e(URL::asset('build/js/app.js')); ?>"></script>
    <script>
var options = {
    series: [{
        name: 'Present',
        data: <?php echo json_encode($presentData, 15, 512) ?>
    },{
        name: 'Absent',
        data: <?php echo json_encode($absentData, 15, 512) ?>
    },{
        name: 'Half Day',
        data: <?php echo json_encode($halfDayData, 15, 512) ?>
    },{
        name: 'Holiday',
        data: <?php echo json_encode($holidayData, 15, 512) ?>
    },{
        name: 'Week Off',
        data: <?php echo json_encode($weekOffData, 15, 512) ?>
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
        categories: <?php echo json_encode($chartLabels, 15, 512) ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/SomyaHRMS/resources/views/index.blade.php ENDPATH**/ ?>