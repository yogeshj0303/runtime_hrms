@extends('layouts.master')

@section('title')
    Helpdesk Access Requests
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Cross-Department Access Requests</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <h5 class="card-title mb-0">Employee Requests</h5>
                <p class="text-muted mb-0">Manage permissions for employees requesting access to raise tickets in other departments.</p>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif

            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle">
                        <thead class="text-muted table-light">
                            <tr>
                                <th scope="col">Employee</th>
                                <th scope="col">Current Dept</th>
                                <th scope="col">Reason for Request</th>
                                <th scope="col">Requested On</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1">
                                            <h5 class="fs-14 mb-1">{{ $req->employee->first_name ?? 'Unknown' }} {{ $req->employee->last_name ?? '' }}</h5>
                                            <p class="text-muted mb-0">{{ $req->employee->employee_code ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $req->employee->department ?? 'N/A' }}</td>
                                <td><div style="max-width: 300px; white-space: normal;">{{ $req->reason }}</div></td>
                                <td>{{ $req->created_at->format('d M, Y') }}</td>
                                <td>
                                    @if($req->status == 'Pending')
                                        <span class="badge bg-warning-subtle text-warning">{{ $req->status }}</span>
                                    @elseif($req->status == 'Approved')
                                        <span class="badge bg-success-subtle text-success">{{ $req->status }}</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger">{{ $req->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($req->status == 'Pending')
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('tickets.approve_access', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                        </form>
                                        <form action="{{ route('tickets.reject_access', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                        </form>
                                    </div>
                                    @else
                                        <span class="text-muted">Action Taken</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No access requests found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
