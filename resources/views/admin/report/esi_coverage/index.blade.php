@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/esi-coverage.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="esi-coverage-page">

    <div class="breadcrumb-text">
        ‹ Reports / Statutory Reports / ESI Coverage
    </div>

    <div class="page-head">
        <h4 class="page-title">ESI Coverage</h4>
        <p class="page-subtitle">
            View ESI coverage for employees based on eligibility and employee level options.
        </p>
    </div>

    <form method="GET" action="{{ route('esi_coverage') }}" id="esiForm">
        <input type="hidden" name="month" id="monthInput" value="{{ $month }}">
        
        <div class="filter-row">
            <div class="filter-box">
                <label>Location</label>
                <select name="location_id" class="form-select">
                    <option value="All">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Cost Center</label>
                <select name="cost_center_id" class="form-select">
                    <option value="All">All Cost Centers</option>
                    @foreach($costCenters as $cc)
                        <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Department</label>
                <select name="department_id" class="form-select">
                    <option value="All">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="month-action-row">
            <div style="display:flex; align-items:center;">
                <button type="button" class="month-nav-btn" id="prevMonth">
                    <i class="ri-arrow-left-circle-line"></i>
                </button>

                <button type="button" class="month-btn" id="esiMonth">
                    {{ strtoupper(\Carbon\Carbon::parse($month)->format('M-Y')) }}
                </button>

                <button type="button" class="month-nav-btn" id="nextMonth">
                    <i class="ri-arrow-right-circle-line"></i>
                </button>
            </div>

            <div style="display:flex; gap:8px;">
                <button type="submit" class="action-btn view-btn">
                    <i class="ri-eye-line"></i> View
                </button>

                <button type="submit" name="export" value="excel" class="action-btn export-btn">
                    <i class="ri-file-excel-2-line"></i> Excel
                </button>
                
                <button type="submit" name="export" value="pdf" class="action-btn export-btn text-danger">
                    <i class="ri-file-pdf-2-line"></i> PDF
                </button>
            </div>
        </div>
    </form>

    <div class="summary-card">
        <div class="summary-item">
            <h5>Total Employees</h5>
            <p class="blue-count">{{ $totalEmployees }}</p>
        </div>

        <div class="summary-item">
            <h5>ESI Deducted</h5>
            <p class="green-count">{{ $esiDeducted }}</p>
        </div>
    </div>

    <div class="relatives-table-wrap mt-4">
        <table class="table table-hover esi-table">
            <thead class="table-light">
                <tr>
                    <th>SN</th>
                    <th>CODE</th>
                    <th>EMPLOYEE NAME</th>
                    <th>DEPARTMENT</th>
                    <th>LOCATION</th>
                    <th>GROSS SALARY</th>
                    <th>ESI NUMBER</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paginatedEmployees as $index => $item)
                    <tr>
                        <td>{{ $paginatedEmployees->firstItem() + $index }}</td>
                        <td>{{ $item['employee']->employee_code ?? '-' }}</td>
                        <td class="fw-bold">{{ $item['employee']->first_name }} {{ $item['employee']->last_name }}</td>
                        <td>{{ optional(optional($item['employee']->workProfiles->first())->department)->name ?? '-' }}</td>
                        <td>{{ optional(optional($item['employee']->workProfiles->first())->location)->name ?? '-' }}</td>
                        <td class="fw-semibold text-primary">₹ {{ number_format($item['gross_salary'], 2) }}</td>
                        <td>{{ $item['esi_number'] ?? '-' }}</td>
                        <td>
                            @if($item['status_str'] === 'Covered')
                                <span class="badge bg-success-subtle text-success border border-success">Covered</span>
                            @elseif($item['status_str'] === 'Pending')
                                <span class="badge bg-warning-subtle text-warning border border-warning">Pending</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary">Not Covered</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center p-4">No records found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($paginatedEmployees->hasPages())
        <div class="mt-4">
            {{ $paginatedEmployees->appends(request()->except('page'))->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

@endsection

@section('script')
<script>
    window.currentMonthStr = "{{ $month }}";
</script>
<script src="{{ asset('assets/admin/js/esi-coverage.js') }}"></script>
@endsection