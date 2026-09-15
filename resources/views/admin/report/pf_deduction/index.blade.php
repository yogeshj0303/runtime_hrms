@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/pf-deduction.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="pf-deduction-page">

    <div class="breadcrumb-text">
        ‹ Reports / Statutory Reports / PF Deduction
    </div>

    <div class="page-head">
        <div>
            <h4 class="page-title">PF Deduction</h4>
            <p class="page-subtitle">
                View or download PF deduction for a period and PF ECR File.
            </p>
        </div>

        <button type="button" class="help-btn">
            <i class="ri-question-line"></i> Need Help
        </button>
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
                <option>All Cost Center</option>
            </select>
        </div>

        <div class="filter-box">
            <label>Department</label>
            <select>
                <option>All Departments</option>
            </select>
        </div>

        <div class="option-box">
            <label>Options <i class="ri-information-fill"></i></label>
            <label class="check-label">
                <input type="checkbox" id="ignoreNcp">
                <span></span>
                Ignore NCP Days
            </label>
        </div>
    </div>

    <div class="month-action-row">
        <button type="button" class="month-nav-btn" id="prevMonth">
            <i class="ri-arrow-left-circle-line"></i>
        </button>

        <button type="button" class="month-btn" id="pfMonth">
            JUN-2026
        </button>

        <button type="button" class="month-nav-btn" id="nextMonth">
            <i class="ri-arrow-right-circle-line"></i>
        </button>

        <button type="button" class="action-btn">
            <i class="ri-table-line"></i> View Summary
        </button>

        <button type="button" class="action-btn">
            <i class="ri-file-excel-2-line"></i> Excel Summary
        </button>

        <button type="button" class="action-btn">
            <i class="ri-file-text-line"></i> PF Return (ECR)
        </button>
    </div>

    <div class="pf-table-wrap">
        <table class="pf-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>EMPLOYEE</th>
                    <th>UAN</th>
                    <th>GROSS WAGES</th>
                    <th>PF WAGES</th>
                    <th>PENSION WAGES</th>
                    <th>EDLI WAGES</th>
                    <th>EMPLOYEE CONT.</th>
                    <th>PENSION CONT.</th>
                    <th>EMPLOYER CONT.</th>
                    <th>NCP DAYS</th>
                    <th>REFUND OF ADV.</th>
                </tr>
            </thead>
            <tbody>
                {{-- Backend data here --}}
            </tbody>
        </table>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/pf-deduction.js') }}"></script>
@endsection