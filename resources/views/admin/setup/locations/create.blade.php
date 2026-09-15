@extends('layouts.master')

@section('title')
Create Location
@endsection

@section('content')

<div class="page-content">
    <div class="container-fluid">


    <div class="row">
        <div class="col-lg-12">

            <div class="card">

                <div class="card-header">
                    <h4 class="card-title mb-1">
                        Create Location
                    </h4>

                    <p class="text-muted mb-0">
                        Create and manage locations within your organization.
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

                    <form action="{{ route('locations.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">

                            <!-- Location Name -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    Location Name
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name') }}"
                                    placeholder="Enter Location Name"
                                    required>
                            </div>

                            <!-- State -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    State
                                    <span class="text-danger">*</span>
                                </label>

                                <input type="text"
                                    name="state"
                                    class="form-control"
                                    value="{{ old('state') }}"
                                    placeholder="Enter State"
                                    required>
                            </div>

                            <!-- Site Head -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    Site Head
                                </label>

                                <input type="text"
                                    name="site_head"
                                    class="form-control"
                                    value="{{ old('site_head') }}"
                                    placeholder="Enter Site Head">
                            </div>

                            <!-- Deputy Head -->
                            <div class="col-md-6">
                                <label class="form-label">
                                    Deputy Head
                                </label>

                                <input type="text"
                                    name="deputy_head"
                                    class="form-control"
                                    value="{{ old('deputy_head') }}"
                                    placeholder="Enter Deputy Head">
                            </div>

                            <!-- Default Location -->
                            <div class="col-md-12">
                                <div class="form-check form-switch mt-3">

                                    <input class="form-check-input"
                                        type="checkbox"
                                        id="is_default"
                                        name="is_default"
                                        value="1">

                                    <label class="form-check-label"
                                        for="is_default">
                                        Set as Default Location
                                    </label>

                                </div>
                            </div>

                        </div>

                        <div class="mt-4">

                            <button type="submit"
                                class="btn btn-primary">
                                Save Location
                            </button>

                            <a href="{{ route('locations.index') }}"
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
