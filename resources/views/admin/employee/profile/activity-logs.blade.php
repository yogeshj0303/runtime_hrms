@extends('admin.employee.profile.layout')

@section('profile_title', 'Activity Logs')
@section('profile_description', 'Chronological audit trail and change logs for this employee.')

@section('profile_content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h6 class="fw-bold mb-0 text-dark">Audit Trail ({{ $logs->total() }} events recorded)</h6>
</div>

@if($logs->count() > 0)
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 180px;">Date & Time</th>
                    <th>Action</th>
                    <th>Description</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td class="text-muted small">
                            {{ $log->created_at ? $log->created_at->format('d-M-Y h:i A') : 'N/A' }}
                        </td>
                        <td>
                            <span class="badge bg-soft-primary text-primary px-2 py-1">{{ $log->action ?? 'Update' }}</span>
                        </td>
                        <td class="text-dark">{{ $log->description ?? 'Record modified' }}</td>
                        <td class="text-muted small">{{ $log->ip ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
@else
    <div class="text-center py-5">
        <i class="ri-history-line text-muted" style="font-size: 48px;"></i>
        <h6 class="mt-3 text-dark fw-bold">No Activity Logs Found</h6>
        <p class="text-muted small">All employee profile changes and system actions will automatically be logged here.</p>
    </div>
@endif
@endsection