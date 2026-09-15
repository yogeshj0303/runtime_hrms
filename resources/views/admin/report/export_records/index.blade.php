@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('assets/admin/css/export-records.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="export-records-page">

    <div class="breadcrumb-text">
        ‹ Reports / Employee Reports / Export Records
    </div>

    <div class="page-head">
        <div>
            <h4 class="page-title">Export Records</h4>
            <p class="page-subtitle">
                Download employee records in a compatible format that can be re-uploaded with changes.
            </p>
        </div>

        <button type="button" class="help-btn">
            <i class="ri-question-line"></i> Need Help
        </button>
    </div>

    <form method="GET" action="{{ route('export_records') }}" id="exportRecordsForm">
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

        <div class="radio-row">
            <label class="radio-box">
                <input type="radio" name="record_type" value="active" {{ request('record_type', 'active') == 'active' ? 'checked' : '' }}>
                <span></span>
                Active Records Only
            </label>

            <label class="radio-box">
                <input type="radio" name="record_type" value="inactive" {{ request('record_type') == 'inactive' ? 'checked' : '' }}>
                <span></span>
                Inactive Records Only
            </label>

            <label class="radio-box">
                <input type="radio" name="record_type" value="all" {{ request('record_type') == 'all' ? 'checked' : '' }}>
                <span></span>
                All Records
            </label>
        </div>

        <input type="hidden" name="export" value="excel">
        <button type="button" class="download-btn" id="downloadBtn">
            <i class="ri-download-2-line"></i> Download
        </button>
    </form>

</div>

@endsection

@section('script')
<script src="{{ asset('assets/admin/js/export-records.js') }}"></script>
@endsection