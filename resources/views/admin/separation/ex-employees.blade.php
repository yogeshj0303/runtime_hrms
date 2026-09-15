@extends('layouts.master')

@section('title')
    Ex-Employees
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
        <span>Ex-Employees</span>
    </div>

    <div class="header-content d-flex justify-content-between align-items-center">
        <div class="header-left">
            <h4>Ex-Employees</h4>
            <p class="text-muted mb-0">View all finalized exits and perform post-exit actions.</p>
        </div>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="{{ route('separation.ex-employees') }}" method="GET" class="row g-3 align-items-end">
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
                    <option value="Retirement" {{ request('exit_reason') == 'Retirement' ? 'selected' : '' }}>Retirement</option>
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
            <table class="table table-hover align-middle mb-0" id="exEmployeesTable">
                <thead class="table-light">
                    <tr>
                        <th>Employee</th>
                        <th>DOJ</th>
                        <th>DOR</th>
                        <th>DOE (Exit)</th>
                        <th>Notice Period</th>
                        <th>Reason</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exEmployees as $exit)
                    @php
                        $plannedNotice = 30; // Mocked
                        $actualNotice = $exit->resignation_date ? \Carbon\Carbon::parse($exit->resignation_date)->diffInDays($exit->exit_date) : 0;
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $exit->employee->first_name }} {{ $exit->employee->last_name }}</strong><br>
                            <small class="text-muted">{{ $exit->employee->employee_code }}</small>
                        </td>
                        <td>{{ $exit->employee->joining_date ? $exit->employee->joining_date->format('d M, Y') : 'N/A' }}</td>
                        <td>{{ $exit->resignation_date ? $exit->resignation_date->format('d M, Y') : 'N/A' }}</td>
                        <td><span class="text-danger fw-medium">{{ $exit->exit_date->format('d M, Y') }}</span></td>
                        <td>{{ $actualNotice }} / {{ $plannedNotice }} Days</td>
                        <td>{{ $exit->exit_reason }}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light btn-icon" type="button" data-bs-toggle="dropdown">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                    <li>
                                        <a class="dropdown-item text-primary" href="{{ route('separation.download-fnf', $exit->id) }}">
                                            <i class="ri-file-download-line me-2"></i> Download F&F
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('separation.reprocess', $exit->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="ri-refresh-line me-2"></i> Re-process Exit
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('separation.rehire', $exit->id) }}" method="POST" class="form-confirm" data-message="Are you sure you want to re-hire this employee?">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-success">
                                                <i class="ri-user-add-line me-2"></i> Re-hire
                                            </button>
                                        </form>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('employee.toggle-status', $exit->employee->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-warning">
                                                <i class="ri-user-unfollow-line me-2"></i> Deactivate Access
                                            </button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('separation.destroy', $exit->id) }}" method="POST" class="form-confirm" data-message="Delete this separation record permanently?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">
                                                <i class="ri-delete-bin-line me-2"></i> Delete Record
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
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

<script>
    $(document).ready(function() {
        $('#exEmployeesTable').DataTable({
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search ex-employees..."
            },
            order: [[3, 'desc']] // Sort by Exit Date descending
        });

        $('.form-confirm').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            const message = $(this).data('message');
            
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
