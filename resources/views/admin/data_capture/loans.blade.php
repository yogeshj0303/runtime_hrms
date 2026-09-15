@extends('layouts.master')

@section('title') Loans @endsection

@section('content')
@component('components.breadcrumb')
    @slot('li_1') Data Capture @endslot
    @slot('title') Loans @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <h5 class="card-title mb-0 flex-grow-1">Issue new employee loans and manage existing loans.</h5>
                    <div class="flex-shrink-0">
                        <button class="btn btn-primary btn-sm me-1" data-bs-toggle="modal" data-bs-target="#addLoanModal">
                            <i class="mdi mdi-plus-circle-outline me-1"></i> Add New Loan
                        </button>
                        <button class="btn btn-success btn-sm">Need Help</button>
                    </div>
                </div>
            </div>
            
            <div class="card-body border border-dashed border-end-0 border-start-0">
                <form method="GET" action="{{ route('capture.loans') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-xxl-2 col-sm-3">
                            <div>
                                <label class="form-label text-muted d-none">Date From</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="dd-mm-yyyy">
                            </div>
                        </div>
                        <div class="col-auto">
                            <span class="text-muted">to</span>
                        </div>
                        <div class="col-xxl-2 col-sm-3">
                            <div>
                                <label class="form-label text-muted d-none">Date To</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="dd-mm-yyyy">
                            </div>
                        </div>
                        <div class="col-xxl-3 col-sm-4">
                            <div>
                                <label class="form-label text-muted d-none">Employee</label>
                                <select name="employee_id" class="form-control" data-choices data-choices-search-true>
                                    <option value="">All Employees</option>
                                    @foreach($employees as $emp)
                                        <option value="{{ $emp->id }}" {{ request('employee_id') == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-secondary"><i class="ri-eye-line align-bottom me-1"></i> View</button>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 50px;">SN</th>
                                <th scope="col">EMPLOYEE</th>
                                <th scope="col">DESIGNATION</th>
                                <th scope="col">DEPARTMENT</th>
                                <th scope="col">LOAN AMOUNT</th>
                                <th scope="col">ISSUE DATE</th>
                                <th scope="col">INTEREST METHOD</th>
                                <th scope="col" style="width: 100px;">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($loans as $index => $loan)
                                <tr>
                                    <td class="text-warning">{{ $index + 1 }}</td>
                                    <td>
                                        <div class="fw-medium text-dark">{{ $loan->employee->first_name }} {{ $loan->employee->last_name }}</div>
                                        <div class="text-muted fs-12">{{ $loan->employee->employee_code }}</div>
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $loan->employee->currentWorkProfile->designation->name ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $loan->employee->currentWorkProfile->department->name ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="text-dark">₹{{ number_format($loan->loan_amount, 2) }}</div>
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $loan->issue_date ? $loan->issue_date->format('d-M-Y') : 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="text-dark">{{ $loan->interest_rate ? $loan->interest_rate . '%' : '0%' }}</div>
                                    </td>
                                    <td>
                                        <form action="{{ route('capture.loans.destroy', $loan->id) }}" method="POST" class="d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-soft-danger" onclick="return confirm('Are you sure you want to delete this loan?');"><i class="ri-delete-bin-line"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted p-4">No loans found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Loan Modal -->
<div class="modal fade" id="addLoanModal" tabindex="-1" aria-labelledby="addLoanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('capture.loans.store') }}" method="POST">
            @csrf
            <div class="modal-content border-0">
                <div class="modal-header bg-soft-primary p-3">
                    <h5 class="modal-title" id="addLoanModalLabel">Issue New Loan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    
                    <div class="mb-3">
                        <label class="form-label">Employee <span class="text-danger">*</span></label>
                        <select name="employee_id" class="form-control" data-choices data-choices-search-true required>
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->first_name }} {{ $emp->last_name }} ({{ $emp->employee_code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Loan Amount <span class="text-danger">*</span></label>
                            <input type="number" name="loan_amount" class="form-control" required min="1">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" name="issue_date" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" class="form-control" step="0.01" value="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Tenure (Months)</label>
                            <input type="number" name="tenure_months" class="form-control" min="1" value="12">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Repayment Start Date</label>
                        <input type="date" name="repayment_start_date" class="form-control">
                    </div>

                </div>
                <div class="modal-footer border-top-0">
                    <button type="button" class="btn btn-light btn-sm text-primary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Issue Loan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@endsection
