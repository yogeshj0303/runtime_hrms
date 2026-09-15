@extends('layouts.master')

@section('title')
    Ticket #{{ $ticket->id }}
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Ticket #{{ $ticket->id }} - {{ $ticket->subject }}</h4>
            <div class="page-title-right">
                <a href="{{ route('tickets.index') }}" class="btn btn-secondary btn-sm">Back to Tickets</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Ticket Info & Messages -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Ticket Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Description:</strong></p>
                <div class="p-3 bg-light rounded border">
                    {{ $ticket->description }}
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Conversation</h5>
            </div>
            <div class="card-body bg-light" style="max-height: 600px; overflow-y: auto; display: flex; flex-direction: column; gap: 1.5rem; padding: 25px;">
                @forelse($ticket->messages as $msg)
                @php
                    $isMe = $adminEmpId ? ($msg->sender_id == $adminEmpId) : false;
                    $senderName = $msg->sender ? $msg->sender->first_name . ' ' . $msg->sender->last_name : 'Admin';
                    $initials = strtoupper(substr($msg->sender->first_name ?? 'A', 0, 1) . substr($msg->sender->last_name ?? 'D', 0, 1));
                @endphp
                <div class="d-flex w-100 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                    <div class="d-flex {{ $isMe ? 'flex-row-reverse' : 'flex-row' }}" style="max-width: 85%;">
                        <!-- Avatar -->
                        <div class="flex-shrink-0 {{ $isMe ? 'ms-3' : 'me-3' }}">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white shadow-sm" style="width: 42px; height: 42px; font-weight: 600; font-size: 14px; background: {{ $isMe ? 'linear-gradient(135deg, #0ab39c 0%, #089c88 100%)' : 'linear-gradient(135deg, #405189 0%, #354472 100%)' }}; border: 2px solid #fff;">
                                {{ $initials }}
                            </div>
                        </div>
                        
                        <!-- Message Bubble -->
                        <div class="p-3 shadow-sm" style="border-radius: 16px; {{ $isMe ? 'background-color: #d1f3ee; border-top-right-radius: 0; color: #055146;' : 'background-color: #ffffff; border-top-left-radius: 0; color: #333;' }}">
                            <div class="d-flex justify-content-between align-items-baseline mb-2 pb-1 border-bottom {{ $isMe ? 'border-success border-opacity-25' : 'border-light' }}">
                                <strong style="font-size: 14px; font-weight: 600;">{{ $isMe ? 'You' : $senderName }}</strong>
                                <small class="ms-4 {{ $isMe ? 'text-success' : 'text-muted' }}" style="font-size: 11px; font-weight: 500;">
                                    <i class="ri-time-line align-middle me-1"></i>{{ $msg->created_at->format('d M, h:i A') }}
                                </small>
                            </div>
                            <p class="mb-0" style="font-size: 14px; line-height: 1.6;">{{ $msg->message }}</p>
                            
                            @if($msg->attachment_url)
                                <div class="mt-3 pt-2">
                                    <a href="{{ $msg->attachment_url }}" target="_blank" class="btn btn-sm shadow-none {{ $isMe ? 'btn-success bg-opacity-10 text-success border-success border-opacity-25' : 'btn-light border text-primary' }}" style="border-radius: 8px;">
                                        <i class="ri-attachment-2 align-middle fs-15 me-1"></i> View Attached File
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5 my-5">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title rounded-circle bg-white shadow text-primary fs-1">
                            <i class="ri-chat-smile-3-line"></i>
                        </div>
                    </div>
                    <h5 class="text-muted fw-semibold">No messages yet</h5>
                    <p class="text-muted mb-0">Conversation with the mobile app user will appear here.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar: Status, Assign, History -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Management</h5>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                
                <p><strong>Status:</strong> 
                    <span class="badge bg-primary">{{ $ticket->status }}</span>
                </p>
                <p><strong>Priority:</strong> 
                    <span class="badge bg-warning">{{ $ticket->priority }}</span>
                </p>
                <p><strong>Created:</strong> {{ $ticket->created_at->format('d M, Y h:i A') }}</p>
                <hr>
                
                <form action="{{ route('tickets.assign', $ticket->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Assign To</label>
                        <select name="assignee_id" class="form-select" required>
                            <option value="">Select Department Member</option>
                            @foreach($departmentEmployees as $emp)
                                <option value="{{ $emp->id }}" {{ $ticket->assignee_id == $emp->id ? 'selected' : '' }}>
                                    {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Assign Ticket</button>
                </form>

                @if($ticket->status != 'Closed')
                <hr>
                <form action="{{ route('tickets.force_close', $ticket->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to forcefully close this ticket?')">Force Close Ticket</button>
                </form>
                @endif
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Audit History</h5>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush" style="font-size: 13px;">
                    @forelse($ticket->histories as $hist)
                    <li class="list-group-item px-0">
                        <strong>{{ $hist->actor->first_name ?? 'Admin' }}</strong> performed <strong>{{ $hist->action }}</strong>
                        @if($hist->new_value)
                            @php
                                $newValueDisplay = $hist->new_value;
                                if (in_array($hist->action, ['Assigned', 'Re-assigned']) && is_numeric($hist->new_value)) {
                                    $emp = \App\Models\Employee::find($hist->new_value);
                                    if ($emp) {
                                        $newValueDisplay = $emp->first_name . ' ' . $emp->last_name . ' (' . $emp->employee_code . ')';
                                    }
                                }
                            @endphp
                            <br><span class="text-muted">Updated to: {{ $newValueDisplay }}</span>
                        @endif
                        <br><small class="text-muted">{{ $hist->created_at->format('d M h:i A') }}</small>
                    </li>
                    @empty
                    <li class="list-group-item px-0">No history.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
