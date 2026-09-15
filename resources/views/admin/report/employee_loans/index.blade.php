@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-loans.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="employee-loans-page">

    <div class="breadcrumb-text">
        Reports / Salary Reports / Employee Loans
    </div>

    <div class="page-head">
        <h4 class="page-title">Employee Loans</h4>
        <p class="page-subtitle">
            List of loans with current status as of date.
        </p>
    </div>

    <form action="{{ route('employee_loans') }}" method="GET" id="filterForm">
        <div class="filter-row">
            <div class="filter-box">
                <label>Location</label>
                <select name="location_id" class="filter-select">
                    <option value="All">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Cost Center</label>
                <select name="cost_center_id" class="filter-select">
                    <option value="All">All Cost Centers</option>
                    @foreach($costCenters as $cc)
                        <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name ?? $cc->code }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Department</label>
                <select name="department_id" class="filter-select">
                    <option value="All">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="date-box">
                <label>Issued After</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}">
            </div>

            <div class="date-box">
                <label>Issued Till</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}">
            </div>

            <div class="report-options">
                <label>Report Options</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="report_type" value="summary" {{ request('report_type', 'summary') == 'summary' ? 'checked' : '' }}>
                        Summary Only
                    </label>
                    <label>
                        <input type="radio" name="report_type" value="detailed" {{ request('report_type') == 'detailed' ? 'checked' : '' }}>
                        With Loan Schedule
                    </label>
                </div>
            </div>
        </div>

        <div class="action-row mt-2">
            <div style="width: 280px; margin-right: 15px;">
                <select name="employee_search" class="filter-select">
                    <option value="">Search Employee...</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->employee_code }}" {{ request('employee_search') == $emp->employee_code ? 'selected' : '' }}>
                            {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="view-btn">
                <i class="ri-eye-line"></i> View
            </button>

            <button type="submit" name="export" value="excel" class="excel-btn">
                <i class="ri-file-excel-2-line"></i> Excel
            </button>

            <button type="submit" name="export" value="pdf" class="pdf-btn">
                <i class="ri-file-pdf-2-line"></i> PDF
            </button>
        </div>
    </form>

    <div class="loans-table-wrap mt-3">
        <table class="loans-table">
            <thead>
                <tr>
                    <th>SN</th>
                    <th>E-CODE</th>
                    <th>EMPLOYEE</th>
                    <th>LOAN AMOUNT</th>
                    <th>ISSUE DATE</th>
                    <th>EMI</th>
                    <th>TENURE</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($loans) && $loans->count() > 0)
                    @foreach($loans as $index => $loan)
                    <tr>
                        <td>{{ $loans->firstItem() + $index }}</td>
                        <td>{{ $loan->employee->employee_code }}</td>
                        <td>{{ $loan->employee->first_name }} {{ $loan->employee->last_name }}</td>
                        <td style="font-weight: bold; color: #133C5A;">{{ number_format($loan->loan_amount, 2) }}</td>
                        <td>{{ $loan->issue_date->format('d M, Y') }}</td>
                        <td>{{ number_format($loan->emi_amount, 2) }}</td>
                        <td>{{ $loan->tenure_months }} Months</td>
                        <td>
                            @if($loan->status == 'Active')
                                <span style="color: #28a745; font-weight: 500;">Active</span>
                            @elseif($loan->status == 'Closed')
                                <span style="color: #6c757d; font-weight: 500;">Closed</span>
                            @else
                                <span style="color: #ffc107; font-weight: 500;">{{ $loan->status }}</span>
                            @endif
                        </td>
                    </tr>
                    @if(request('report_type') == 'detailed' && $loan->installments->count() > 0)
                    <tr class="schedule-row">
                        <td colspan="8">
                            <div class="schedule-box">
                                <strong>Loan Schedule:</strong>
                                <table class="schedule-table">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                    @foreach($loan->installments as $inst)
                                    <tr>
                                        <td>{{ $inst->installment_date->format('M Y') }}</td>
                                        <td>{{ number_format($inst->amount, 2) }}</td>
                                        <td>{{ $inst->status }}</td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @endforeach
                @else
                    <tr class="empty-row">
                        <td colspan="8" style="text-align: center; padding: 20px;">
                            @if(request()->has('from_date'))
                                No loans found for the selected filters.
                            @else
                                Please select filters and click View to load loans.
                            @endif
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if(isset($loans) && $loans->hasPages())
        <div class="mt-3">
            {{ $loans->links('pagination::bootstrap-4') }}
        </div>
    @endif

</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    (function($) {
        function initSelect2() {
            $('.filter-select').select2({
                width: '100%',
                allowClear: false
            });
        }
        $(document).ready(initSelect2);
        document.addEventListener("turbolinks:load", initSelect2);
        document.addEventListener("pjax:complete", initSelect2);
    })(jQuery);
</script>
@endsection