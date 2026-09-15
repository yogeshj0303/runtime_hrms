@extends('layouts.master')

@section('title') Income Tax Exemptions @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Data Capture - Deduction - TDS @endslot
    @slot('title') Income Tax Exemptions @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Capture amount of exempted allowances to avoid tax calculation on same.</h5>
                    <div class="flex-shrink-0">
                        <button class="btn btn-success btn-sm">Need Help</button>
                    </div>
                </div>
            </div>
            
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form method="GET" action="{{ route('capture.itexemptions') }}">
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-4">
                            <div>
                                <label class="form-label text-muted">Business Unit</label>
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
                    </div>
                    <div class="row g-3 mt-1">
                        <div class="col-xxl-2 col-sm-6">
                            <div>
                                <label class="form-label text-muted d-none">Financial Year</label>
                                <div class="input-group">
                                    <div class="input-group-text"><i class="ri-calendar-event-line"></i></div>
                                    <select name="financial_year" class="form-control" required>
                                        @php
                                            $currentYear = date('Y');
                                            $years = [
                                                ($currentYear-1) . '-' . substr($currentYear, -2),
                                                $currentYear . '-' . substr($currentYear+1, -2),
                                                ($currentYear+1) . '-' . substr($currentYear+2, -2)
                                            ];
                                            $selectedYear = request('financial_year', $currentYear . '-' . substr($currentYear+1, -2));
                                        @endphp
                                        @foreach($years as $yr)
                                            <option value="{{ $yr }}" {{ $selectedYear == $yr ? 'selected' : '' }}>{{ $yr }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-6">
                            <div>
                                <label class="form-label text-muted d-none">Search Employee</label>
                                <div class="input-group">
                                    <input type="text" name="employee_search" class="form-control" placeholder="Search Employee Name" value="{{ request('employee_search') }}">
                                    <button type="submit" class="btn btn-primary"><i class="ri-search-line me-1"></i> Load</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                @if(request()->has('financial_year'))
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 50px;">SN</th>
                                    <th scope="col">EMPLOYEE NAME</th>
                                    <th scope="col">POSITION</th>
                                    <th scope="col" class="text-end">GROSS SALARY</th>
                                    <th scope="col" class="text-end">CALCULATED EXEMPTIONS</th>
                                    <th scope="col" style="width: 200px;">ADDITIONAL EXEMPTIONS</th>
                                    <th scope="col" class="text-end">NET SALARY</th>
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
                                        <td class="text-end text-dark">0.00</td>
                                        <td class="text-end text-dark">0.00</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm inline-input text-end" id="exemption_{{ $employee->id }}" value="{{ $employee->additional_exemptions }}" step="0.01" min="0">
                                        </td>
                                        <td class="text-end text-dark">0.00</td>
                                        <td>
                                            <button class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 inline-save-btn" data-emp-id="{{ $employee->id }}" style="width: 24px; height: 24px; padding: 0;" title="Save">
                                                <i class="mdi mdi-check fs-12"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No employees found matching the criteria.</td>
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
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // AJAX save IT Exemptions
        $(document).on('click', '.inline-save-btn', function(e) {
            e.preventDefault();
            
            var btn = $(this);
            var empId = btn.data('emp-id');
            var val = $('#exemption_' + empId).val();
            var finYear = $('select[name="financial_year"]').val();
            
            var originalBtnHtml = btn.html();
            
            btn.html('<i class="mdi mdi-loading mdi-spin fs-12"></i>').prop('disabled', true);

            $.ajax({
                url: '{{ route('capture.itexemptions.store') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    employee_id: empId,
                    financial_year: finYear,
                    additional_exemptions: val
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
    });
</script>
@endsection
