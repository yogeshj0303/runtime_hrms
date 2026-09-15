<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="light" data-sidebar-image="none"
    data-theme="default" data-theme-colors="default">

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

@yield('body')

@yield('content')

@include('layouts.vendor-scripts')

@yield('script')
</body>

</html>
