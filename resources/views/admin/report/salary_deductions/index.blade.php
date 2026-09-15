@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/salary-deductions.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="salary-deductions-page">

    <div class="breadcrumb-text">
        Reports / Salary Reports / Salary Deductions
    </div>

    <div class="page-head">
        <h4 class="page-title">Salary Deductions</h4>
        <p class="page-subtitle">
            Shows details of deductions applied to employee salary during a period.
        </p>
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
            <label>Cost Center</label>
            <select>
                <option>All Cost Centers</option>
            </select>
        </div>

        <div class="filter-box">
            <label>Deduction</label>
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
                    <th>CODE</th>
                    <th>EMPLOYEE NAME</th>
                    <th>DEPARTMENT</th>
                    <th>DESIGNATION</th>
                    <th class="text-end">AMOUNT</th>
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
<script src="{{ asset('assets/admin/js/salary-deductions.js') }}"></script>
@endsection