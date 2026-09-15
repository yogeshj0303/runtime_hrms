@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-addresses.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="salary-variable-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Addresses
    </div>

    <div class="page-head">
        <h4 class="page-title">Employee Addresses</h4>
        <p class="page-subtitle">Download list of employee addresses.</p>
    </div>

    <form action="{{ route('employee_addresses') }}" method="GET" id="filterForm">
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
        </div>

        <div class="action-row">
            <select name="employee_id" class="employee-search filter-select" style="width: 250px;">
                <option value="">All Employees</option>
                @foreach($allEmployeesList as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->employee_code }} - {{ $emp->first_name }} {{ $emp->last_name }}</option>
                @endforeach
            </select>

            <button class="view-btn" type="submit" id="viewBtn">
                <i class="ri-table-line"></i> View
            </button>

            <button class="export-btn" type="submit" name="export" value="excel" id="excelBtn">
                <i class="ri-file-excel-2-line"></i> Download (EXCEL)
            </button>

            <button class="export-btn" type="submit" name="export" value="pdf" id="pdfBtn">
                <i class="ri-file-pdf-2-line"></i> Download (PDF)
            </button>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="salary-table">
            <thead>
                <tr>
                    <th width="70">SN</th>
                    <th>EMPLOYEE</th>
                    <th>ADDRESS TYPE</th>
                    <th>ADDRESS LINE 1</th>
                    <th>ADDRESS LINE 2</th>
                    <th>CITY</th>
                    <th>PINCODE</th>
                    <th>STATE</th>
                    <th>COUNTRY</th>
                </tr>
            </thead>

            <tbody id="addressTableBody">
                @forelse($addresses as $index => $address)
                <tr>
                    <td>{{ $addresses->firstItem() + $index }}</td>
                    <td>{{ optional($address->employee)->employee_code }} - {{ optional($address->employee)->first_name }} {{ optional($address->employee)->last_name }}</td>
                    <td style="text-transform: capitalize;">{{ $address->type }}</td>
                    <td>{{ $address->address1 }}</td>
                    <td>{{ $address->address2 }}</td>
                    <td>{{ $address->city }}</td>
                    <td>{{ $address->zipcode }}</td>
                    <td>{{ $address->state }}</td>
                    <td>{{ $address->country }}</td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="9" style="text-align: center;">No addresses found for selected filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($addresses->hasPages())
        <div class="mt-3">
            {{ $addresses->links('pagination::bootstrap-4') }}
        </div>
    @endif

</div>

@endsection

@section('script')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.filter-select').select2({
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
@endsection