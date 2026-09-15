@extends('layouts.master')
@section('content')

<!-- include summernote css/js for rich text editing -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Create Letter Template</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('setup') }}">Setup</a></li>
                <li class="breadcrumb-item"><a href="{{ route('setup.letter-templates.index') }}">Letter Templates</a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('setup.letter-templates.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Template Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Standard Warning Letter" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Template Type</label>
                    <select name="type" class="form-select" required>
                        <option value="">- Select Type -</option>
                        <option value="Warning">Warning</option>
                        <option value="Offer">Offer</option>
                        <option value="Appointment">Appointment</option>
                        <option value="Experience">Experience</option>
                        <option value="Relieving">Relieving</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Letter Content</label>
                <p class="text-muted small">You can use dynamic placeholders like: <code>@{{employee_name}}</code>, <code>@{{employee_code}}</code>, <code>@{{designation}}</code>, <code>@{{department}}</code>, <code>@{{location}}</code>, <code>@{{salary}}</code>, <code>@{{joining_date}}</code>, <code>@{{date}}</code>, <code>@{{business_name}}</code>. The system will automatically replace these with the actual employee data.</p>
                <textarea name="content" id="summernote" class="form-control"></textarea>
            </div>

            <div class="mb-4 form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive" checked>
                <label class="form-check-label" for="isActive">Is Active</label>
            </div>

            <button type="submit" class="btn btn-primary">Save Template</button>
            <a href="{{ route('setup.letter-templates.index') }}" class="btn btn-light">Cancel</a>
        </form>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#summernote').summernote({
            height: 300,
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
