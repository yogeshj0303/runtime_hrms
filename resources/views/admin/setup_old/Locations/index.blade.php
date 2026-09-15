@extends('layouts.master')

@section('content')

<link rel="stylesheet" href="{{ asset('/assets/admin/css/location.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">


<div class="helpdesk-header">

    <div class="breadcrumb-section">
        <span>Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Master Setup</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Locations</span>
    </div>

    <div class="header-content">

        <div class="header-left">
            <h4>Locations</h4>
            <p>Add or edit Locations of your organizaton</p>
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
                        <th>LOCATION</th>
                        <th width="150">ACTIONS</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>Head Office - Gwalior</td>
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
                        <td>Indore Branch</td>
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
                        <td>Bhopal Branch</td>
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
                        <td>Jabalpur Branch</td>
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
                        <td>Delhi Corporate Office</td>
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
<script src="{{ asset('assets/admin/js/location.js') }}"></script>
@endsection