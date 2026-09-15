@extends('layouts.master')

@section('title')
    Manage Business Unit Images
@endsection

@section('content')
<div class="page-content">
    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-1">
                            Manage Images: {{ $businessUnit->unit_name }}
                        </h4>
                        <p class="text-muted mb-0">
                            Upload or update the header and footer images for this business unit.
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

                        <form action="{{ route('business-units.update-images', $businessUnit->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row g-3">

                                <!-- Header Image -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Header Image
                                    </label>

                                    @if($businessUnit->header_image)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($businessUnit->header_image) }}" alt="Header Image" class="img-fluid border" style="max-height: 100px;">
                                        </div>
                                    @endif

                                    <input type="file"
                                           name="header_image"
                                           class="form-control"
                                           accept="image/*">
                                    <small class="text-muted">Allowed formats: jpeg, png, jpg, gif. Max size: 2MB.</small>
                                </div>

                                <!-- Footer Image -->
                                <div class="col-md-6">
                                    <label class="form-label">
                                        Footer Image
                                    </label>

                                    @if($businessUnit->footer_image)
                                        <div class="mb-2">
                                            <img src="{{ Storage::url($businessUnit->footer_image) }}" alt="Footer Image" class="img-fluid border" style="max-height: 100px;">
                                        </div>
                                    @endif

                                    <input type="file"
                                           name="footer_image"
                                           class="form-control"
                                           accept="image/*">
                                    <small class="text-muted">Allowed formats: jpeg, png, jpg, gif. Max size: 2MB.</small>
                                </div>

                            </div> <!-- End Row -->

                            <div class="mt-4">
                                <button type="submit" class="btn btn-success w-md">
                                    Save Images
                                </button>

                                <a href="{{ route('business-units.index') }}"
                                   class="btn btn-secondary w-md">
                                    Cancel
                                </a>
                            </div>

                        </form>

                    </div> <!-- end card body -->
                </div> <!-- end card -->

            </div>
        </div>

    </div>
</div>
@endsection
