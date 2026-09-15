@extends('layouts.master')
@section('title') Attendance Requests @endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/assets/admin/css/business.css') }}">
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet">
@endsection

@section('content')

<div class="helpdesk-header">
    <div class="breadcrumb-section">
        <span>HR Management</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Requests</span>
        <i class="ri-arrow-right-s-line"></i>
        <span>Attendance</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>Attendance Requests (Missing Punch)</h4>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#pending" role="tab">
                    <span class="d-none d-sm-block">Pending Requests</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#approved" role="tab">
                    <span class="d-none d-sm-block">Approved Requests</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#rejected" role="tab">
                    <span class="d-none d-sm-block">Rejected Requests</span> 
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content p-3 text-muted">
            
            <!-- Pending Tab -->
            <div class="tab-pane active" id="pending" role="tabpanel">
                <div class="table-responsive">
                    <table id="pending-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Requested In</th>
                                <th>Requested Out</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingRequests as $req)
                                <tr>
                                    <td>{{ $req->employee->name ?? 'N/A' }}</td>
                                    <td>{{ $req->attendance_date }}</td>
                                    <td>{{ $req->requested_punch_in }}</td>
                                    <td>{{ $req->requested_punch_out }}</td>
                                    <td>{{ $req->request_type }}</td>
                                    <td>{{ $req->reason }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $req->id }}">Approve</button>
                                        <button type="button" class="btn btn-sm btn-danger ms-1" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $req->id }}">Reject</button>
                                    </td>
                                </tr>

                                <!-- Approve Modal -->
                                <div class="modal fade" id="approveModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('hr.requests.attendance.action', $req->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Approve Attendance Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Date: <strong>{{ $req->attendance_date }}</strong></p>
                                                    <div class="mb-3">
                                                        <label>Punch In Time</label>
                                                        <input type="time" name="punch_in" class="form-control" value="{{ \Carbon\Carbon::parse($req->requested_punch_in)->format('H:i') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Punch Out Time</label>
                                                        <input type="time" name="punch_out" class="form-control" value="{{ \Carbon\Carbon::parse($req->requested_punch_out)->format('H:i') }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label>Admin Remark <span class="text-danger">*</span></label>
                                                        <textarea name="admin_remark" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success">Approve & Mark Present</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <form action="{{ route('hr.requests.attendance.action', $req->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reject Attendance Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label>Admin Remark <span class="text-danger">*</span></label>
                                                        <textarea name="admin_remark" class="form-control" required></textarea>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-danger">Reject Request</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Approved Tab -->
            <div class="tab-pane" id="approved" role="tabpanel">
                <div class="table-responsive">
                    <table id="approved-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Admin Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approvedRequests as $req)
                                <tr>
                                    <td>{{ $req->employee->name ?? 'N/A' }}</td>
                                    <td>{{ $req->attendance_date }}</td>
                                    <td>{{ $req->request_type }}</td>
                                    <td>{{ $req->reason }}</td>
                                    <td><span class="badge bg-success">Approved</span></td>
                                    <td>{{ $req->admin_remark ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Rejected Tab -->
            <div class="tab-pane" id="rejected" role="tabpanel">
                <div class="table-responsive">
                    <table id="rejected-table" class="table table-bordered table-striped dt-responsive nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Admin Remark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rejectedRequests as $req)
                                <tr>
                                    <td>{{ $req->employee->name ?? 'N/A' }}</td>
                                    <td>{{ $req->attendance_date }}</td>
                                    <td>{{ $req->request_type }}</td>
                                    <td>{{ $req->reason }}</td>
                                    <td><span class="badge bg-danger">Rejected</span></td>
                                    <td>{{ $req->admin_remark ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function () {
    $('#pending-table').DataTable({ responsive: true, pageLength: 10 });
    $('#approved-table').DataTable({ responsive: true, pageLength: 10 });
    $('#rejected-table').DataTable({ responsive: true, pageLength: 10 });
});
</script>
@endsection
