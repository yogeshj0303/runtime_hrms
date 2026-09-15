@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/time-register.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="time-register-page">

    <div class="breadcrumb-text">
        Reports / Attendance Reports / Time Register
    </div>

    <div class="top-head">
        <div>
            <h4 class="page-title">Time Register</h4>
            <p class="page-subtitle">
                Displays daily breakup of employee time including late coming, early going and more.
            </p>
        </div>

        <button class="help-btn">
            <i class="ri-question-line"></i> Read Help
        </button>
    </div>

    <div class="filter-area">

        <div class="filter-grid">
            <div class="filter-box">
                <label>Business Unit</label>
                <select>
                    <option>All Business Units</option>
                </select>
            </div>

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

            <div class="filter-box salary-box">
                <label>Salary Component</label>
                <select>
                    <option>- Select -</option>
                </select>
            </div>

            <div class="show-detail-box">
                <label>&nbsp;</label>
                <label class="switch-label">
                    <input type="checkbox" checked>
                    <span class="switch"></span>
                    <span>Show Details</span>
                </label>
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

            <input type="text" class="employee-search" placeholder="Employee name or code">

            <button type="button" class="view-btn">
                <i class="ri-table-line"></i> View
            </button>

            <button type="button" class="export-btn">
                Export <i class="ri-arrow-down-s-line"></i>
            </button>
        </div>

    </div>

    <div class="report-table-card">
        <table class="report-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>EMPLOYEE</th>
                    <th>SHIFT HRS</th>
                    <th>EARLY IN</th>
                    <th>LATE IN</th>
                    <th>IN HRS</th>
                    <th>LUNCH</th>
                    <th>OUT HRS</th>
                    <th>EARLY OUT</th>
                    <th>LATE OUT</th>
                    <th>PAID HRS+</th>
                    <th>PRESENTS</th>
                    <th>ABSENTS</th>
                    <th>WEEK OFFS</th>
                    <th>HOLIDAYS</th>
                    <th>PAID DAYS</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="16" class="empty-message">
                        Click 'View' to generate report
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-alert">
        * Paid Hrs include Extra Hrs (if any). Paid Hrs are displayed only when a salary component is selected
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/time-register.js') }}"></script>
@endsection