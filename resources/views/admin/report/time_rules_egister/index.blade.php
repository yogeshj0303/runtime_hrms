@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/time-rules-register.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="time-rules-page">

    <div class="breadcrumb-text">
        Reports / Attendance Reports / Time Rules Register
    </div>

    <div class="page-head">
        <h4 class="page-title">Time Rules Register</h4>
        <p class="page-subtitle">
            Shows the applicability of time rules and their effect on attendance.
        </p>
    </div>

    <div class="divider"></div>

    <div class="filter-area">

        <div class="filter-grid">
            <div class="filter-box">
                <label>Location</label>
                <select>
                    <option>All Locations</option>
                </select>
            </div>

            <div class="filter-box">
                <label>Department</label>
                <select>
                    <option>All Departments</option>
                </select>
            </div>

            <div class="filter-box">
                <label>Cost Center</label>
                <select>
                    <option>All Cost Centers</option>
                </select>
            </div>
        </div>

        <div class="report-action-row">
            <button type="button" class="month-nav-btn" id="prevMonth">
                <i class="ri-arrow-left-circle-line"></i>
            </button>

            <div class="month-display" id="monthText">MAY-2026</div>

            <button type="button" class="month-nav-btn" id="nextMonth">
                <i class="ri-arrow-right-circle-line"></i>
            </button>

            <input type="text" class="employee-search" placeholder="All Employees">

            <button type="button" class="view-btn">
                <i class="ri-table-line"></i> View
            </button>

            <button type="button" class="export-btn">
                <i class="ri-file-excel-2-line"></i> Export (EXCEL)
            </button>

            <button type="button" class="export-btn">
                <i class="ri-file-pdf-2-line"></i> Export (PDF)
            </button>
        </div>

    </div>

    <div class="report-table-card">
        <table class="report-table">
            <thead>
                <tr>
                    <th>DATE</th>
                    <th>SHIFT TIMING</th>
                    <th>ACTUAL TIMING</th>
                    <th>RULE #</th>
                    <th>HIT #</th>
                    <th>DESCRIPTION</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6" class="empty-message"></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/time-rules-register.js') }}"></script>
@endsection