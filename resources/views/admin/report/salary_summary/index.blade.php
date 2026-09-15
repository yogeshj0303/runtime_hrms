@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/salary-summary.css') }}">

<style>
.salary-summary-page .filter-btn,
.salary-summary-page .export-btn,
.salary-summary-page .view-btn{
    background: linear-gradient(135deg, #133C5A 0%, #1d567f 100%) !important;
    background-color: #133C5A !important;
    color: #fff !important;
    border: 0 !important;
    box-shadow: none !important;
}

.salary-summary-page .filter-btn:hover,
.salary-summary-page .filter-btn:focus,
.salary-summary-page .filter-btn:active,
.salary-summary-page .export-btn:hover,
.salary-summary-page .export-btn:focus,
.salary-summary-page .export-btn:active,
.salary-summary-page .view-btn:hover,
.salary-summary-page .view-btn:focus,
.salary-summary-page .view-btn:active{
    background: linear-gradient(135deg, #133C5A 0%, #1d567f 100%) !important;
    background-color: #133C5A !important;
    color: #fff !important;
}
</style>



<div class="salary-summary-page">

    <div class="breadcrumb-text">
        Reports / Salary Reports / Salary Summary
    </div>

    <h4 class="page-title">Salary Summary</h4>

    <p class="page-subtitle">
        View salary summary by cost center, location and department
    </p>

    <div class="filter-row">
        <button type="button" class="filter-btn" id="prevMonth">
            <i class="ri-arrow-left-s-line"></i>
        </button>

        <input type="month" class="month-box" id="salaryMonth" value="2026-06">

        <button type="button" class="filter-btn" id="nextMonth">
            <i class="ri-arrow-right-s-line"></i>
        </button>

        <button type="button" class="view-btn" id="viewReport">
            <i class="ri-eye-line"></i> View
        </button>
    </div>

    @php
        $sections = [
            ['title' => 'Cost Centers', 'column' => 'COST CENTER'],
            ['title' => 'Locations', 'column' => 'LOCATION'],
            ['title' => 'Departments', 'column' => 'DEPARTMENT'],
            ['title' => 'Grades', 'column' => 'GRADE'],
        ];
    @endphp

    @foreach($sections as $section)
        <div class="report-section">
            <div class="section-header">
                <h5 class="section-title">{{ $section['title'] }}</h5>

                <div class="action-buttons">
                    <button type="button" class="export-btn export-summary"
                        data-section="{{ $section['title'] }}">
                        <i class="ri-file-list-3-line"></i>
                        Export Summary
                    </button>

                    <button type="button" class="export-btn export-detail"
                        data-section="{{ $section['title'] }}">
                        <i class="ri-file-text-line"></i>
                        Export Detail
                    </button>
                </div>
            </div>

            <div class="table-responsive table-card">
                <table class="summary-table">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>{{ $section['column'] }}</th>
                            <th>EMPLOYEES</th>
                            <th>PAY DAYS</th>
                            <th>EARNINGS</th>
                            <th>DEDUCTIONS</th>
                            <th class="text-end">NET SALARY</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="empty-row">
                            <td colspan="7">Click 'View' to generate report</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/salary-summary.js') }}"></script>
@endsection