@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-workman.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="employee-workman-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Workman Status
    </div>

    <div class="page-head">
        <h4 class="page-title">Workman Status</h4>
        <p class="page-subtitle">
            View or download list of employees with Workman installation status.
        </p>
    </div>

    <div class="filter-row">
        <div class="filter-box">
            <label>Location</label>
            <select id="location">
                <option>All Locations</option>
            </select>
        </div>

        <div class="filter-box">
            <label>Cost Center</label>
            <select id="cost_center">
                <option>All Cost Centers</option>
            </select>
        </div>

        <div class="filter-box">
            <label>Department</label>
            <select id="department">
                <option>All Departments</option>
            </select>
        </div>

        <div class="toggle-box">
            <label class="switch">
                <input type="checkbox" id="inactive_users" checked>
                <span class="slider"></span>
            </label>
            <span>Inactive Users Only</span>
        </div>
    </div>

    <div class="action-row">
        <button type="button" class="view-btn" id="viewBtn">
            <i class="ri-table-line"></i> View
        </button>

        <button type="button" class="excel-btn" id="excelBtn">
            <i class="ri-file-excel-2-line"></i> Excel
        </button>
    </div>

    <div class="workman-empty-box"></div>

</div>

<script src="{{ asset('assets/admin/js/employee-workman.js') }}"></script>

@endsection