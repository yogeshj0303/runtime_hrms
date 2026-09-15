@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/overtime-register.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="overtime-page">

    <div class="breadcrumb-text">
        Reports / Salary Reports / Overtime Register
    </div>

    <div class="page-head">
        <div>
            <h4 class="page-title">Overtime Register</h4>
            <p class="page-subtitle">
                Report showing overtime hours earned by employee(s) during a period.
            </p>
        </div>

        <button type="button" class="help-btn">
            <i class="ri-question-line"></i> Need Help
        </button>
    </div>

    <div class="filter-grid">
        <div class="filter-group">
            <label>Location</label>
            <select class="filter-select">
                <option>All Locations</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Cost Center</label>
            <select class="filter-select">
                <option>All Cost Centers</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Department</label>
            <select class="filter-select">
                <option>All Departments</option>
            </select>
        </div>
    </div>

    <div class="toggle-row">
        <label class="switch-item">
            <input type="checkbox">
            <span class="slider"></span>
            <span>Include Inactive Employees</span>
        </label>

        <label class="switch-item">
            <input type="checkbox">
            <span class="slider"></span>
            <span>Include Zero Records</span>
        </label>

        <label class="switch-item">
            <input type="checkbox">
            <span class="slider"></span>
            <span>Detailed Report</span>
        </label>
    </div>

    <div class="action-row">
        <button type="button" class="filter-btn" id="prevMonth">
            <i class="ri-arrow-left-s-line"></i>
        </button>

        <input type="month" class="month-box" id="salaryMonth" value="2026-05">

        <button type="button" class="filter-btn" id="nextMonth">
            <i class="ri-arrow-right-s-line"></i>
        </button>

        <input type="text" class="employee-input" placeholder="All Employees">

        <button type="button" class="view-btn">
            <i class="ri-table-line"></i> View
        </button>

        <button type="button" class="download-btn">
            <i class="ri-download-2-line"></i> Download
        </button>
    </div>

    <div class="report-table-wrap">
        <table class="report-table">
            <thead>
                <tr>
                    <th class="sn-col">SN</th>
                    <th>EMPLOYEE</th>
                    <th>DEPUTATION</th>
                    <th class="text-right">HRS</th>
                    <th class="text-right">DAYS</th>
                    <th class="text-right">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <tr class="empty-row">
                    <td colspan="6"></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/overtime-register.js') }}"></script>
@endsection