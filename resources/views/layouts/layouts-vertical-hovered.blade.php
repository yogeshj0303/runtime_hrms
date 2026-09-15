<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-layout-style="default"
    data-layout-position="fixed" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover"
    data-layout-width="fluid">

<head>

    <meta charset="utf-8" />

    <title>
        @yield('title') | SOMYA GROUP HRMS
    </title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="description"
        content="SOMYA GROUP HRMS - Human Resource Management System for employee, attendance, payroll, leave and workforce management."
    >

    <meta
        name="author"
        content="ACT T CONNECT"
    >

    <!-- App Favicon -->
    <link
        rel="shortcut icon"
        href="{{ URL::asset('build/images/logo-dark.png') }}"
    >

    @include('layouts.head-css')

</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <!-- Start content -->
                <div class="container-fluid">
                    @yield('content')
                </div> <!-- content -->
            </div>
            @include('layouts.footer')
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    @include('layouts.customizer')
    <!-- END Right Sidebar -->

    @include('layouts.vendor-scripts')
</body>

</html>
