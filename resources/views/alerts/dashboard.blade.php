@extends('layouts.master')

@section('title') Alert Dashboard @endsection

@section('content')

@php
    $userId = Auth::id();
    $totalAlerts = \App\Models\Alert::where('user_id', $userId)->count();
    $pendingAlerts = \App\Models\Alert::where('user_id', $userId)->where('status', 'pending')->count();
@endphp

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Alert Dashboard</h4>
            <div class="page-title-right">
                <a href="{{ route('alerts.create') }}" class="btn btn-primary"><i class="ri-send-plane-fill me-1 align-bottom"></i> Send Broadcast Alert</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Alerts</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $totalAlerts }}" id="total_alerts">{{ $totalAlerts }}</span></h4>
                        <a href="{{ url('alerts/history') }}" class="text-decoration-underline">View all alerts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-animate">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1 overflow-hidden">
                        <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending</p>
                    </div>
                </div>
                <div class="d-flex align-items-end justify-content-between mt-4">
                    <div>
                        <h4 class="fs-22 fw-semibold ff-secondary mb-4"><span class="counter-value" data-target="{{ $pendingAlerts }}" id="pending_alerts">{{ $pendingAlerts }}</span></h4>
                        <a href="{{ url('alerts/history?status=pending') }}" class="text-decoration-underline">View pending</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
