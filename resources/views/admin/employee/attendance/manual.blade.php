@extends('layouts.master')
@section('title') Manual Attendance @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Manual Attendance</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Attendance</a></li>
                    <li class="breadcrumb-item active">Manual Attendance</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-0">
                <h5 class="card-title mb-0">Manual Attendance</h5>
                <p class="text-muted mb-0">Capture number of days present, absent etc. directly without tracking time punches.</p>
            </div>
            
            <div class="card-body bg-light border-bottom border-top border-light">
                <form action="{{ route('attendance.manual') }}" method="GET">
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Business Unit</label>
                            <select name="business_unit_id" class="form-control form-control-sm" data-choices>
                                <option value="">All Units</option>
                                @foreach($businessUnits as $unit)
                                    <option value="{{ $unit->id }}" {{ request('business_unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->name ?? $unit->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Location</label>
                            <select name="location_id" class="form-control form-control-sm" data-choices>
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Cost Center</label>
                            <select name="cost_center_id" class="form-control form-control-sm" data-choices>
                                <option value="">All Cost Centers</option>
                                @foreach($costCenters as $cc)
                                    <option value="{{ $cc->id }}" {{ request('cost_center_id') == $cc->id ? 'selected' : '' }}>{{ $cc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Department</label>
                            <select name="department_id" class="form-control form-control-sm" data-choices>
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div class="row g-3 mt-1">
                        <div class="col-xxl-2 col-sm-6">
                            <label class="fs-12 mb-1">Month</label>
                            <input type="month" name="month_year" class="form-control form-control-sm" value="{{ $monthYear }}" required>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <label class="fs-12 mb-1">Employee</label>
                            <select name="employee_id" class="form-control form-control-sm" data-choices>
                                <option value="">Select Employee</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->user_id }}" {{ $employeeId == $emp->user_id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xxl-2 col-sm-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100"><i class="ri-search-line align-bottom me-1"></i> View</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0 table-borderless table-striped">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 50px;">SN</th>
                                <th scope="col" style="min-width: 250px;">EMPLOYEE</th>
                                <th scope="col" class="text-center" style="width: 70px;">P</th>
                                <th scope="col" class="text-center" style="width: 70px;">A</th>
                                <th scope="col" class="text-center" style="width: 70px;">H</th>
                                <th scope="col" class="text-center" style="width: 70px;">W</th>
                                @foreach($leaveTypes as $leave)
                                    <th scope="col" class="text-center" style="width: 70px;" title="{{ $leave->name }}">{{ $leave->short_name }}</th>
                                @endforeach
                                <th scope="col" class="text-end" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($employees as $index => $emp)
                                @php
                                    $attendance = $manualAttendances->get($emp->user_id);
                                    $leaveDays = $attendance ? ($attendance->leave_days ?? []) : [];
                                @endphp
                                <tr id="row-{{ $emp->user_id }}">
                                    <td>{{ $employees->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1">
                                                <h5 class="fs-14 mb-1">{{ $emp->first_name }} {{ $emp->last_name }}</h5>
                                                <p class="text-muted mb-0 fs-12">{{ $emp->employee_code }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" step="0.5" min="0" max="31" class="form-control form-control-sm text-center present_days" value="{{ $attendance ? $attendance->present_days : '0' }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.5" min="0" max="31" class="form-control form-control-sm text-center absent_days" value="{{ $attendance ? $attendance->absent_days : '0' }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.5" min="0" max="31" class="form-control form-control-sm text-center holiday_days" value="{{ $attendance ? $attendance->holiday_days : '0' }}">
                                    </td>
                                    <td>
                                        <input type="number" step="0.5" min="0" max="31" class="form-control form-control-sm text-center week_off_days" value="{{ $attendance ? $attendance->week_off_days : '0' }}">
                                    </td>
                                    @foreach($leaveTypes as $leave)
                                        <td>
                                            <input type="number" step="0.5" min="0" max="31" class="form-control form-control-sm text-center leave_input" data-leave-id="{{ $leave->id }}" value="{{ isset($leaveDays[$leave->id]) ? $leaveDays[$leave->id] : '0' }}">
                                        </td>
                                    @endforeach
                                    <td class="text-end">
                                        <button type="button" class="btn btn-sm btn-success btn-icon waves-effect waves-light save-btn" data-employee-id="{{ $emp->user_id }}" title="Save">
                                            <i class="ri-save-line"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-info btn-icon waves-effect waves-light history-btn" data-employee-id="{{ $emp->user_id }}" title="History">
                                            <i class="ri-history-line"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 6 + count($leaveTypes) }}" class="text-center">No employees found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $employees->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">Manual Attendance History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Date & Time</th>
                                <th>User</th>
                                <th>Action</th>
                                <th>Changes</th>
                            </tr>
                        </thead>
                        <tbody id="historyTableBody">
                            <!-- History populated via JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const monthYear = "{{ $monthYear }}";
    
    // Save button click
    document.querySelectorAll('.save-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const employeeId = this.getAttribute('data-employee-id');
            const row = document.getElementById('row-' + employeeId);
            
            const present = row.querySelector('.present_days').value;
            const absent = row.querySelector('.absent_days').value;
            const holiday = row.querySelector('.holiday_days').value;
            const weekOff = row.querySelector('.week_off_days').value;
            
            const leaves = {};
            row.querySelectorAll('.leave_input').forEach(input => {
                const leaveId = input.getAttribute('data-leave-id');
                leaves[leaveId] = input.value;
            });
            
            // Show loading state on button
            const originalHtml = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            this.disabled = true;
            
            fetch("{{ route('attendance.manual.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    employee_id: employeeId,
                    month_year: monthYear,
                    present_days: present,
                    absent_days: absent,
                    holiday_days: holiday,
                    week_off_days: weekOff,
                    leaves: leaves
                })
            })
            .then(response => response.json())
            .then(data => {
                this.innerHTML = originalHtml;
                this.disabled = false;
                
                if (data.success) {
                    Toastify({
                        text: "Attendance saved successfully",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "#10c469",
                    }).showToast();
                } else {
                    alert('Error saving data');
                }
            })
            .catch(error => {
                this.innerHTML = originalHtml;
                this.disabled = false;
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    });
    
    // History button click
    const historyModal = new bootstrap.Modal(document.getElementById('historyModal'));
    document.querySelectorAll('.history-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const employeeId = this.getAttribute('data-employee-id');
            const tbody = document.getElementById('historyTableBody');
            
            tbody.innerHTML = '<tr><td colspan="4" class="text-center"><div class="spinner-border text-primary" role="status"></div></td></tr>';
            historyModal.show();
            
            let url = "{{ route('attendance.manual.history', ['employeeId' => ':id']) }}";
            url = url.replace(':id', employeeId) + "?month_year=" + monthYear;
            
            fetch(url)
            .then(response => response.json())
            .then(data => {
                if (!data.history || data.history.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center">No history available</td></tr>';
                    return;
                }
                
                let html = '';
                data.history.forEach(item => {
                    const date = new Date(item.created_at).toLocaleString();
                    const user = item.user ? (item.user.first_name + ' ' + item.user.last_name) : 'System';
                    
                    let changesHtml = '<ul class="mb-0 ps-3">';
                    if (item.action === 'created') {
                        changesHtml += '<li>Initial entry created</li>';
                    } else if (item.changes) {
                        for (const [key, value] of Object.entries(item.changes)) {
                            changesHtml += `<li><strong>${key.replace('_days', '')}</strong>: ${value.old} &rarr; ${value.new}</li>`;
                        }
                    }
                    changesHtml += '</ul>';
                    
                    html += `<tr>
                        <td>${date}</td>
                        <td>${user}</td>
                        <td><span class="badge bg-${item.action === 'created' ? 'success' : 'info'}">${item.action}</span></td>
                        <td>${changesHtml}</td>
                    </tr>`;
                });
                
                tbody.innerHTML = html;
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error loading history</td></tr>';
            });
        });
    });
});
</script>
@endsection
