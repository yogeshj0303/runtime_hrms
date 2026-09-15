@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-relatives.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="employee-relatives-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Employee Relatives
    </div>

    <div class="page-head">
        <h4 class="page-title">Employee Relatives</h4>
        <p class="page-subtitle">
            View or download list of employee relatives.
        </p>
    </div>

    <form method="GET" action="{{ route('employee_relatives') }}">
        <div class="filter-row">
            <div class="filter-box">
                <label>Location</label>
                <select name="location_id" class="form-select" style="font-size:12px; height:34px;">
                    <option value="All">All Locations</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Cost Center</label>
                <select name="cost_center_id" class="form-select" style="font-size:12px; height:34px;">
                    <option value="All">All Cost Centers</option>
                    @foreach($costCenters as $cc)
                        <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-box">
                <label>Department</label>
                <select name="department_id" class="form-select" style="font-size:12px; height:34px;">
                    <option value="All">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>

            <label class="switch-wrap">
                <input type="checkbox" name="active_only" value="1" id="activeEmployeeToggle" {{ request('active_only') == '1' ? 'checked' : '' }}>
                <span class="switch-slider"></span>
                <span class="switch-text">Active Employees Only</span>
            </label>
        </div>

        <div class="action-row">
            <button type="submit" class="action-btn view-btn">
                <i class="ri-table-line"></i> View
            </button>

            <button type="submit" name="export" value="excel" class="action-btn export-btn">
                <i class="ri-file-excel-2-line"></i> Excel
            </button>
            
            <button type="submit" name="export" value="pdf" class="action-btn export-btn text-danger">
                <i class="ri-file-pdf-2-line"></i> PDF
            </button>
        </div>
    </form>

    <div class="relatives-table-wrap">
        <table class="relatives-table table table-hover">
            <thead class="table-light">
                <tr>
                    <th>SN</th>
                    <th>CODE</th>
                    <th>EMPLOYEE NAME</th>
                    <th>RELATIONSHIP</th>
                    <th>RELATIVE NAME</th>
                    <th>DATE OF BIRTH</th>
                    <th>DEPENDENT</th>
                    <th>PHONE</th>
                    <th>E-MAIL</th>
                    <th>NOTES</th>
                </tr>
            </thead>
            <tbody>
                @forelse($relatives as $index => $relative)
                    <tr>
                        <td>{{ $relatives->firstItem() + $index }}</td>
                        <td>{{ optional($relative->employee)->employee_code ?? '-' }}</td>
                        <td class="fw-bold">{{ optional($relative->employee)->first_name }} {{ optional($relative->employee)->last_name }}</td>
                        <td><span class="badge bg-info text-dark">{{ $relative->relation ?? '-' }}</span></td>
                        <td class="fw-semibold">{{ $relative->name ?? '-' }}</td>
                        <td>{{ $relative->dob ? \Carbon\Carbon::parse($relative->dob)->format('d M Y') : '-' }}</td>
                        <td>
                            @if($relative->is_dependent)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                        <td>{{ $relative->phone ?? '-' }}</td>
                        <td>{{ $relative->email ?? '-' }}</td>
                        <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $relative->notes }}">{{ $relative->notes ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center p-4">No relatives found for the selected criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($relatives->hasPages())
        <div class="mt-4">
            {{ $relatives->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/employee-relatives.js') }}"></script>
@endsection