@extends('layouts.master')

@section('title')
    Create Business Unit
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">
                            Create Business Unit
                        </h4>
                        <p class="text-muted mb-0">
                            Create and manage business units within your organization.
                        </p>
                    </div>

                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('business-units.store') }}" method="POST">
                            @csrf

                            <div class="row g-3">

                                <!-- Unit Name -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Unit Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="unit_name"
                                           class="form-control"
                                           value="{{ old('unit_name') }}"
                                           placeholder="Enter Unit Name"
                                           required>
                                </div>

                                <!-- Report Title -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Report Title <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                           name="report_title"
                                           class="form-control"
                                           value="{{ old('report_title') }}"
                                           placeholder="Enter Report Title"
                                           required>
                                </div>

                                <!-- Sub Header 1 -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Sub Header 1
                                    </label>
                                    <input type="text"
                                           name="sub_header_1"
                                           class="form-control"
                                           value="{{ old('sub_header_1') }}"
                                           placeholder="Enter Sub Header 1">
                                </div>

                                <!-- Sub Header 2 -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Sub Header 2
                                    </label>
                                    <input type="text"
                                           name="sub_header_2"
                                           class="form-control"
                                           value="{{ old('sub_header_2') }}"
                                           placeholder="Enter Sub Header 2">
                                </div>

                                <!-- Footer Line 1 -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Footer Line 1
                                    </label>
                                    <input type="text"
                                           name="footer_line_1"
                                           class="form-control"
                                           value="{{ old('footer_line_1') }}"
                                           placeholder="Enter Footer Line 1">
                                </div>

                                <!-- Footer Line 2 -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Footer Line 2
                                    </label>
                                    <input type="text"
                                           name="footer_line_2"
                                           class="form-control"
                                           value="{{ old('footer_line_2') }}"
                                           placeholder="Enter Footer Line 2">
                                </div>

                                <!-- Default Business Unit -->
                                <div class="col-md-12">
                                    <div class="form-check form-switch mt-3">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               id="is_default"
                                               name="is_default"
                                               value="1">

                                        <label class="form-check-label"
                                               for="is_default">
                                            Set as Default Business Unit
                                        </label>
                                    </div>
                                </div>

                            </div>

                            <div class="mt-4">
                                <button type="submit"
                                        class="btn btn-primary">
                                    Save Business Unit
                                </button>

                                <a href="{{ route('business-units.index') }}"
                                   class="btn btn-light">
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@section('script')
<script src="{{ URL::asset('build/js/app.js') }}"></script>
@endsection