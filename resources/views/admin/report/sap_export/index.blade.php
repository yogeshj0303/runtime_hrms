@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/sap-export.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="sap-export-page">

    <div class="breadcrumb-text">
        Reports / Salary Reports / SAP Export
    </div>

    <div class="page-head">
        <h4 class="page-title">SAP Export</h4>
        <p class="page-subtitle">
            Export salary details to import in SAP.
        </p>
    </div>

    <div class="filter-row">
        <div class="month-box-wrap">
            <label>Period</label>
            <div class="month-action">
                <button type="button" class="month-btn" id="prevMonth">
                    <i class="ri-arrow-left-s-line"></i>
                </button>

                <input type="month" id="salaryMonth" class="month-input" value="2026-05">

                <button type="button" class="month-btn" id="nextMonth">
                    <i class="ri-arrow-right-s-line"></i>
                </button>
            </div>
        </div>

        <div class="format-box">
            <label>Format</label>
            <select>
                <option>Excel</option>
                <option>CSV</option>
            </select>
        </div>
    </div>

    <div class="component-row">

        <div class="component-box">
            <label>Select Components</label>

            <div class="scroll-card">
                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Basic Salary (Paid Days)</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>House Rent Allowance (Paid Days)</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Leave Encashment (Variable)</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Bonus (Variable)</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Gratuity (Variable)</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Loan (System)</span>
                </label>
            </div>
        </div>

        <div class="component-box">
            <label>Select Deductions</label>

            <div class="scroll-card">
                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>ESI</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>PF</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Voluntary PF</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Professional Tax</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Income Tax</span>
                </label>

                <label class="switch-line">
                    <input type="checkbox">
                    <span class="mini-switch"></span>
                    <span>Labour Welfare Fund</span>
                </label>
            </div>
        </div>

    </div>

    <button type="button" class="download-btn">
        <i class="ri-download-2-line"></i> Download
    </button>

    <div class="info-alert">
        <i class="ri-information-fill"></i>
        Make sure you have updated SAP Mappings in
        <a href="#">settings <i class="ri-external-link-line"></i></a>
    </div>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/sap-export.js') }}"></script>
@endsection