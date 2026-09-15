@extends('layouts.master')

<link rel="stylesheet" href="{{ asset('assets/admin/css/general.css') }}">


@section('content')



<div class="general-wrapper">

    <div class="page-header">

        <div>
            <div class="breadcrumb">
                <a href="#">Setup</a>
                <span>/</span>
                <a href="#">Master Setup</a>
                <span>/</span>
                <span>General Settings</span>
            </div>

            <h2 class="page-title">General Settings</h2>
            <p class="page-subtitle">
                General options and configuration settings
            </p>
        </div>

        <button class="help-btn">
            Read Help
        </button>

    </div>

    <div class="settings-grid">

        {{-- Left Side --}}
        <div>

            <div class="setting-card">

                <h5 class="card-title">Business Bank Details</h5>

                <div class="form-group">
                    <label>Bank Name</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Bank Branch</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Bank IFSC</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Bank Account No.</label>
                    <input type="text" class="form-control">
                </div>

            </div>

            <div class="setting-card mt-4">

                <h5 class="card-title">Statutory Information</h5>

                <div class="form-group">
                    <label>PAN</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>TAN</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>GSTIN</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>ESI Registration</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>PF Registration</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Shop Act Registration</label>
                    <input type="text" class="form-control">
                </div>

                <div class="form-group">
                    <label>Labour Act Registration</label>
                    <input type="text" class="form-control">
                </div>

            </div>

        </div>

        {{-- Right Side --}}
        <div>

            <div class="setting-card">

                <h5 class="card-title">Employee Options</h5>

                <div class="form-group">
                    <label>Default Probation Period</label>
                    <input type="text"
                           value="90"
                           class="form-control small-input">
                </div>

                <div class="form-group">
                    <label>Employee Confirmation</label>

                    <div>
                        <input type="checkbox">
                        Auto Confirm
                    </div>
                </div>

            </div>

            <div class="setting-card mt-4">

                <h5 class="card-title">Employee Additional Info</h5>

                @for($i=1;$i<=10;$i++)
                <div class="form-group">
                    <label>Field {{ $i }}</label>
                    <input type="text"
                           class="form-control"
                           value="Other Info {{ $i }}">
                </div>
                @endfor

            </div>

        </div>

    </div>

    <button class="save-btn">
        Save Settings
    </button>

</div>

@endsection
@section('script')
<script src="{{ asset('assets/admin/js/general.js') }}"></script>
@endsection