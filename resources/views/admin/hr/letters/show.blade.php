@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">View Letter</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">HR</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hr.letters.index') }}">Letters</a></li>
                <li class="breadcrumb-item active" aria-current="page">View</li>
            </ol>
        </nav>
    </div>
    <div>
        <button class="btn btn-outline-secondary" onclick="window.print()">
            <i class="ri-printer-line"></i> Print
        </button>
        <a href="{{ route('hr.letters.edit', $letter->id) }}" class="btn btn-primary">
            <i class="ri-pencil-line"></i> Edit
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="mb-4">
            <div class="row text-muted small">
                <div class="col-md-3"><strong>Employee:</strong> {{ $letter->employee->first_name }} {{ $letter->employee->last_name }}</div>
                <div class="col-md-3"><strong>Issued On:</strong> {{ \Carbon\Carbon::parse($letter->issued_date)->format('d M, Y') }}</div>
                <div class="col-md-3"><strong>Status:</strong> 
                    @if($letter->status === 'Published')
                        <span class="badge bg-success">Published</span>
                    @else
                        <span class="badge bg-secondary">Draft</span>
                    @endif
                </div>
                <!-- removed signatory -->
            </div>
            <hr>
        </div>

        <div class="p-4 bg-white border" style="min-height: 500px;">
            {!! $letter->generated_html !!}
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .card-body, .card-body * {
        visibility: visible;
    }
    .card-body {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    .btn { display: none; }
}
</style>

@endsection
