@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-register.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="salary-summary-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Employee Register
    </div>

    <div class="page-top">
        <div>
            <h4 class="page-title">Employee Register</h4>
            <p class="page-subtitle">
                View or download employee register for selected business unit, location, cost center and department.
            </p>
        </div>

        <button type="button" class="help-btn">
            <i class="ri-question-line"></i> Need Help
        </button>
    </div>

    <form method="GET" action="{{ route('employee_register') }}">
    <div class="top-filter-grid">
        <div class="filter-group">
            <label>Business Unit</label>
            <select class="filter-select" name="business_unit_id">
                <option value="All">All Business Units</option>
                @foreach($businessUnits as $bu)
                    <option value="{{ $bu->id }}" {{ request('business_unit_id') == $bu->id ? 'selected' : '' }}>{{ $bu->unit_name ?? $bu->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>Location</label>
            <select class="filter-select" name="location_id">
                <option value="All">All Locations</option>
                @foreach($locations as $loc)
                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>Cost Center</label>
            <select class="filter-select" name="cost_center_id">
                <option value="All">All Cost Centers</option>
                @foreach($costCenters as $cc)
                    <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name ?? $cc->code }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>Department</label>
            <select class="filter-select" name="department_id">
                <option value="All">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="filter-group">
            <label>Employee Status</label>
            <select class="filter-select" name="status">
                <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All Employees</option>
                <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active Employees</option>
                <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive Employees</option>
            </select>
        </div>
    </div>

    <div class="filter-row">
        <div style="width: 300px; display: inline-block;">
            <select name="employee_id" class="filter-select">
                <option value="">Search Employee Name / Code</option>
                @foreach($allEmployeesList as $emp)
                    <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                        {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="view-btn">
            <i class="ri-eye-line"></i> View
        </button>

        <button type="submit" name="export" value="pdf" class="view-btn">
            <i class="ri-download-2-line"></i> Download PDF
        </button>

        <button type="submit" name="export" value="excel" class="view-btn">
            <i class="ri-file-excel-2-line"></i> Download Excel
        </button>
    </div>
    <div class="report-options-card">

        <div class="report-options-title">
            <i class="ri-filter-3-line"></i>
            Employee Register Options
        </div>

        <hr>

        <div class="records-row">
            <div>
                <h6>Records to Include</h6>

                <div class="record-checks">
                    <div class="form-check form-switch">
                        <input class="form-check-input record-type" type="radio" name="record_type" value="all" id="allRecords" {{ request('record_type') == 'all' ? 'checked' : '' }}>
                        <label class="form-check-label" for="allRecords">All Records</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input record-type" type="radio" name="record_type" value="active" id="activeRecords" {{ request('record_type', 'active') == 'active' ? 'checked' : '' }}>
                        <label class="form-check-label" for="activeRecords">Active Records</label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input record-type" type="radio" name="record_type" value="inactive" id="inactiveRecords" {{ request('record_type') == 'inactive' ? 'checked' : '' }}>
                        <label class="form-check-label" for="inactiveRecords">Inactive Records</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="checkbox-grid">

            <div class="check-column">
                <h6>Basic Details</h6>

                @php
                    $basic = [
                        ['Employee Code', 'empCode', true],
                        ['Employee Name', 'empName', true],
                        ['Gender', 'gender', true],
                        ['Date of Birth', 'dob', false],
                        ['Date of Joining', 'doj', true],
                        ['Date of Exit', 'exitDate', false],
                        ['Employee Status', 'empStatus', true],
                        ['Employee Type', 'empType', false],
                    ];
                @endphp

                @foreach($basic as $item)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" name="columns[]" value="{{ $item[1] }}" id="{{ $item[1] }}" {{ (is_array(request('columns')) && in_array($item[1], request('columns'))) || (!request()->has('columns') && $item[2]) ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $item[1] }}">{{ $item[0] }}</label>
                    </div>
                @endforeach
            </div>

            <div class="check-column">
                <h6>Contact Details</h6>

                @php
                    $contact = [
                        ['Mobile Phone', 'mobile', true],
                        ['Office E-Mail', 'email', true],
                        ['Personal E-Mail', 'personalEmail', false],
                        ['Current Address', 'currentAddress', false],
                        ['Permanent Address', 'permanentAddress', false],
                        ['City', 'city', false],
                        ['State', 'state', false],
                        ['PIN Code', 'pincode', false],
                    ];
                @endphp

                @foreach($contact as $item)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" name="columns[]" value="{{ $item[1] }}" id="{{ $item[1] }}" {{ (is_array(request('columns')) && in_array($item[1], request('columns'))) || (!request()->has('columns') && $item[2]) ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $item[1] }}">{{ $item[0] }}</label>
                    </div>
                @endforeach
            </div>

            <div class="check-column">
                <h6>Work Profile</h6>

                @php
                    $work = [
                        ['Business Unit', 'businessUnit', true],
                        ['Location', 'location', true],
                        ['Cost Center', 'costCenter', true],
                        ['Department', 'department', true],
                        ['Designation', 'designation', true],
                        ['Grade', 'grade', false],
                        ['Reporting Manager', 'manager', true],
                        ['Shift Policy', 'shiftPolicy', false],
                    ];
                @endphp

                @foreach($work as $item)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" name="columns[]" value="{{ $item[1] }}" id="{{ $item[1] }}" {{ (is_array(request('columns')) && in_array($item[1], request('columns'))) || (!request()->has('columns') && $item[2]) ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $item[1] }}">{{ $item[0] }}</label>
                    </div>
                @endforeach
            </div>

            <div class="check-column">
                <h6>Identity & Bank</h6>

                @php
                    $identity = [
                        ['PAN Number', 'pan', false],
                        ['Aadhar Number', 'aadhar', false],
                        ['PF UAN Number', 'pf', false],
                        ['ESI IP Number', 'esi', false],
                        ['Bank Name', 'bankName', false],
                        ['Bank IFSC', 'ifsc', false],
                        ['Bank Account', 'account', false],
                    ];
                @endphp

                @foreach($identity as $item)
                    <div class="form-check form-check-primary mb-2">
                        <input class="form-check-input" type="checkbox" name="columns[]" value="{{ $item[1] }}" id="{{ $item[1] }}" {{ (is_array(request('columns')) && in_array($item[1], request('columns'))) || (!request()->has('columns') && $item[2]) ? 'checked' : '' }}>
                        <label class="form-check-label" for="{{ $item[1] }}">{{ $item[0] }}</label>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="rounding-row">
            <div class="filter-group small-filter">
                <label>Sort By</label>
                <select class="filter-select" name="sort_by">
                    <option value="employee_code" {{ request('sort_by') == 'employee_code' ? 'selected' : '' }}>Employee Code</option>
                    <option value="first_name" {{ request('sort_by') == 'first_name' ? 'selected' : '' }}>Employee Name</option>
                    <option value="joining_date" {{ request('sort_by') == 'joining_date' ? 'selected' : '' }}>Date of Joining</option>
                    <option value="department" {{ request('sort_by') == 'department' ? 'selected' : '' }}>Department</option>
                </select>
            </div>

            <div class="filter-group small-filter">
                <label>Records Per Page</label>
                <select class="filter-select" name="per_page">
                    <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == '50' ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == '100' ? 'selected' : '' }}>100</option>
                    <option value="500" {{ request('per_page') == '500' ? 'selected' : '' }}>500</option>
                </select>
            </div>
        </div>

        <div class="save-area">
            <button type="submit" class="save-btn">
                <i class="ri-check-double-line"></i> Apply Options
            </button>
        </div>

    </div>
    </form>

    <!-- Data Table -->
    @if(isset($employees) && count($employees) > 0)
    <div class="card mt-4" style="border:none; box-shadow:0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24); border-radius:8px;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle table-hover">
                    <thead class="table-light">
                        <tr>
                            @php
                                $selectedCols = request('columns', ['empCode', 'empName', 'gender', 'doj', 'empStatus', 'mobile', 'email', 'businessUnit', 'location', 'costCenter', 'department', 'designation']);
                                $colsMap = [
                                    'empCode' => 'Emp Code',
                                    'empName' => 'Emp Name',
                                    'gender' => 'Gender',
                                    'dob' => 'DOB',
                                    'doj' => 'DOJ',
                                    'exitDate' => 'Exit Date',
                                    'empStatus' => 'Status',
                                    'mobile' => 'Mobile',
                                    'email' => 'Email',
                                    'businessUnit' => 'Business Unit',
                                    'location' => 'Location',
                                    'costCenter' => 'Cost Center',
                                    'department' => 'Department',
                                    'designation' => 'Designation',
                                ];
                            @endphp
                            @foreach($selectedCols as $c)
                                <th>{{ $colsMap[$c] ?? ucfirst($c) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $emp)
                        <tr>
                            @foreach($selectedCols as $c)
                                @if($c == 'empCode') <td>{{ $emp->employee_code }}</td>
                                @elseif($c == 'empName') <td>{{ $emp->first_name }} {{ $emp->last_name }}</td>
                                @elseif($c == 'gender') <td>{{ optional($emp->profile)->gender ?? '-' }}</td>
                                @elseif($c == 'dob') <td>{{ optional($emp->profile)->dob ? \Carbon\Carbon::parse($emp->profile->dob)->format('d-M-Y') : '-' }}</td>
                                @elseif($c == 'doj') <td>{{ $emp->joining_date ? $emp->joining_date->format('d-M-Y') : '-' }}</td>
                                @elseif($c == 'exitDate') <td>{{ optional($emp->profile)->resignation_date ? \Carbon\Carbon::parse($emp->profile->resignation_date)->format('d-M-Y') : '-' }}</td>
                                @elseif($c == 'empStatus')
                                    <td>
                                        @if($emp->status == 'active')
                                            <span class="badge bg-success-subtle text-success px-2 py-1">Active</span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger px-2 py-1">Inactive</span>
                                        @endif
                                    </td>
                                @elseif($c == 'empType') <td>{{ optional($emp->profile)->employment_type ?? '-' }}</td>
                                @elseif($c == 'mobile') <td>{{ $emp->phone ?? '-' }}</td>
                                @elseif($c == 'email') <td>{{ $emp->email ?? '-' }}</td>
                                @elseif($c == 'personalEmail') <td>{{ optional($emp->profile)->personal_email ?? '-' }}</td>
                                @elseif($c == 'currentAddress') <td>{{ optional($emp->addresses->where('type', 'current')->first())->address1 ?? '-' }}</td>
                                @elseif($c == 'permanentAddress') <td>{{ optional($emp->addresses->where('type', 'permanent')->first())->address1 ?? '-' }}</td>
                                @elseif($c == 'city') <td>{{ optional($emp->addresses->first())->city ?? '-' }}</td>
                                @elseif($c == 'state') <td>{{ optional($emp->addresses->first())->state ?? '-' }}</td>
                                @elseif($c == 'pincode') <td>{{ optional($emp->addresses->first())->zipcode ?? '-' }}</td>
                                @elseif($c == 'businessUnit') <td>{{ optional(optional($emp->workProfiles->where('is_current', true)->first())->businessUnit)->name ?? optional($emp->business)->name ?? '-' }}</td>
                                @elseif($c == 'location') <td>{{ optional(optional($emp->workProfiles->where('is_current', true)->first())->location)->name ?? '-' }}</td>
                                @elseif($c == 'costCenter') <td>{{ optional(optional($emp->workProfiles->where('is_current', true)->first())->costCenter)->name ?? optional(optional($emp->workProfiles->where('is_current', true)->first())->costCenter)->code ?? '-' }}</td>
                                @elseif($c == 'department') <td>{{ optional(optional($emp->workProfiles->where('is_current', true)->first())->department)->name ?? $emp->department ?? '-' }}</td>
                                @elseif($c == 'designation') <td>{{ optional(optional($emp->workProfiles->where('is_current', true)->first())->designation)->name ?? $emp->designation ?? '-' }}</td>
                                @elseif($c == 'grade') <td>{{ optional(optional($emp->workProfiles->where('is_current', true)->first())->grade)->name ?? '-' }}</td>
                                @elseif($c == 'manager') <td>{{ optional(optional($emp->workProfiles->where('is_current', true)->first())->reportingManager)->first_name ?? '-' }}</td>
                                @elseif($c == 'shiftPolicy') <td>{{ optional(optional($emp->policyAssignment)->shift)->name ?? '-' }}</td>
                                @elseif($c == 'pan') <td>{{ optional($emp->identity)->pan_number ?? '-' }}</td>
                                @elseif($c == 'aadhar') <td>{{ optional($emp->identity)->aadhaar_number ?? '-' }}</td>
                                @elseif($c == 'pf') <td>{{ optional($emp->identity)->pf_uan ?? '-' }}</td>
                                @elseif($c == 'esi') <td>{{ optional($emp->identity)->esi_number ?? '-' }}</td>
                                @elseif($c == 'bankName') <td>{{ optional($emp->identity)->bank_name ?? '-' }}</td>
                                @elseif($c == 'ifsc') <td>{{ optional($emp->identity)->ifsc ?? '-' }}</td>
                                @elseif($c == 'account') <td>{{ optional($emp->identity)->account_number ?? '-' }}</td>
                                @else <td>-</td>
                                @endif
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-3">
                {{ $employees->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
    @elseif(isset($employees))
    <div class="alert alert-info mt-4">
        No records found for the selected filters.
    </div>
    @endif

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/employee-register.js') }}"></script>
<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.filter-select').select2({
            width: '100%',
            placeholder: "Select an option",
            allowClear: true
        });
    });
</script>
@endsection