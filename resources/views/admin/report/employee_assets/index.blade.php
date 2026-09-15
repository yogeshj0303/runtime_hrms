@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/employee-assets.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="employee-assets-page">

    <div class="breadcrumb-text">
        Reports / Employee Reports / Employee Assets
    </div>

    <div class="page-top">
        <div>
            <h4 class="page-title">Employee Assets</h4>
            <p class="page-subtitle">
                View or download list of assets issued to employees.
            </p>
        </div>

        <button type="button" class="help-btn">
            <i class="ri-question-line"></i> Help
        </button>
    </div>

    <form action="{{ route('employee_assets') }}" method="GET" id="filterForm">
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

        <div class="filter-row second-row">
            <div class="employee-search">
                <select name="employee_id" class="filter-select" style="width: 250px;">
                    <option value="">All Employees</option>
                    @foreach($allEmployeesList as $emp)
                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->employee_code }} - {{ $emp->first_name }} {{ $emp->last_name }}</option>
                    @endforeach
                </select>
            </div>

            <label class="switch-wrap" style="margin-left: 15px;">
                <input type="checkbox" name="warranty_expired" value="1" {{ request('warranty_expired') ? 'checked' : '' }}>
                <span class="switch-slider"></span>
                <span class="switch-text">Warranty Expired Only</span>
            </label>
        </div>

        <div class="action-row">
            <button type="submit" class="action-btn">
                <i class="ri-table-line"></i> View
            </button>

            <button type="submit" name="export" value="excel" class="action-btn">
                <i class="ri-file-excel-2-line"></i> Download (EXCEL)
            </button>

            <button type="submit" name="export" value="pdf" class="action-btn">
                <i class="ri-file-pdf-2-line"></i> Download (PDF)
            </button>
        </div>
    </form>

    <div class="table-wrapper">
        <table class="assets-table">
            <thead>
                <tr>
                    <th width="50">SN</th>
                    <th>EMPLOYEE</th>
                    <th>ASSET TYPE</th>
                    <th>ASSET NAME</th>
                    <th>SERIAL NO</th>
                    <th>ISSUE DATE</th>
                    <th>EXPIRY DATE</th>
                    <th>EST VALUE</th>
                </tr>
            </thead>
            <tbody>
                @forelse($assets as $index => $asset)
                <tr>
                    <td>{{ $assets->firstItem() + $index }}</td>
                    <td>{{ optional($asset->employee)->employee_code }} - {{ optional($asset->employee)->first_name }} {{ optional($asset->employee)->last_name }}</td>
                    <td>{{ $asset->asset_type }}</td>
                    <td>{{ $asset->asset_name }}</td>
                    <td>{{ $asset->serial_number }}</td>
                    <td>{{ $asset->issue_date ? \Carbon\Carbon::parse($asset->issue_date)->format('d-M-Y') : '-' }}</td>
                    <td>
                        @if($asset->expiry_date)
                            @if(\Carbon\Carbon::parse($asset->expiry_date)->isPast())
                                <span style="color: red; font-weight: bold;">{{ \Carbon\Carbon::parse($asset->expiry_date)->format('d-M-Y') }} (Expired)</span>
                            @else
                                {{ \Carbon\Carbon::parse($asset->expiry_date)->format('d-M-Y') }}
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $asset->estimated_value ? '₹'.$asset->estimated_value : '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">No assets found for selected filters.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($assets->hasPages())
        <div class="mt-3">
            {{ $assets->links('pagination::bootstrap-4') }}
        </div>
    @endif

</div>

@endsection

@section('script')
<!-- Include jQuery first in case it's missing or deferred -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    // Use an IIFE and multiple event hooks to ensure it runs even with Turbolinks/PJAX
    (function($) {
        function initSelect2() {
            $('.filter-select').select2({
                width: '100%',
                placeholder: "Select an option",
                allowClear: true
            });
        }
        
        $(document).ready(initSelect2);
        
        // If Turbolinks/Livewire/PJAX is used
        document.addEventListener("turbolinks:load", initSelect2);
        document.addEventListener("pjax:complete", initSelect2);
    })(jQuery);
</script>
@endsection