@extends('layouts.master')

@section('title')
Comp Off Rules
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
        margin-top: 10px;
    }
    .time-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .time-row label {
        font-size: 13px;
        color: #333;
        font-weight: 500;
        min-width: 160px;
    }
    .time-row select {
        width: 80px;
        font-size: 13px;
    }
    .time-row span {
        font-size: 13px;
        color: #6c757d;
    }
    .radio-group {
        display: flex;
        gap: 20px;
        margin-bottom: 12px;
        flex-wrap: wrap;
    }
    .radio-group .form-check-label {
        font-size: 13px;
    }
    .section-divider {
        border-top: 1px solid #f0f0f0;
        margin: 14px 0;
    }
    .sub-fields-disabled {
        opacity: 0.45;
        pointer-events: none;
    }
</style>
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Leaves &amp; Attendance</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Comp Off Rules</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Comp Off Rules</h4>
            <p class="text-muted fs-13 mb-0">Configure compensatory off grant rules for weekly offs, holidays and set lapse rules.</p>
        </div>
        <div class="header-buttons">
            <button type="button" class="btn btn-success btn-sm border-0"><i class="ri-book-read-line"></i> Read Help</button>
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

<form action="{{ route('comp-off-rules.store') }}" method="POST">
    @csrf

    {{-- ─────────────────────────────────────────────────────────────
         WEEKLY OFF RULES
    ───────────────────────────────────────────────────────────────── --}}
    <div class="card settings-card">
        <div class="card-header">
            <i class="ri-calendar-2-line me-1"></i> Weekly Off Rules
        </div>
        <div class="card-body">

            {{-- Auto Grant Toggle --}}
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="wo_auto_grant_comp_off"
                    name="wo_auto_grant_comp_off" value="1"
                    {{ (isset($rule) && $rule->wo_auto_grant_comp_off) ? 'checked' : '' }}>
                <label class="form-check-label" for="wo_auto_grant_comp_off">
                    Auto Grant Comp Off on Weekly Off
                </label>
            </div>

            {{-- Sub-fields --}}
            <div id="wo_sub_fields" class="{{ (!isset($rule) || !$rule->wo_auto_grant_comp_off) ? 'sub-fields-disabled' : '' }}">

                <div class="section-divider"></div>

                {{-- Half Day Minimum Hours --}}
                <div class="time-row">
                    <label>Half Day Minimum Hours</label>
                    <select class="form-select form-select-sm" name="wo_half_day_min_hours" id="wo_half_day_min_hours">
                        @for($h = 0; $h <= 23; $h++)
                            <option value="{{ $h }}" {{ (isset($rule) && $rule->wo_half_day_min_hours == $h) ? 'selected' : ($h == 4 ? 'selected' : '') }}>
                                {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endfor
                    </select>
                    <span>hrs</span>
                    <select class="form-select form-select-sm" name="wo_half_day_min_minutes" id="wo_half_day_min_minutes">
                        @foreach([0, 15, 30, 45] as $m)
                            <option value="{{ $m }}" {{ (isset($rule) && $rule->wo_half_day_min_minutes == $m) ? 'selected' : ($m == 0 ? 'selected' : '') }}>
                                {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endforeach
                    </select>
                    <span>min</span>
                </div>

                {{-- Full Day Minimum Hours --}}
                <div class="time-row">
                    <label>Full Day Minimum Hours</label>
                    <select class="form-select form-select-sm" name="wo_full_day_min_hours" id="wo_full_day_min_hours">
                        @for($h = 0; $h <= 23; $h++)
                            <option value="{{ $h }}" {{ (isset($rule) && $rule->wo_full_day_min_hours == $h) ? 'selected' : ($h == 8 ? 'selected' : '') }}>
                                {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endfor
                    </select>
                    <span>hrs</span>
                    <select class="form-select form-select-sm" name="wo_full_day_min_minutes" id="wo_full_day_min_minutes">
                        @foreach([0, 15, 30, 45] as $m)
                            <option value="{{ $m }}" {{ (isset($rule) && $rule->wo_full_day_min_minutes == $m) ? 'selected' : ($m == 0 ? 'selected' : '') }}>
                                {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endforeach
                    </select>
                    <span>min</span>
                </div>

                <div class="section-divider"></div>

                {{-- Grant Comp Off --}}
                <div class="mb-3">
                    <p class="mb-2" style="font-size:13px;font-weight:500;color:#333;">Grant Comp Off</p>
                    <div class="radio-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="wo_grant_comp_off"
                                id="wo_grant_same_day" value="same_day"
                                {{ (!isset($rule) || $rule->wo_grant_comp_off == 'same_day') ? 'checked' : '' }}>
                            <label class="form-check-label" for="wo_grant_same_day">Same Day</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="wo_grant_comp_off"
                                id="wo_grant_next_day" value="next_day"
                                {{ (isset($rule) && $rule->wo_grant_comp_off == 'next_day') ? 'checked' : '' }}>
                            <label class="form-check-label" for="wo_grant_next_day">Next Day</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="wo_grant_comp_off"
                                id="wo_grant_any_day" value="any_day"
                                {{ (isset($rule) && $rule->wo_grant_comp_off == 'any_day') ? 'checked' : '' }}>
                            <label class="form-check-label" for="wo_grant_any_day">Any Day</label>
                        </div>
                    </div>
                </div>

                {{-- Add to Extra Days --}}
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="wo_add_to_extra_days"
                        name="wo_add_to_extra_days" value="1"
                        {{ (isset($rule) && $rule->wo_add_to_extra_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="wo_add_to_extra_days">
                        Add to Extra Days
                    </label>
                </div>

            </div>{{-- /wo_sub_fields --}}
        </div>
    </div>

    {{-- ─────────────────────────────────────────────────────────────
         HOLIDAY RULES
    ───────────────────────────────────────────────────────────────── --}}
    <div class="card settings-card">
        <div class="card-header">
            <i class="ri-sun-line me-1"></i> Holiday Rules
        </div>
        <div class="card-body">

            {{-- Auto Grant Toggle --}}
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="ho_auto_grant_comp_off"
                    name="ho_auto_grant_comp_off" value="1"
                    {{ (isset($rule) && $rule->ho_auto_grant_comp_off) ? 'checked' : '' }}>
                <label class="form-check-label" for="ho_auto_grant_comp_off">
                    Auto Grant Comp Off on Holiday
                </label>
            </div>

            {{-- Sub-fields --}}
            <div id="ho_sub_fields" class="{{ (!isset($rule) || !$rule->ho_auto_grant_comp_off) ? 'sub-fields-disabled' : '' }}">

                <div class="section-divider"></div>

                {{-- Half Day Minimum Hours --}}
                <div class="time-row">
                    <label>Half Day Minimum Hours</label>
                    <select class="form-select form-select-sm" name="ho_half_day_min_hours" id="ho_half_day_min_hours">
                        @for($h = 0; $h <= 23; $h++)
                            <option value="{{ $h }}" {{ (isset($rule) && $rule->ho_half_day_min_hours == $h) ? 'selected' : ($h == 4 ? 'selected' : '') }}>
                                {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endfor
                    </select>
                    <span>hrs</span>
                    <select class="form-select form-select-sm" name="ho_half_day_min_minutes" id="ho_half_day_min_minutes">
                        @foreach([0, 15, 30, 45] as $m)
                            <option value="{{ $m }}" {{ (isset($rule) && $rule->ho_half_day_min_minutes == $m) ? 'selected' : ($m == 0 ? 'selected' : '') }}>
                                {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endforeach
                    </select>
                    <span>min</span>
                </div>

                {{-- Full Day Minimum Hours --}}
                <div class="time-row">
                    <label>Full Day Minimum Hours</label>
                    <select class="form-select form-select-sm" name="ho_full_day_min_hours" id="ho_full_day_min_hours">
                        @for($h = 0; $h <= 23; $h++)
                            <option value="{{ $h }}" {{ (isset($rule) && $rule->ho_full_day_min_hours == $h) ? 'selected' : ($h == 8 ? 'selected' : '') }}>
                                {{ str_pad($h, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endfor
                    </select>
                    <span>hrs</span>
                    <select class="form-select form-select-sm" name="ho_full_day_min_minutes" id="ho_full_day_min_minutes">
                        @foreach([0, 15, 30, 45] as $m)
                            <option value="{{ $m }}" {{ (isset($rule) && $rule->ho_full_day_min_minutes == $m) ? 'selected' : ($m == 0 ? 'selected' : '') }}>
                                {{ str_pad($m, 2, '0', STR_PAD_LEFT) }}
                            </option>
                        @endforeach
                    </select>
                    <span>min</span>
                </div>

                <div class="section-divider"></div>

                {{-- Grant Comp Off --}}
                <div class="mb-3">
                    <p class="mb-2" style="font-size:13px;font-weight:500;color:#333;">Grant Comp Off</p>
                    <div class="radio-group">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ho_grant_comp_off"
                                id="ho_grant_same_day" value="same_day"
                                {{ (!isset($rule) || $rule->ho_grant_comp_off == 'same_day') ? 'checked' : '' }}>
                            <label class="form-check-label" for="ho_grant_same_day">Same Day</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ho_grant_comp_off"
                                id="ho_grant_next_day" value="next_day"
                                {{ (isset($rule) && $rule->ho_grant_comp_off == 'next_day') ? 'checked' : '' }}>
                            <label class="form-check-label" for="ho_grant_next_day">Next Day</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ho_grant_comp_off"
                                id="ho_grant_any_day" value="any_day"
                                {{ (isset($rule) && $rule->ho_grant_comp_off == 'any_day') ? 'checked' : '' }}>
                            <label class="form-check-label" for="ho_grant_any_day">Any Day</label>
                        </div>
                    </div>
                </div>

                {{-- Add to Extra Days --}}
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="ho_add_to_extra_days"
                        name="ho_add_to_extra_days" value="1"
                        {{ (isset($rule) && $rule->ho_add_to_extra_days) ? 'checked' : '' }}>
                    <label class="form-check-label" for="ho_add_to_extra_days">
                        Add to Extra Days
                    </label>
                </div>

            </div>{{-- /ho_sub_fields --}}
        </div>
    </div>

    {{-- ─────────────────────────────────────────────────────────────
         COMP OFF LAPSE RULES
    ───────────────────────────────────────────────────────────────── --}}
    <div class="card settings-card">
        <div class="card-header">
            <i class="ri-timer-line me-1"></i> Comp Off Lapse Rules
        </div>
        <div class="card-body">

            {{-- Lapse After Days --}}
            <div class="mb-3">
                <label for="lapse_after_days" class="form-label" style="font-size:13px;font-weight:500;color:#333;">
                    Lapse Comp Off After (days)
                </label>
                <div style="display:flex;align-items:center;gap:10px;">
                    <input type="number" class="form-control form-control-sm" id="lapse_after_days"
                        name="lapse_after_days" min="1" max="365"
                        value="{{ isset($rule) ? $rule->lapse_after_days : 30 }}"
                        style="width:100px;">
                    <span class="text-muted" style="font-size:13px;">days after the working date</span>
                </div>
                @error('lapse_after_days')
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $message }}</div>
                @enderror
            </div>

            <div class="section-divider"></div>

            {{-- Lapse at Month End --}}
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="lapse_at_month_end"
                    name="lapse_at_month_end" value="1"
                    {{ (isset($rule) && $rule->lapse_at_month_end) ? 'checked' : '' }}>
                <label class="form-check-label" for="lapse_at_month_end">
                    Lapse at Month End
                </label>
            </div>
            <p class="settings-description mt-2 mb-0">
                <i class="ri-information-line"></i>
                When enabled, unavailed comp off will lapse at the end of the month instead of after the specified number of days.
            </p>

        </div>
    </div>

    <div class="mb-5 pb-5">
        <button type="submit" class="btn btn-primary px-4">
            <i class="ri-save-line me-1"></i> Save Settings
        </button>
    </div>

</form>

@endsection

@section('script')
<script>
$(document).ready(function () {

    // ── Weekly Off sub-fields toggle ──────────────────────────────
    function toggleWoSub() {
        if ($('#wo_auto_grant_comp_off').is(':checked')) {
            $('#wo_sub_fields').removeClass('sub-fields-disabled');
        } else {
            $('#wo_sub_fields').addClass('sub-fields-disabled');
        }
    }

    // ── Holiday sub-fields toggle ─────────────────────────────────
    function toggleHoSub() {
        if ($('#ho_auto_grant_comp_off').is(':checked')) {
            $('#ho_sub_fields').removeClass('sub-fields-disabled');
        } else {
            $('#ho_sub_fields').addClass('sub-fields-disabled');
        }
    }

    $('#wo_auto_grant_comp_off').on('change', toggleWoSub);
    $('#ho_auto_grant_comp_off').on('change', toggleHoSub);

    // Initial state on page load
    toggleWoSub();
    toggleHoSub();

});
</script>
@endsection
