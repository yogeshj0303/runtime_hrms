@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/manual-updates.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="manual-updates-page">

    <div class="breadcrumb-text">
        Reports / Attendance Reports / Manual Updates
    </div>

    <h4 class="page-title">Manual Updates</h4>
    <p class="page-subtitle">
        Displays manual changes made to employee attendance during a given period.
    </p>

    <div class="filter-area">

        <div class="filter-grid">

            <div class="filter-box">
                <label>Location</label>
                <select>
                    <option>All Locations</option>
                </select>
            </div>

            <div class="filter-box">
                <label>Cost Center</label>
                <select>
                    <option>All Cost Centers</option>
                </select>
            </div>

            <div class="filter-box">
                <label>Department</label>
                <select>
                    <option>All Departments</option>
                </select>
            </div>

        </div>

        <div class="month-row">

            <button type="button" class="month-btn" id="prevMonth">
                <i class="ri-arrow-left-s-line"></i>
            </button>

            <div class="month-display" id="monthText">JUN-2026</div>

            <button type="button" class="month-btn next" id="nextMonth">
                <i class="ri-arrow-right-s-line"></i>
            </button>

            <input type="text" class="search-box" placeholder="Search name or code">

        </div>

        <div class="action-row">
            <button type="button" class="export-btn">
                <i class="ri-file-excel-2-fill"></i>
                Export (Excel)
            </button>

            <button type="button" class="export-btn">
                <i class="ri-file-pdf-2-fill"></i>
                Export (PDF)
            </button>
        </div>

    </div>

</div>

<script src="{{ asset('assets/admin/js/manual-updates.js') }}"></script>

@endsection