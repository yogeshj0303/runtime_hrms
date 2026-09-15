<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeLoan;
use App\Models\EmployeeLoanInstallment;
use Carbon\Carbon;

class EmployeeLoanController extends Controller
{
    public function index($id)
    {
        $employee = Employee::findOrFail($id);
        $loans = EmployeeLoan::where('employee_id', $id)
            ->with('installments')
            ->orderBy('issue_date', 'desc')
            ->get();
            
        return view('admin.employee.profile.loans', compact('employee', 'loans'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'loan_amount' => 'required|numeric|min:1',
            'interest_rate' => 'nullable|numeric|min:0',
            'issue_date' => 'required|date',
            'repayment_start_date' => 'required|date',
            'tenure_months' => 'required|integer|min:1'
        ]);

        $employee = Employee::findOrFail($id);

        $loanAmount = $request->loan_amount;
        $tenure = $request->tenure_months;
        $emiAmount = $loanAmount / $tenure; // Simple division without interest logic for now, as interest is usually complex in HR

        $loan = EmployeeLoan::create([
            'employee_id' => $employee->id,
            'loan_amount' => $loanAmount,
            'interest_rate' => $request->interest_rate ?? 0,
            'issue_date' => $request->issue_date,
            'repayment_start_date' => $request->repayment_start_date,
            'tenure_months' => $tenure,
            'emi_amount' => $emiAmount,
            'status' => 'Active',
            'remarks' => $request->remarks
        ]);

        $startDate = Carbon::parse($request->repayment_start_date);

        for ($i = 0; $i < $tenure; $i++) {
            EmployeeLoanInstallment::create([
                'employee_loan_id' => $loan->id,
                'installment_date' => $startDate->copy()->addMonths($i),
                'amount' => $emiAmount,
                'status' => 'Pending'
            ]);
        }

        return redirect()->back()->with('success', 'Loan created successfully! Schedule generated.');
    }
}
