@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Employee Letters</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">HR</a></li>
                <li class="breadcrumb-item active" aria-current="page">Letters</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('hr.letters.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i> Generate Letter
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
                        <th>Employee</th>
                        <th>Letter Type</th>
                        <th>Issued Date</th>
                        <th>Status</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $letter)
                    <tr>
                        <td>
                            <strong>{{ $letter->employee->first_name }} {{ $letter->employee->last_name }}</strong><br>
                            <small class="text-muted">{{ $letter->employee->employee_code }}</small>
                        </td>
                        <td>{{ $letter->template->title ?? 'Custom Letter' }}</td>
                        <td>{{ \Carbon\Carbon::parse($letter->issued_date)->format('d M, Y') }}</td>
                        <td>
                            @if($letter->status === 'Published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('hr.letters.show', $letter->id) }}" class="btn btn-sm btn-outline-info">View</a>
                            <a href="{{ route('hr.letters.edit', $letter->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <form action="{{ route('hr.letters.destroy', $letter->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No letters generated yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $letters->links() }}
        </div>
    </div>
</div>

@endsection
