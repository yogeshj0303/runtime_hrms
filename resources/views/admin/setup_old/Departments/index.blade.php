@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('/assets/admin/css/departments.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">


<div class="helpdesk-header">

    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Master Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Departments</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Departments</h4>
            <p>Add or edit departments within your organization</p>
        </div>

        <div class="header-buttons">
            <button class="btn-add">
                <i class="ri-add-line"></i>
                Add New
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

            <table class="table table-bordered align-middle">

                <thead>
                    <tr>
                        <th>NAME</th>
                        <th>HEAD</th>
                        <th>DEPUTY HEAD</th>
                        <th>DEFAULT</th>
                        <th>EMPLOYEES</th>
                        <th width="150">ACTIONS</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>Human Resources</td>
                        <td>Rahul Sharma</td>
                        <td>Priya Verma</td>
                        <td>Yes</td>
                        <td>25</td>
                        <td>
                            <a href="#" class="action-btn edit-btn">
                                <i class="ri-pencil-line"></i>
                            </a>

                            <a href="#" class="action-btn delete-btn">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>Finance</td>
                        <td>Amit Singh</td>
                        <td>Neha Gupta</td>
                        <td>No</td>
                        <td>18</td>
                        <td>
                            <a href="#" class="action-btn edit-btn">
                                <i class="ri-pencil-line"></i>
                            </a>

                            <a href="#" class="action-btn delete-btn">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>Sales</td>
                        <td>Vikas Jain</td>
                        <td>Pooja Mishra</td>
                        <td>No</td>
                        <td>42</td>
                        <td>
                            <a href="#" class="action-btn edit-btn">
                                <i class="ri-pencil-line"></i>
                            </a>

                            <a href="#" class="action-btn delete-btn">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>Marketing</td>
                        <td>Ankit Verma</td>
                        <td>Riya Sharma</td>
                        <td>No</td>
                        <td>21</td>
                        <td>
                            <a href="#" class="action-btn edit-btn">
                                <i class="ri-pencil-line"></i>
                            </a>

                            <a href="#" class="action-btn delete-btn">
                                <i class="ri-delete-bin-line"></i>
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td>IT Department</td>
                        <td>Sandeep Patel</td>
                        <td>Karan Dubey</td>
                        <td>No</td>
                        <td>35</td>
                        <td>
                            <a href="#" class="action-btn edit-btn">
                                <i class="ri-pencil-line"></i>
                            </a>

                            <a href="#" class="action-btn delete-btn">
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
<script src="{{ asset('assets/admin/js/departmets.js') }}"></script>
@endsection