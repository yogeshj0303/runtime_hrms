<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeLoan;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::all();
        
        $query = EmployeeLoan::with(['employee.currentWorkProfile.designation', 'employee.currentWorkProfile.department']);

        if ($request->filled('date_from')) {
            $query->whereDate('issue_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('issue_date', '<=', $request->date_to);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $loans = $query->orderBy('issue_date', 'desc')->get();

        return view('admin.data_capture.loans', compact('employees', 'loans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'loan_amount' => 'required|numeric|min:1',
            'issue_date' => 'required|date',
            'interest_rate' => 'nullable|numeric',
            'repayment_start_date' => 'nullable|date',
            'tenure_months' => 'nullable|integer|min:1',
        ]);

        try {
            EmployeeLoan::create([
                'employee_id' => $request->employee_id,
                'loan_amount' => $request->loan_amount,
                'issue_date' => $request->issue_date,
                'interest_rate' => $request->interest_rate ?? 0,
                'repayment_start_date' => $request->repayment_start_date,
                'tenure_months' => $request->tenure_months ?? 1,
                'emi_amount' => $request->tenure_months > 0 ? ($request->loan_amount / $request->tenure_months) : $request->loan_amount,
                'status' => 'Active',
                'remarks' => $request->remarks
            ]);

            return redirect()->back()->with('success', 'Loan created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error creating loan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $loan = EmployeeLoan::findOrFail($id);
            $loan->delete();
            return redirect()->back()->with('success', 'Loan deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting loan: ' . $e->getMessage());
        }
    }
}
