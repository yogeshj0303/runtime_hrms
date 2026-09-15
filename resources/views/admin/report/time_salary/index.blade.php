@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/time-salary.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="time-salary-page">

    <div class="breadcrumb-text">
        Reports / Salary Reports / Time Salary
    </div>

    <div class="page-head">
        <div>
            <h4 class="page-title">Time Salary</h4>
            <p class="page-subtitle">
                Shows details of salary earned for time-based components.
            </p>
        </div>
    </div>

    <div class="filter-row">
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
            <label>Salary Component</label>
            <select>
                <option>- Select -</option>
            </select>
        </div>
    </div>

    <div class="action-row">
        <button type="button" class="month-btn" id="prevMonth">
            <i class="ri-arrow-left-s-line"></i>
        </button>

        <input type="month" id="salaryMonth" class="month-input" value="2026-06">

        <button type="button" class="month-btn" id="nextMonth">
            <i class="ri-arrow-right-s-line"></i>
        </button>

        <input type="text" class="employee-search" placeholder="Search employee">

        <button type="button" class="view-btn">
            <i class="ri-table-line"></i> View
        </button>

        <button type="button" class="export-btn">
            <i class="ri-file-excel-2-line"></i> Export
        </button>
    </div>

    <div class="table-wrapper">
        <table class="salary-table">
            <thead>
                <tr>
                    <th width="70">SN</th>
                    <th>EMPLOYEE</th>
                    <th>DESIGNATION</th>
                    <th>TYPE OF PAYMENT</th>
                    <th class="text-end">RATE</th>
                    <th class="text-end">HOURS</th>
                    <th class="text-end">AMOUNT</th>
                </tr>
            </thead>
            <tbody>
                <tr class="empty-row">
                    <td colspan="7"></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/time-salary.js') }}"></script>
@endsection