@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/variable-salary.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="salary-variable-page">

    <!-- Breadcrumb -->
    <div class="breadcrumb-text">
        Reports / Salary Reports / Variable Salary
    </div>

    <!-- Header -->
    <div class="page-head">
        <div>
            <h4 class="page-title">Variable Salary</h4>
            <p class="page-subtitle">
                Shows details of variable salary components during a period.
            </p>
        </div>
    </div>

    <!-- Filters -->
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

    <!-- Actions -->
    <div class="action-row">

        <button class="month-btn" id="prevMonth">
            <i class="ri-arrow-left-s-line"></i>
        </button>

        <input type="month"
               id="salaryMonth"
               class="month-input"
               value="2026-06">

        <button class="month-btn" id="nextMonth">
            <i class="ri-arrow-right-s-line"></i>
        </button>

        <input type="text"
               class="employee-search"
               placeholder="Search employee">

        <button class="view-btn">
            <i class="ri-table-line"></i>
            View
        </button>

        <button class="export-btn">
            <i class="ri-file-excel-2-line"></i>
            Export
        </button>

    </div>

    <!-- Table -->
    <div class="table-wrapper">

        <table class="salary-table">

            <thead>
                <tr>
                    <th width="70">SN</th>
                    <th>EMPLOYEE</th>
                    <th>DEPARTMENT</th>
                    <th>DESIGNATION</th>
                    <th class="text-end">AMOUNT</th>
                </tr>
            </thead>

            <tbody>
                <tr class="empty-row">
                    <td colspan="5"></td>
                </tr>
            </tbody>

        </table>

    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/variable-salary.js') }}"></script>
@endsection