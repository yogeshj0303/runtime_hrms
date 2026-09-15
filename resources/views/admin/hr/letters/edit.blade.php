@extends('layouts.master')
@section('content')

<!-- include summernote css/js for rich text editing -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Edit Letter Draft</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">HR</a></li>
                <li class="breadcrumb-item"><a href="{{ route('hr.letters.index') }}">Letters</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <strong>Employee:</strong> {{ $letter->employee->first_name }} {{ $letter->employee->last_name }} <br>
        <strong>Template:</strong> {{ $letter->template->title ?? 'Custom' }}
    </div>
    <div class="card-body">
        <form action="{{ route('hr.letters.update', $letter->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Letter Content</label>
                <textarea name="generated_html" id="summernote" class="form-control">{{ $letter->generated_html }}</textarea>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Draft" {{ $letter->status == 'Draft' ? 'selected' : '' }}>Draft</option>
                        <option value="Published" {{ $letter->status == 'Published' ? 'selected' : '' }}>Published (Visible to Employee)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="{{ route('hr.letters.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

@endsection
