@extends('admin.employee.profile.layout')

@section('profile_title', 'Loans & Advances')
@section('profile_description', 'Manage loans and advances for this employee')

@section('page_css')
<style>
    .loan-card {
        border: 1px solid #eef1f5;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        background: #fbfcfe;
    }
    .loan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eef1f5;
    }
    .loan-amount {
        font-size: 18px;
        font-weight: 600;
        color: #133C5A;
    }
    .loan-status {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }
    .status-active { background: #d4edda; color: #155724; }
    .status-closed { background: #e2e3e5; color: #383d41; }
    
    .loan-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        font-size: 13px;
    }
    .detail-item label {
        display: block;
        color: #777;
        margin-bottom: 2px;
        font-size: 11px;
    }
    .detail-item div {
        color: #333;
        font-weight: 500;
    }
    
    .btn-add-loan {
        background: linear-gradient(135deg, #133C5A, #1d567f);
        color: #fff;
        border: none;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        transition: all 0.3s ease;
    }
    .btn-add-loan:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(19, 60, 90, 0.2);
        color: #fff;
    }
</style>
@endsection

@section('profile_actions')
<button type="button" class="btn-hrms-crimson" data-bs-toggle="modal" data-bs-target="#addLoanModal">
    <i class="ri-add-line"></i> Add New Loan
</button>
@endsection

@section('profile_content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="row">
    <div class="col-12">
        @if($loans->count() > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="font-size: 13px;">
                    <thead style="background: #fbfcfe;">
                        <tr>
                            <th>Issue Date</th>
                            <th>Amount</th>
                            <th>EMI</th>
                            <th>Tenure</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                            <tr>
                                <td>{{ $loan->issue_date->format('d M, Y') }}</td>
                                <td style="font-weight: 600; color: #133C5A;">₹{{ number_format($loan->loan_amount, 2) }}</td>
                                <td>₹{{ number_format($loan->emi_amount, 2) }}</td>
                                <td>{{ $loan->tenure_months }} Months</td>
                                <td>{{ $loan->repayment_start_date->format('M Y') }}</td>
                                <td>
                                    @if($loan->status == 'Active')
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $loan->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#schedule-{{ $loan->id }}">
                                        View Schedule
                                    </button>
                                </td>
                            </tr>
                            <tr class="collapse" id="schedule-{{ $loan->id }}">
                                <td colspan="7" style="background: #f8f9fa; padding: 15px;">
                                    <h6 style="font-size: 12px; margin-bottom: 10px;">Repayment Schedule</h6>
                                    <table class="table table-sm table-bordered bg-white" style="font-size: 11px;">
                                        <thead>
                                            <tr>
                                                <th>Installment Month</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($loan->installments as $inst)
                                                <tr>
                                                    <td>{{ $inst->installment_date->format('F Y') }}</td>
                                                    <td>₹{{ number_format($inst->amount, 2) }}</td>
                                                    <td>
                                                        @if($inst->status == 'Paid')
                                                            <span class="text-success"><i class="ri-checkbox-circle-fill"></i> Paid</span>
                                                        @else
                                                            <span class="text-warning"><i class="ri-time-fill"></i> Pending</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center p-5 text-muted">
                <i class="ri-money-dollar-circle-line" style="font-size: 48px; color: #d7dce3;"></i>
                <p class="mt-3 mb-0">No loans found for this employee.</p>
            </div>
        @endif
    </div>
</div>

<!-- Add Loan Modal -->
<div class="modal fade" id="addLoanModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Loan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employee.profile.loans.store', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Loan Amount (₹) <span class="text-danger">*</span></label>
                        <input type="number" name="loan_amount" id="loan_amount" class="form-control" required min="1" step="0.01">
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Issue Date <span class="text-danger">*</span></label>
                            <input type="date" name="issue_date" class="form-control" required value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Interest Rate (%)</label>
                            <input type="number" name="interest_rate" class="form-control" value="0" min="0" step="0.1">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Tenure (Months) <span class="text-danger">*</span></label>
                            <input type="number" name="tenure_months" id="tenure_months" class="form-control" required min="1" value="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Repayment Start Date <span class="text-danger">*</span></label>
                            <input type="date" name="repayment_start_date" class="form-control" required>
                            <small class="text-muted" style="font-size: 10px;">When will the first EMI be deducted?</small>
                        </div>
                    </div>

                    <div class="mb-3 p-3 bg-light rounded" style="border: 1px solid #eef1f5;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="font-size: 13px; font-weight: 500;">Calculated Monthly EMI:</span>
                            <span id="calculated_emi" style="font-size: 18px; font-weight: 700; color: #133C5A;">₹0.00</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Remarks (Optional)</label>
                        <textarea name="remarks" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-add-loan">Save & Generate Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('page_script')
<script>
    $(document).ready(function() {
        function calculateEMI() {
            let amount = parseFloat($('#loan_amount').val()) || 0;
            let tenure = parseInt($('#tenure_months').val()) || 1;
            
            if (amount > 0 && tenure > 0) {
                let emi = amount / tenure;
                $('#calculated_emi').text('₹' + emi.toFixed(2));
            } else {
                $('#calculated_emi').text('₹0.00');
            }
        }

        $('#loan_amount, #tenure_months').on('input', calculateEMI);
    });
</script>
@endsection
