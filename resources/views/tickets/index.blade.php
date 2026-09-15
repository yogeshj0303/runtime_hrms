@extends('layouts.master')

@section('title')
    Helpdesk Tickets
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Helpdesk Tickets</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Support Tickets</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Subject</th>
                            <th>Department</th>
                            <th>Creator</th>
                            <th>Assignee</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td>#{{ $ticket->id }}</td>
                            <td>{{ $ticket->subject }}</td>
                            <td>{{ $ticket->department->name ?? 'N/A' }}</td>
                            <td>{{ $ticket->creator->first_name ?? '' }} {{ $ticket->creator->last_name ?? '' }}</td>
                            <td>{{ $ticket->assignee->first_name ?? 'Unassigned' }} {{ $ticket->assignee->last_name ?? '' }}</td>
                            <td>
                                <span class="badge 
                                    @if($ticket->priority == 'High' || $ticket->priority == 'Urgent') bg-danger 
                                    @elseif($ticket->priority == 'Medium') bg-warning 
                                    @else bg-info @endif">
                                    {{ $ticket->priority }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($ticket->status == 'Open') bg-primary 
                                    @elseif($ticket->status == 'Resolved' || $ticket->status == 'Closed') bg-success 
                                    @else bg-secondary @endif">
                                    {{ $ticket->status }}
                                </span>
                            </td>
                            <td>{{ $ticket->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-primary">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No tickets found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
