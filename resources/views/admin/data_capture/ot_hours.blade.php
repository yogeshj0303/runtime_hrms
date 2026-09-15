@extends('layouts.master')

@section('title') OT Hours @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Data Capture @endslot
    @slot('title') OT Hours @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Capture OT hours (add or deduct) to adjust final OT calculation.</h5>
                    <div class="flex-shrink-0">
                        <button class="btn btn-dark btn-sm me-1" data-bs-toggle="modal" data-bs-target="#copyPreviousModal">
                            <i class="mdi mdi-content-copy me-1"></i> Copy from Previous Period
                        </button>
                        <button class="btn btn-success btn-sm">Need Help</button>
                    </div>
                </div>
            </div>
            
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form method="GET" action="{{ route('capture.othours') }}">
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label class="form-label text-muted">Business Units</label>
                                <select name="business_unit_id" class="form-control" data-choices data-choices-search-false>
                                    <option value="">All Business Units</option>
                                    @foreach($businessUnits as $bu)
                                        <option value="{{ $bu->id }}" {{ request('business_unit_id') == $bu->id ? 'selected' : '' }}>{{ $bu->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label class="form-label text-muted">Location</label>
                                <select name="location_id" class="form-control" data-choices data-choices-search-false>
                                    <option value="">All Locations</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}" {{ request('location_id') == $loc->id ? 'selected' : '' }}>{{ $loc->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label class="form-label text-muted">Department</label>
                                <select name="department_id" class="form-control" data-choices data-choices-search-false>
                                    <option value="">All Departments</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-2 col-sm-6">
                            <div>
                                <label class="form-label text-muted">Month</label>
                                <div class="input-group">
                                    <div class="input-group-text"><i class="ri-calendar-event-line"></i></div>
                                    <input type="month" name="payroll_month" class="form-control" value="{{ request('payroll_month', date('Y-m')) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <div>
                                <label class="form-label text-muted">Search Employee</label>
                                <div class="input-group">
                                    <input type="text" name="employee_search" class="form-control" placeholder="Search Employee Name" value="{{ request('employee_search') }}">
                                    <button type="submit" class="btn btn-primary"><i class="ri-search-line"></i> Load</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                @if(request()->has('payroll_month'))
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">SN</th>
                                    <th scope="col">EMPLOYEE NAME</th>
                                    <th scope="col">DESIGNATION</th>
                                    <th scope="col">JOINING DATE</th>
                                    <th scope="col" style="width: 150px;">OT (HH:MM) <i class="mdi mdi-information-outline text-primary"></i></th>
                                    <th scope="col" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $index => $employee)
                                    <tr>
                                        <td class="text-warning">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-medium text-dark">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                            <div class="text-muted fs-12">{{ $employee->employee_code }}</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">{{ $employee->currentWorkProfile->designation->name ?? 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="text-dark">{{ $employee->currentWorkProfile->joining_date ? date('d-M-Y', strtotime($employee->currentWorkProfile->joining_date)) : 'N/A' }}</div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <input type="number" class="form-control form-control-sm inline-input text-end me-1" id="hours_{{ $employee->id }}" value="{{ $employee->current_hours }}" style="width: 60px;" placeholder="HH">
                                                <span>:</span>
                                                <input type="number" class="form-control form-control-sm inline-input text-end ms-1" id="minutes_{{ $employee->id }}" value="{{ $employee->current_minutes }}" style="width: 60px;" placeholder="MM" min="0" max="59">
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 inline-save-btn" data-emp-id="{{ $employee->id }}" style="width: 24px; height: 24px; padding: 0;" title="Save">
                                                <i class="mdi mdi-check fs-12"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No employees found matching the criteria.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted p-4">
                        Please load data using the filters above.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Copy from Previous Period Modal -->
<div class="modal fade" id="copyPreviousModal" tabindex="-1" aria-labelledby="copyPreviousModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('capture.othours.copy') }}" method="POST">
            @csrf
            <input type="hidden" name="payroll_month" value="{{ request('payroll_month', date('Y-m')) }}">
            
            @if(isset($employees))
                @foreach($employees as $employee)
                    <input type="hidden" name="employee_ids[]" value="{{ $employee->id }}">
                @endforeach
            @endif

            <div class="modal-content border-0">
                <div class="modal-header bg-soft-primary p-3">
                    <h5 class="modal-title" id="copyPreviousModalLabel">Copy from Previous Period</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">You are about to copy OT Hours from a selected previous month into the current target month for all <strong class="text-primary">{{ isset($employees) ? count($employees) : 0 }}</strong> loaded employees.</p>
                    
                    <div class="row align-items-center mb-3">
                        <div class="col-sm-4 text-sm-end text-muted">Copy From:</div>
                        <div class="col-sm-8">
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-sm btn-soft-secondary" id="btn-prev-month"><i class="mdi mdi-chevron-left"></i></button>
                                <div class="px-3 fw-bold flex-grow-1 text-center" id="display_from_month"></div>
                                <button type="button" class="btn btn-sm btn-soft-secondary" id="btn-next-month"><i class="mdi mdi-chevron-right"></i></button>
                                <input type="hidden" name="copy_from_month" id="copy_from_month" value="{{ date('Y-m', strtotime(request('payroll_month', date('Y-m')) . '-01 -1 month')) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row align-items-center mb-2">
                        <div class="col-sm-4 text-sm-end text-muted">Target Month:</div>
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
        // AJAX save OT Hours
        $(document).on('click', '.inline-save-btn', function(e) {
            e.preventDefault();
            
            var btn = $(this);
            var empId = btn.data('emp-id');
            var hours = $('#hours_' + empId).val();
            var minutes = $('#minutes_' + empId).val();
            var payrollMonth = $('input[name="payroll_month"]').val();
            
            var originalBtnHtml = btn.html();
            
            btn.html('<i class="mdi mdi-loading mdi-spin fs-12"></i>').prop('disabled', true);

            $.ajax({
                url: '{{ route('capture.othours.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: empId,
                    payroll_month: payrollMonth,
                    hours: hours,
                    minutes: minutes
                },
                success: function(response) {
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

        $(document).on('keypress', '.inline-input', function(e) {
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
