@extends('layouts.master')

@section('title')
    Pending Exits
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

@section('content')

<div class="helpdesk-header mb-4">
    <div class="breadcrumb-section">
        <span>Employees</span>
        <i class="ri-arrow-right-s-line"></i>
        <a href="{{ route('separation.dashboard') }}">Separation & Offboarding</a>
        <i class="ri-arrow-right-s-line"></i>
        <span>Pending Exits</span>
    </div>

    <div class="header-content d-flex justify-content-between align-items-center">
        <div class="header-left">
            <h4>Pending Exits</h4>
            <p class="text-muted mb-0">Manage and process Full & Final settlements for pending exits.</p>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('separation.pending') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Exit Date From</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Exit Date To</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Exit Reason</label>
                <select name="exit_reason" class="form-select">
                    <option value="">All Reasons</option>
                    <option value="Resignation" {{ request('exit_reason') == 'Resignation' ? 'selected' : '' }}>Resignation</option>
                    <option value="Termination" {{ request('exit_reason') == 'Termination' ? 'selected' : '' }}>Termination</option>
                    <option value="Absconding" {{ request('exit_reason') == 'Absconding' ? 'selected' : '' }}>Absconding</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="pendingExitsTable">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>DOJ</th>
                        <th>Resignation Date</th>
                        <th>Exit Date</th>
                        <th>Planned vs Actual Notice</th>
                        <th>Reason</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingExits as $exit)
                    @php
                        $plannedNotice = 30; // Mocked
                        $actualNotice = $exit->resignation_date ? \Carbon\Carbon::parse($exit->resignation_date)->diffInDays($exit->exit_date) : 0;
                        $noticeDiff = $actualNotice - $plannedNotice;
                        $noticeClass = $noticeDiff < 0 ? 'text-danger' : 'text-success';
                    @endphp
                    <tr id="row-{{ $exit->id }}">
                        <td>
                            <strong>{{ $exit->employee->first_name }} {{ $exit->employee->last_name }}</strong><br>
                            <small class="text-muted">{{ $exit->employee->employee_code }}</small>
                        </td>
                        <td>{{ $exit->employee->joining_date ? $exit->employee->joining_date->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $exit->resignation_date ? $exit->resignation_date->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $exit->exit_date->format('d M, Y') }}</td>
                        <td>
                            <div>Planned: {{ $plannedNotice }} Days</div>
                            <div>Actual: {{ $actualNotice }} Days <span class="{{ $noticeClass }} fw-medium">({{ $noticeDiff > 0 ? '+' : '' }}{{ $noticeDiff }})</span></div>
                        </td>
                        <td>{{ $exit->exit_reason }}</td>
                        <td>
                            @if($exit->status == 'Processed')
                                <span class="badge bg-success-subtle text-success">Processed</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning">{{ $exit->status }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($exit->status == 'Processed')
                                <form action="{{ route('separation.finalize', $exit->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="button" class="btn btn-sm btn-success btn-finalize" title="Finalize Exit & Send Relieving Letter">
                                        <i class="ri-mail-send-line me-1"></i> Finalize
                                    </button>
                                </form>
                            @else
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary btn-process" data-id="{{ $exit->id }}" title="Process F&F">
                                        Process F&F
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger btn-cancel" data-id="{{ $exit->id }}" title="Cancel Exit">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    $(document).ready(function() {
        $('#pendingExitsTable').DataTable({
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search exits..."
            },
            order: [[3, 'asc']] // Sort by Exit Date
        });

        // Process F&F
        $('.btn-process').click(function() {
            const id = $(this).data('id');
            const row = $('#row-' + id);
            
            Swal.fire({
                title: 'Process Full & Final Settlement?',
                text: "Make sure you have completed Leave encashment & Gratuity.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, process it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/employee/separation/${id}/process`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if(response.success) {
                                Toastify({ text: response.message, backgroundColor: "#198754" }).showToast();
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        }
                    });
                }
            });
        });

        // Cancel Exit
        $('.btn-cancel').click(function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Cancel this exit request?',
                text: "This will mark the separation as cancelled.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                confirmButtonText: 'Yes, cancel it'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/employee/separation/${id}/cancel`,
                        type: 'POST',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if(response.success) {
                                Toastify({ text: response.message, backgroundColor: "#dc3545" }).showToast();
                                setTimeout(() => window.location.reload(), 1000);
                            }
                        }
                    });
                }
            });
        });

        // Finalize Exit
        $('.btn-finalize').click(function() {
            const form = $(this).closest('form');
            Swal.fire({
                title: 'Finalize Exit?',
                text: "This will generate the Relieving Letter, email it to the employee, and mark them as an ex-employee in the system.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                confirmButtonText: 'Finalize & Send Email'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
