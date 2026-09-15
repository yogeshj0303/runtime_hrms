@extends('layouts.master')

@section('title') Income Tax (TDS) @endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Income Tax (TDS)</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Data Capture</a></li>
                    <li class="breadcrumb-item active">Income Tax (TDS)</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Capture TDS deduction manually, if you have already computed tax liability offline.</h5>
                <div class="dropdown">
                    <button class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#copyPreviousModal">
                        <i class="mdi mdi-content-copy"></i> Copy from Previous Period
                    </button>
                    <a href="#" class="btn btn-success btn-sm">Need Help</a>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filters -->
                <form method="GET" action="{{ route('capture.tds') }}">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label>Business Units</label>
                            <select name="business_unit_id" class="form-select form-select-sm">
                                <option value="">All Business Units</option>
                                @foreach($businessUnits as $bu)
                                    <option value="{{ $bu->id }}" {{ request('business_unit_id') == $bu->id ? 'selected' : '' }}>{{ $bu->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Location</label>
                            <select name="location_id" class="form-select form-select-sm">
                                <option value="">All Locations</option>
                                @foreach($locations as $loc)
                                    <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Department</label>
                            <select name="department_id" class="form-select form-select-sm">
                                <option value="">All Departments</option>
                                @foreach($departments as $dep)
                                    <option value="{{ $dep->id }}" {{ request('department_id') == $dep->id ? 'selected' : '' }}>{{ $dep->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-3">
                            <label>Month</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                <input type="month" name="payroll_month" class="form-control" value="{{ request('payroll_month', date('Y-m')) }}" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Search Employee</label>
                            <div class="input-group input-group-sm">
                                <input type="text" name="employee_search" class="form-control" placeholder="Search employees" value="{{ request('employee_search') }}">
                                <button class="btn btn-secondary" type="submit"><i class="mdi mdi-magnify"></i> Load</button>
                            </div>
                        </div>
                    </div>
                </form>

                @if(request()->has('payroll_month'))
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>SN</th>
                                    <th>EMPLOYEE NAME</th>
                                    <th>POSITION</th>
                                    <th>STATUS <i class="mdi mdi-information text-primary" title="Employee Status"></i></th>
                                    <th style="width: 150px;">AMOUNT</th>
                                    <th style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $employee)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong><br>
                                        <small class="text-muted">{{ $employee->employee_code }}</small>
                                    </td>
                                    <td>
                                        {{ $employee->currentWorkProfile->designation->name ?? 'N/A' }}<br>
                                        <small class="text-muted">{{ $employee->currentWorkProfile->department->name ?? 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge badge-soft-success">
                                            <i class="mdi mdi-circle-medium"></i> Enabled
                                        </span>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm inline-amount text-end" id="amount_{{ $employee->id }}" value="{{ $employee->current_tds_amount }}" step="1">
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 inline-save-btn" 
                                            data-emp-id="{{ $employee->id }}"
                                            style="width: 24px; height: 24px; padding: 0;" title="Save TDS">
                                            <i class="mdi mdi-check fs-12"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No employees found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Alert Box for TDS System Config -->
                    <div class="alert alert-primary d-flex align-items-center mt-4" role="alert">
                        <i class="mdi mdi-information fs-24 me-3 text-primary"></i>
                        <div>
                            <strong>Capturing TDS here will skip system computed income tax calculation for selected employee(s).</strong><br>
                            Global Income Tax Setting: <strong>{{ $isGlobalTdsEnabled ? 'Enabled' : 'Disabled' }}</strong> 
                            <i class="mdi mdi-information-outline text-primary" title="System Income Tax Calculation Status"></i>
                        </div>
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>

<!-- Copy from Previous Period Modal -->
<div class="modal fade" id="copyPreviousModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('capture.tds.copy_previous') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fs-14">Copy TDS from Previous Period</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    <input type="hidden" name="payroll_month" value="{{ request('payroll_month', date('Y-m')) }}">
                    <input type="hidden" name="copy_from_month" id="copy_from_month" value="{{ date('Y-m', strtotime(request('payroll_month', date('Y-m')) . '-01 -1 month')) }}">
                    @if(isset($employees))
                        @foreach($employees as $emp)
                            <input type="hidden" name="employee_ids[]" value="{{ $emp->id }}">
                        @endforeach
                    @endif
                    
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label fs-13 text-muted">Copy From</label>
                        <div class="col-sm-8 d-flex align-items-center">
                            <button type="button" class="btn btn-sm btn-light border" id="btn-prev-month">
                                <i class="mdi mdi-chevron-left"></i>
                            </button>
                            <span class="mx-3 fw-bold fs-13" id="display_from_month"></span>
                            <button type="button" class="btn btn-sm btn-light border" id="btn-next-month">
                                <i class="mdi mdi-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="row align-items-center">
                        <label class="col-sm-4 col-form-label fs-13 text-muted">Copy To</label>
                        <div class="col-sm-8">
                            <span class="fw-bold fs-13">{{ strtoupper(date('M - Y', strtotime(request('payroll_month', date('Y-m')) . '-01'))) }}</span>
                        </div>
                    </div>

                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light btn-sm text-primary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm" {{ (!isset($employees) || count($employees) == 0) ? 'disabled' : '' }}>
                        <i class="mdi mdi-content-copy me-1"></i> Copy
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX save TDS
        $('.inline-save-btn').on('click', function(e) {
            e.preventDefault();
            
            var btn = $(this);
            var empId = btn.data('emp-id');
            var amount = $('#amount_' + empId).val();
            var payrollMonth = $('input[name="payroll_month"]').val();
            
            var originalBtnHtml = btn.html();
            
            if(amount === '') {
                alert('Amount is required');
                return;
            }

            btn.html('<i class="mdi mdi-loading mdi-spin fs-12"></i>').prop('disabled', true);

            $.ajax({
                url: '{{ route('capture.tds.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: empId,
                    payroll_month: payrollMonth,
                    amount: amount
                },
                success: function(response) {
                    // Temporarily show double check for success
                    btn.html('<i class="mdi mdi-check-all fs-12"></i>').prop('disabled', false);
                    setTimeout(function() {
                        btn.html(originalBtnHtml);
                    }, 2000);
                    
                    if(!response.success) {
                        alert(response.message);
                    }
                },
                error: function(xhr) {
                    btn.html(originalBtnHtml).prop('disabled', false);
                    var errMsg = 'Error saving data';
                    if(xhr.responseJSON && xhr.responseJSON.message) {
                        errMsg = xhr.responseJSON.message;
                    }
                    alert(errMsg);
                }
            });
        });

        $(document).on('keypress', '.inline-amount', function(e) {
            if(e.which == 13) { // Enter key
                e.preventDefault();
                var btn = $(this).closest('tr').find('.inline-save-btn');
                btn.click();
            }
        });

        // Modal Copy From logic
        var fromMonthInput = $('#copy_from_month');
        var displayFromMonth = $('#display_from_month');
        var targetMonth = '{{ request('payroll_month', date('Y-m')) }}';
        
        // Formatter (e.g. "2026-07" -> "JUL - 2026")
        function formatMonthDisplay(ymString) {
            if (!ymString) return '';
            var parts = ymString.split('-');
            var year = parts[0];
            var month = parseInt(parts[1], 10) - 1;
            var date = new Date(year, month, 1);
            var monthName = date.toLocaleString('default', { month: 'short' }).toUpperCase();
            return monthName + ' - ' + year;
        }

        function addMonths(ymString, increment) {
            var parts = ymString.split('-');
            var year = parseInt(parts[0], 10);
            var month = parseInt(parts[1], 10) - 1;
            var date = new Date(year, month + increment, 1);
            var newYear = date.getFullYear();
            var newMonth = date.getMonth() + 1;
            return newYear + '-' + (newMonth < 10 ? '0' : '') + newMonth;
        }
        
        function updateDisplay() {
            var val = fromMonthInput.val();
            displayFromMonth.text(formatMonthDisplay(val));
            
            // disable next button if copy_from_month >= targetMonth
            if (val >= targetMonth) {
                $('#btn-next-month').prop('disabled', true);
            } else {
                $('#btn-next-month').prop('disabled', false);
            }
        }

        $('#btn-prev-month').on('click', function() {
            var current = fromMonthInput.val();
            var newMonth = addMonths(current, -1);
            fromMonthInput.val(newMonth);
            updateDisplay();
        });

        $('#btn-next-month').on('click', function() {
            var current = fromMonthInput.val();
            if (current < targetMonth) {
                var newMonth = addMonths(current, 1);
                fromMonthInput.val(newMonth);
                updateDisplay();
            }
        });

        // Initialize display
        updateDisplay();
    });
</script>
@endsection
