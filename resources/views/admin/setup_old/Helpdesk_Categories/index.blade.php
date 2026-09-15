@extends('layouts.master')

<link rel="stylesheet" href="{{ asset('assets/admin/css/helpdesk.css') }}">

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Master Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Helpdesk Categories</span>
    </div>

    <div class="header-content">
        <div>
            <h4>Helpdesk Categories</h4>
            <p>Configure categories for which employees can submit helpdesk tickets</p>
        </div>

        <div class="header-buttons">
            <button class="btn-add">
                <i class="ri-add-line"></i> Add New
            </button>

            <button class="btn-help">
                <i class="ri-book-open-line"></i> Read Help
            </button>
        </div>
    </div>
</div>
<div class="card">
  

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>NAME</th>
                        <th>PRIMARY APPROVER</th>
                        <th>BACKUP APPROVER</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Attendance Queries</td>
                        <td>Not Defined</td>
                        <td>Not Defined</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>Income Tax Queries</td>
                        <td>Not Defined</td>
                        <td>Not Defined</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>Payroll Queries</td>
                        <td>Not Defined</td>
                        <td>Not Defined</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>Workman App Queries</td>
                        <td>Not Defined</td>
                        <td>Not Defined</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-primary">
                                <i class="ri-pencil-line"></i>
                            </a>
                            <a href="#" class="btn btn-sm btn-danger">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

@section('script')
<script src="{{ asset('assets/admin/js/helpdesk.js') }}"></script>
@endsection

