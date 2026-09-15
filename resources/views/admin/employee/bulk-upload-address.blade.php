@extends('layouts.master')

@section('title')
    Bulk Address Import
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Bulk Updates</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Employee Address</span>
    </div>

    <div class="header-content">
        <div class="header-left">
            <h4>Bulk Address Import</h4>
            <p class="text-muted">
                Download the template, fill in the employee address details, and upload to create or update multiple employee addresses at once.
            </p>
        </div>
        <div class="header-buttons">
            <a href="{{ route('bulk-upload-address.download-template') }}" class="btn btn-primary">
                <i class="ri-download-line align-bottom me-1"></i> Download CSV Template
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="ri-check-double-line label-icon"></i><strong>Success</strong> - {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="ri-alert-line label-icon"></i><strong>Warning</strong> - {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible alert-label-icon label-arrow fade show" role="alert">
                <i class="ri-error-warning-line label-icon"></i><strong>Error</strong> - {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('import_errors') && is_array(session('import_errors')))
            <div class="card border-danger">
                <div class="card-header bg-danger-subtle text-danger">
                    <h6 class="card-title mb-0"><i class="ri-error-warning-line me-1"></i> Import Errors</h6>
                </div>
                <div class="card-body">
                    <ul class="text-danger mb-0">
                        @foreach(session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Upload Address Data File</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('bulk-upload-address.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="form-label text-muted fs-13">Select CSV File</label>
                        <input type="file" class="form-control" name="file" accept=".csv" required>
                        <p class="text-muted fs-12 mt-2">
                            Please ensure the file is in .CSV format and matches the column structure of the template.
                        </p>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-success">
                            <i class="ri-upload-cloud-2-line align-bottom me-1"></i> Upload and Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Instructions & Field Guidelines</h5>
            </div>
            <div class="card-body text-muted">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Mandatory Fields:</h6>
                        <ul>
                            <li><strong>Employee Code:</strong> Must be an existing employee code within your business.</li>
                            <li><strong>Type:</strong> Must be exactly <code>permanent</code> or <code>current</code>.</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Optional Fields:</h6>
                        <ul>
                            <li><strong>Address Line 1:</strong> Primary address details.</li>
                            <li><strong>Address Line 2:</strong> Secondary address details (Apt, Suite, etc).</li>
                            <li><strong>City:</strong> City of residence.</li>
                            <li><strong>State:</strong> State or province.</li>
                            <li><strong>Country:</strong> Country of residence.</li>
                            <li><strong>Zipcode:</strong> Postal code.</li>
                        </ul>
                    </div>
                </div>
                <div class="alert alert-info mt-3 mb-0">
                    <strong>Note:</strong> If an address of the specified <code>Type</code> already exists for the employee, it will be <strong>updated</strong> with the new information. If it doesn't exist, it will be <strong>created</strong>.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
