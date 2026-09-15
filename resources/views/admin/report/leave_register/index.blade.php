@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/leave-register.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="leave-register-page">

    <div class="breadcrumb-text">
        Reports / Attendance Reports / Leave Register
    </div>

    <div class="top-head">
        <div>
            <h4 class="page-title">Leave Register</h4>
            <p class="page-subtitle">Leave balance report for one or more periods</p>
        </div>

        <button class="help-btn">
            <i class="ri-question-line"></i> Read Help
        </button>
    </div>

    <div class="filter-area">

        <div class="filter-grid">
            <div class="filter-box">
                <label>Location</label>
                <select><option>All Locations</option></select>
            </div>

            <div class="filter-box">
                <label>Cost Center</label>
                <select><option>All Cost Centers</option></select>
            </div>

            <div class="filter-box">
                <label>Department</label>
                <select><option>All Departments</option></select>
            </div>

            <div class="filter-box">
                <label>From</label>
                <input type="date" value="2026-06-01">
            </div>

            <div class="filter-box">
                <label>To</label>
                <input type="date" value="2026-06-01">
            </div>

            <div class="filter-box">
                <label>Leave Type</label>
                <select>
                    <option>- Select -</option>
                    <option>Casual Leave</option>
                    <option>Sick Leave</option>
                    <option>Earned Leave</option>
                </select>
            </div>

            <div class="filter-box employee-filter">
                <label>&nbsp;</label>
                <input type="text" placeholder="Filter Employees">
            </div>
        </div>

        <div class="radio-row">
            <label><input type="radio" name="record" checked> All Records</label>
            <label><input type="radio" name="record"> Active Records</label>
            <label><input type="radio" name="record"> Inactive Records</label>
        </div>

        <div class="action-row">
            <button class="view-btn">
                <i class="ri-eye-line"></i> View
            </button>

            <button class="download-btn">
                <i class="ri-download-2-line"></i> Download
            </button>
        </div>

    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>EMPLOYEE NAME<br><span>EMPLOYEE CODE</span></th>
                    <th>JOINING DATE<br><span>CONFIRMATION DATE</span></th>
                    <th>LOCATION<br><span>DESIGNATION</span></th>
                    <th>DATE</th>
                    <th>REMARKS</th>
                    <th class="text-right">COUNT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" class="empty-row"></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/leave-register.js') }}"></script>
@endsection