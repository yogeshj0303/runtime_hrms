@extends('layouts.master')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-1">Broadcast Alert</h4>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#">HR Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Broadcast Alert</li>
            </ol>
        </nav>
    </div>
</div>

<div class="card">
    <div class="card-header bg-light">
        <strong>Send Custom Alert</strong>
        <p class="text-muted small mb-0">This will send an alert notification to all active employees in your current business. They will see it in their mobile app.</p>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('hr.alerts.broadcast.store') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="form-label">Alert Message</label>
                <textarea name="message" class="form-control" rows="4" placeholder="Enter the message you want to broadcast..." required></textarea>
                <div class="form-text">Keep it concise. E.g., "Office will remain closed tomorrow due to heavy rain."</div>
            </div>

            <button type="submit" class="btn btn-primary" onclick="return confirm('Are you sure you want to send this alert to all active employees?');">
                <i class="ri-broadcast-line"></i> Broadcast Alert Now
            </button>
        </form>
    </div>
</div>

@endsection
