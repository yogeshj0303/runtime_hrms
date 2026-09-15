@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/leave-encashment.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="leave-encashment-page">

    <div class="breadcrumb-text">
        Reports / Salary Reports / Leave Encashment
    </div>

    <div class="page-head">
        <h4 class="page-title">Leave Encashment</h4>
        <p class="page-subtitle">
            Shows details of leave encashment applied during a selected period.
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
                    <th>EMPLOYEE NAME</th>
                    <th>POSITION</th>
                    <th>DESCRIPTION</th>
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
<script src="{{ asset('assets/admin/js/leave-encashment.js') }}"></script>
@endsection