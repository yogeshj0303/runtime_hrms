@extends('layouts.master')

<link rel="stylesheet" href="{{ asset('assets/admin/css/setup.css') }}">

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">



<div class="setup-page">
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
<div class="sidebar">
<div class="search">
    <div class="search-box">
        <input type="text" id="menuSearch" placeholder="Search Settings">
        <span id="clearSearch"><i class="ri-close-line"></i></span>
    </div>
</div>

<ul class="menu">
    <li class="active" data-tab="master"><i class="ri-tools-line"></i> Master Setup</li>
    <li data-tab="salary"><i class="ri-money-dollar-circle-line"></i> Salary & Deductions</li>
    <li data-tab="attendance"><i class="ri-calendar-check-line"></i> Attendance & Leaves</li>
    <li data-tab="statutory"><i class="ri-folder-line"></i> Statutory Options</li>
    <li data-tab="ess"><i class="ri-user-line"></i> Employee Self Service</li>
    <li data-tab="integration"><i class="ri-global-line"></i> Integrations</li>
    <li data-tab="advanced"><i class="ri-settings-4-line"></i> Advanced</li>
</ul>
</div>

<div class="cards" id="cardsContainer"></div>



</div>
</div>

</div>



@endsection

@section('script')
<script src="{{ asset('assets/admin/js/setup.js') }}"></script>
@endsection

