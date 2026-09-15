@extends('layouts.master')

@section('title')
Attendance Settings
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<style>
    .settings-card {
        border: 1px solid #e9ecef;
        border-radius: 4px;
        margin-bottom: 20px;
        box-shadow: none;
    }
    .settings-card .card-header {
        background-color: #fcfcfc;
        border-bottom: 1px solid #e9ecef;
        padding: 12px 20px;
        font-weight: 600;
        color: #0056b3;
        font-size: 14px;
    }
    .settings-card .card-body {
        padding: 20px;
        background-color: #fff;
    }
    .settings-description {
        font-size: 12px;
        color: #6c757d;
        margin-bottom: 15px;
    }
    .form-switch .form-check-input {
        width: 2.5em;
        margin-left: -2.5em;
    }
    .form-switch .form-check-label {
        padding-left: 0.5em;
        font-size: 13px;
        color: #333;
        font-weight: 500;
    }
    .sub-option {
        margin-left: 2.5em;
        margin-top: 8px;
    }
    .radio-group {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Leaves & Attendance</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Settings</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Attendance Settings</h4>
            <p class="text-muted fs-13 mb-0">Configure attendance settings for present, absent, half-day and sandwich rules.</p>
        </div>
        <div class="header-buttons">
            <button class="btn btn-success btn-sm border-0"><i class="ri-book-read-line"></i> Read Help</button>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('attendance-settings.store') }}" method="POST">
    @csrf

    <!-- Default Attendance -->
    <div class="card settings-card">
        <div class="card-header">
            Default Attendance
        </div>
        <div class="card-body">
            <div class="radio-group">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="default_attendance" id="defaultPresent" value="Present" {{ (!isset($settings) || $settings->default_attendance == 'Present') ? 'checked' : '' }}>
                    <label class="form-check-label fs-13" for="defaultPresent">
                        Present
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="default_attendance" id="defaultAbsent" value="Absent" {{ (isset($settings) && $settings->default_attendance == 'Absent') ? 'checked' : '' }}>
                    <label class="form-check-label fs-13" for="defaultAbsent">
                        Absent
                    </label>
                </div>
            </div>

            <div class="form-check form-switch mt-3">
                <input class="form-check-input" type="checkbox" id="mark_out_every_second_punch" name="mark_out_every_second_punch" value="1" {{ (isset($settings) && $settings->mark_out_every_second_punch) ? 'checked' : '' }}>
                <label class="form-check-label" for="mark_out_every_second_punch">Mark Out on every 2nd punch</label>
            </div>
        </div>
    </div>

    <!-- Manual Attendance -->
    <div class="card settings-card">
        <div class="card-header">
            Manual Attendance
        </div>
        <div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="manual_attendance_enabled" name="manual_attendance_enabled" value="1" {{ (isset($settings) && $settings->manual_attendance_enabled) ? 'checked' : '' }}>
                <label class="form-check-label" for="manual_attendance_enabled">Enable Manual Attendance</label>
            </div>
            <p class="settings-description mt-2 mb-0"><i class="ri-information-line"></i> Enable this option to manually capture total Presents, Absents & Leaves every month and calculate Payroll based on the manual capture.</p>
        </div>
    </div>

    <!-- Holiday Sandwich Rule -->
    <div class="card settings-card">
        <div class="card-header">
            Holiday Sandwich Rule
        </div>
        <div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="holiday_sandwich_rule" name="holiday_sandwich_rule" value="1" {{ (isset($settings) && $settings->holiday_sandwich_rule) ? 'checked' : '' }}>
                <label class="form-check-label" for="holiday_sandwich_rule">Do not allow Holiday if Absent on next/previous day</label>
            </div>
            
            <div class="sub-option form-check form-switch">
                <input class="form-check-input" type="checkbox" id="holiday_absent_both_days" name="holiday_absent_both_days" value="1" {{ (isset($settings) && $settings->holiday_absent_both_days) ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="holiday_absent_both_days">Apply only if absent on BOTH next and previous day</label>
            </div>
            
            <div class="sub-option form-check form-switch">
                <input class="form-check-input" type="checkbox" id="holiday_restrict_one_day" name="holiday_restrict_one_day" value="1" {{ (isset($settings) && $settings->holiday_restrict_one_day) ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="holiday_restrict_one_day">Restrict to only one day before/after absent</label>
                <div class="settings-description mt-1 ms-4">(applicable only if above setting is off)</div>
            </div>
        </div>
    </div>

    <!-- Week Off Sandwich Rule -->
    <div class="card settings-card">
        <div class="card-header">
            Week Off Sandwich Rule
        </div>
        <div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="week_off_sandwich_rule" name="week_off_sandwich_rule" value="1" {{ (isset($settings) && $settings->week_off_sandwich_rule) ? 'checked' : '' }}>
                <label class="form-check-label" for="week_off_sandwich_rule">Do not allow Week Off if Absent on the next/previous day</label>
            </div>
            
            <div class="sub-option form-check form-switch">
                <input class="form-check-input" type="checkbox" id="week_off_absent_both_days" name="week_off_absent_both_days" value="1" {{ (isset($settings) && $settings->week_off_absent_both_days) ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="week_off_absent_both_days">Apply only if absent on BOTH next and previous day</label>
            </div>
            
            <div class="sub-option form-check form-switch">
                <input class="form-check-input" type="checkbox" id="week_off_restrict_one_day" name="week_off_restrict_one_day" value="1" {{ (isset($settings) && $settings->week_off_restrict_one_day) ? 'checked' : '' }}>
                <label class="form-check-label text-muted" for="week_off_restrict_one_day">Restrict to only one day before/after absent</label>
                <div class="settings-description mt-1 ms-4">(applicable only if above setting is off)</div>
            </div>
        </div>
    </div>

    <div class="mb-5 pb-5">
        <button type="submit" class="btn btn-primary px-4"><i class="ri-save-line me-1"></i> Save Settings</button>
    </div>

</form>

@endsection

@section('script')
<script>
$(document).ready(function () {
    // Toggle sub-options for Holiday
    function toggleHolidaySub() {
        let isChecked = $('#holiday_sandwich_rule').is(':checked');
        $('#holiday_absent_both_days, #holiday_restrict_one_day').prop('disabled', !isChecked);
    }

    // Toggle sub-options for Week Off
    function toggleWeekOffSub() {
        let isChecked = $('#week_off_sandwich_rule').is(':checked');
        $('#week_off_absent_both_days, #week_off_restrict_one_day').prop('disabled', !isChecked);
    }

    $('#holiday_sandwich_rule').on('change', toggleHolidaySub);
    $('#week_off_sandwich_rule').on('change', toggleWeekOffSub);

    // Initial load
    toggleHolidaySub();
    toggleWeekOffSub();
});
</script>
@endsection
