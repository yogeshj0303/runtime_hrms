@extends('layouts.master')
@section('title') HR Policies @endsection

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
        <span>Policies</span>
    </div>
    <div class="header-content">
        <div class="header-left">
            <h4>Company HR Policies</h4>
            <p class="text-muted mb-0">View all policies configured in the system</p>
        </div>
    </div>
</div>

<div class="card mt-3">
    <div class="card-body">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs nav-tabs-custom nav-justified" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#leave" role="tab">
                    <span class="d-none d-sm-block">Leave Policies</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#shift" role="tab">
                    <span class="d-none d-sm-block">Shift Policies</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#weekoff" role="tab">
                    <span class="d-none d-sm-block">Week Off Policies</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#overtime" role="tab">
                    <span class="d-none d-sm-block">Overtime Policies</span> 
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#maternity" role="tab">
                    <span class="d-none d-sm-block">Maternity Policies</span> 
                </a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content p-3 text-muted">
            
            <!-- Leave Tab -->
            <div class="tab-pane active" id="leave" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive nowrap align-middle datatable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>LEAVE TYPE</th>
                                <th>POLICY DESCRIPTION</th>
                                <th>GRANT ENABLED</th>
                                <th>LAPSE ENABLED</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($leavePolicies as $policy)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 16px; height: 16px; border-radius: 50%; background-color: {{ $policy->leaveType->color ?? '#ccc' }}; margin-right: 8px;"></div>
                                            <span class="fw-medium">{{ $policy->leaveType->name ?? 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $policy->policy_description ?? 'N/A' }}</td>
                                    <td>
                                        @if($policy->grant_leaves)
                                        <span class="badge bg-success rounded-pill px-3">Yes</span>
                                        @else
                                        <span class="badge bg-secondary rounded-pill px-3">No</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($policy->lapse_leaves)
                                        <span class="badge bg-success rounded-pill px-3">Yes</span>
                                        @else
                                        <span class="badge bg-secondary rounded-pill px-3">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Shift Tab -->
            <div class="tab-pane" id="shift" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive nowrap align-middle datatable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>SR NO.</th>
                                <th>POLICY NAME</th>
                                <th>DESCRIPTION</th>
                                <th>DEFAULT POLICY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shiftPolicies as $key => $policy)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><span class="fw-medium">{{ $policy->name ?? 'N/A' }}</span></td>
                                    <td>{{ $policy->description ?? 'N/A' }}</td>
                                    <td>
                                        @if($policy->is_default)
                                        <span class="badge bg-success rounded-pill px-3">Yes</span>
                                        @else
                                        <span class="badge bg-secondary rounded-pill px-3">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Week Off Tab -->
            <div class="tab-pane" id="weekoff" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive nowrap align-middle datatable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>SR NO.</th>
                                <th>POLICY NAME</th>
                                <th>DESCRIPTION</th>
                                <th>DEFAULT POLICY</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($weekOffPolicies as $key => $policy)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><span class="fw-medium">{{ $policy->name ?? 'N/A' }}</span></td>
                                    <td>{{ $policy->description ?? 'N/A' }}</td>
                                    <td>
                                        @if($policy->is_default)
                                        <span class="badge bg-success rounded-pill px-3">Yes</span>
                                        @else
                                        <span class="badge bg-secondary rounded-pill px-3">No</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Overtime Tab -->
            <div class="tab-pane" id="overtime" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive nowrap align-middle datatable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>SR NO.</th>
                                <th>POLICY NAME</th>
                                <th>SALARY TREATMENT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overtimePolicies as $key => $policy)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><span class="fw-medium">{{ $policy->policy_name ?? 'N/A' }}</span></td>
                                    <td>{{ $policy->salary_treatment ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Maternity Tab -->
            <div class="tab-pane" id="maternity" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped dt-responsive nowrap align-middle datatable" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th>SR NO.</th>
                                <th>CHILD TYPE</th>
                                <th>NORMAL LEAVE WEEKS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($maternityPolicies as $key => $policy)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><span class="fw-medium">{{ \App\Models\MaternityLeavePolicy::CHILD_TYPES[$policy->child_type] ?? 'N/A' }}</span></td>
                                    <td>{{ $policy->normal_leave_weeks ?? 'N/A' }}</td>
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
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script>
$(document).ready(function () {
    $('.datatable').DataTable({
        responsive: true, 
        pageLength: 10 
    });
});
</script>
@endsection
