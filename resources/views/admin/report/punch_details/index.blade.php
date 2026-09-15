@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/punch-details.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="punch-details-page">

    <div class="breadcrumb-text">
        Reports / Attendance Reports / Punch Details
    </div>

    <div class="page-head">
        <h4 class="page-title">Punch Details</h4>
        <p class="page-subtitle">
            View details of punches with address and location information
        </p>
    </div>

    <div class="addon-alert">
        <i class="ri-information-line"></i>
        You are not subscribed to <b>Remote Punch Address Addon.</b>
        Postal addresses will be <b>unavailable</b> in this report.
        <br>
        <a href="#">Click here <i class="ri-external-link-line"></i></a>
        to know more and subscribe to this addon.
    </div>

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

            <div class="filter-box">
                <label>From</label>
                <input type="date" value="2026-06-01">
            </div>

            <div class="filter-box">
                <label>To</label>
                <input type="date" value="2026-06-01">
            </div>

            <div class="filter-box employee-box">
                <label>Employee</label>
                <input type="text" placeholder="Search Employee">
            </div>

            <div class="hide-empty-box">
                <label>&nbsp;</label>
                <label class="switch-label">
                    <input type="checkbox">
                    <span class="switch"></span>
                    <span>Hide Empty Records</span>
                </label>
            </div>
        </div>

        <div class="action-row">
            <button type="button" class="view-btn">
                <i class="ri-table-line"></i> View
            </button>

            <button type="button" class="download-btn">
                <i class="ri-download-2-line"></i> Download
            </button>
        </div>

    </div>

    <div class="report-table-card">
        <table class="report-table">
            <thead>
                <tr>
                    <th>EMPLOYEE NAME</th>
                    <th>DATE &amp; TIME</th>
                    <th>LOCATION</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="3" class="empty-message"></td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/punch-details.js') }}"></script>
@endsection