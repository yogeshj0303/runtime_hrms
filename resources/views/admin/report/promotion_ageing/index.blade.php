@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/promotion-ageing.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="promotion-ageing-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Promotion Ageing
    </div>

    <div class="page-head">
        <h4 class="page-title">Promotion Ageing</h4>
        <p class="page-subtitle">
            View promotion ageing based on last promotion date of employees.
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

    <div class="action-row">
        <div class="filter-box small-box">
            <label>Ageing</label>
            <select>
                <option>More than 1 Year</option>
                <option>More than 2 Years</option>
                <option>More than 3 Years</option>
            </select>
        </div>

        <div class="filter-box small-box">
            <label>Grade</label>
            <select>
                <option>All Grades</option>
            </select>
        </div>

        <button type="button" class="view-btn">
            <i class="ri-table-line"></i> View
        </button>

        <button type="button" class="export-btn">
            <i class="ri-file-excel-2-line"></i> Export
        </button>
    </div>

    <div class="table-wrapper">
        <table class="promotion-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>EMPLOYEE</th>
                    <th>DESIGNATION</th>
                    <th>DEPARTMENT</th>
                    <th>LAST PROMOTED</th>
                    <th>AGEING</th>
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
<script src="{{ asset('assets/admin/js/promotion-ageing.js') }}"></script>
@endsection