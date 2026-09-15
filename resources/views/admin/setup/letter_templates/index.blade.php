@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Letter Templates</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('setup') }}">Setup</a></li>
                <li class="breadcrumb-item active" aria-current="page">Letter Templates</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('setup.letter-templates.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i> Add Template
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($templates as $template)
                    <tr>
                        <td><strong>{{ $template->title }}</strong></td>
                        <td><span class="badge bg-secondary">{{ $template->type }}</span></td>
                        <td>
                            @if($template->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('setup.letter-templates.edit', $template->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('setup.letter-templates.destroy', $template->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">No templates found. Click "Add Template" to create one.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
