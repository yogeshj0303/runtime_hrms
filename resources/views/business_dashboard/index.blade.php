@extends('layouts.master')

@section('title')
    Business List
@endsection

@section('css')

<style>

.business-card{
    border:none;
    border-radius:14px;
    box-shadow:0 2px 10px rgba(15,23,42,.05);
}

.business-card .card-body{
    padding:24px;
}

.business-title{
    font-size:32px;
    font-weight:700;
    color:#1e293b;
}

.business-subtitle{
    color:#64748b;
    font-size:15px;
}

.business-name{
    font-size:20px;
    font-weight:700;
    color:#111827;
}

.business-badge{
    border:1px solid #dbeafe;
    background:#f8fafc;
    color:#334155;
    padding:5px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
}

.business-label{
    color:#94a3b8;
    font-size:13px;
    margin-bottom:5px;
    display:block;
}

.business-value{
    font-size:18px;
    font-weight:600;
    color:#111827;
}

.btn-business{
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    padding:8px 16px;
}

.btn-open{
    background:#3b82f6;
    border:none;
    color:#fff;
}

.btn-open:hover{
    background:#2563eb;
    color:#fff;
}

.btn-light{
    border:1px solid #e2e8f0;
}

</style>

@endsection

@section('content')

<div class="row">

    <div class="col-xl-12">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="business-title mb-1">
                    Business List
                </h3>

                <p class="business-subtitle mb-0">
                    View and manage your registered business accounts.
                </p>

            </div>

            <a href="{{ route('business.add') }}" class="btn btn-primary btn-business">

                <i class="ri-add-line align-middle me-1"></i>

                Add Business

            </a>

        </div>

    @if($businesses->count())

    @foreach($businesses as $business)

        <div class="card business-card mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start flex-wrap">

                    <div>

                        <h4 class="business-name mb-2">
                            {{ $business->business_name }}
                        </h4>

                        <div class="d-flex gap-2 flex-wrap">

                            <span class="business-badge">
                                PAN : {{ $business->pan_number ?? 'N/A' }}
                            </span>

                            <span class="business-badge">
                                {{ $business->state ?? 'N/A' }}
                            </span>

                            <span class="business-badge">
                                {{ $business->district ?? 'N/A' }}
                            </span>

                        </div>

                    </div>

                    <div class="text-end mt-2 mt-md-0">

                        <small class="business-label">
                            Status
                        </small>

                        <span class="badge bg-success">
                            {{ ucfirst($business->status) }}
                        </span>

                    </div>

                </div>

                <hr class="my-4">

                <div class="row">

                    <div class="col-md-3">
                        <span class="business-label">
                            City
                        </span>

                        <div class="business-value">
                            {{ $business->city }}
                        </div>
                    </div>

                    <div class="col-md-3">
                        <span class="business-label">
                            Pincode
                        </span>

                        <div class="business-value">
                            {{ $business->pincode }}
                        </div>
                    </div>

                    <div class="col-md-6">
                        <span class="business-label">
                            Constitution
                        </span>

                        <div class="business-value">
                            {{ $business->business_constitution }}
                        </div>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2 flex-wrap">

                    <a href="{{ route('business.show',$business->id) }}"
                       class="btn btn-info btn-business">
                        Open
                    </a>

                    <a href="{{ route('business.edit',$business->id) }}"
                       class="btn btn-warning btn-business">
                        Edit
                    </a>

                    <a href="javascript:void(0)"
   class="btn btn-danger btn-business delete-business"
   data-url="{{ route('business.delete',$business->id) }}">
    Delete
</a>

                </div>

            </div>

        </div>

    @endforeach

@else

    <div class="card">
        <div class="card-body text-center py-5">

            <h5>No Business Found</h5>

            <p class="text-muted">
                Click "Add Business" to create your first business.
            </p>

            <a href="{{ route('business.add') }}"
               class="btn btn-primary">
                Add Business
            </a>

        </div>
    </div>

@endif

    </div>

</div>

@endsection
@section('script')

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function(){

    $('.delete-business').click(function(){

        let deleteUrl = $(this).data('url');

        Swal.fire({
            title: 'Delete Business?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {

            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }

        });

    });

});
</script>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Success',
    text: '{{ session("success") }}',
    timer: 2500,
    showConfirmButton: false
});
</script>
@endif

@endsection