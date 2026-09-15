@extends('layouts.master')
@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Generate Letter</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">HR</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hr.letters.index') }}">Letters</a></li>
                <li class="breadcrumb-item active" aria-current="page">Generate</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('hr.letters.store') }}" method="POST">
            @csrf
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Select Employee</label>
                    <select name="employee_id" class="form-select select2" required>
                        <option value="">- Select Employee -</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Select Template</label>
                    <select name="letter_template_id" class="form-select" required>
                        <option value="">- Select Template -</option>
                        @foreach($templates as $template)
                            <option value="{{ $template->id }}">{{ $template->title }} ({{ $template->type }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Issued Date</label>
                    <input type="date" name="issued_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Generate Letter Draft</button>
                <a href="{{ route('hr.letters.index') }}" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5', // Requires select2 bootstrap theme if you have it
            width: '100%'
        });
    });
</script>

@endsection
