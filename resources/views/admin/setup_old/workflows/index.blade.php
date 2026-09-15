@extends('layouts.master')

<link rel="stylesheet" href="{{ asset('assets/admin/css/workflows.css') }}">

@section('content')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">


<div class="helpdesk-header">
    
    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Master Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Workflows</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Workflows</h4>
            <p>Define custom Workflows to automate common approval tasks</p>
        </div>

        <div class="header-buttons">

            <button class="btn-add">
                <i class="ri-add-line"></i>
                Add New
            </button>

            <button class="btn-video">
                <i class="ri-video-line"></i>
                Video Help
            </button>

            <button class="btn-help">
                <i class="ri-book-open-line"></i>
                Read Help
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
                        <th>FIELDS</th>
                        <th>STEPS</th>
                        <th>ACTIVE</th>
                        <th>ACTIONS</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>Leave Approval Workflow</td>
                        <td>3</td>
                        <td>2</td>
                        <td>Active</td>
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
                        <td>Attendance Workflow</td>
                        <td>4</td>
                        <td>3</td>
                        <td>Active</td>
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
                        <td>Travel Approval Workflow</td>
                        <td>2</td>
                        <td>1</td>
                        <td>Inactive</td>
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
                        <td>Expense Approval Workflow</td>
                        <td>5</td>
                        <td>4</td>
                        <td>Active</td>
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
<script src="{{ asset('assets/admin/js/workflows.js') }}"></script>
@endsection

