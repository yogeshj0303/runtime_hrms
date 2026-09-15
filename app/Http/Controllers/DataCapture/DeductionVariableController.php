<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\SalaryDeduction;
use App\Models\EmployeeDeductionVariable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeductionVariableController extends Controller
{
    public function index(Request $request)
    {
        $businessUnits = BusinessUnit::all();
        $locations = Location::all();
        $departments = Department::all();
        
        $deductionComponents = SalaryDeduction::where('active', true)->get();

        $employees = [];

        if ($request->has('salary_deduction_id') && $request->has('payroll_month')) {
            $query = Employee::query();
            
            if ($request->business_unit_id || $request->location_id || $request->department_id) {
                $query->whereHas('workProfiles', function ($q) use ($request) {
                    $q->where('is_current', true);
                    if ($request->business_unit_id) {
                        $q->where('business_unit_id', $request->business_unit_id);
                    }
                    if ($request->location_id) {
                        $q->where('location_id', $request->location_id);
                    }
                    if ($request->department_id) {
                        $q->where('department_id', $request->department_id);
                    }
                });
            }
            if ($request->employee_search) {
                $query->where(function($q) use ($request) {
                    $q->where('first_name', 'like', '%' . $request->employee_search . '%')
                      ->orWhere('last_name', 'like', '%' . $request->employee_search . '%')
                      ->orWhere('employee_code', 'like', '%' . $request->employee_search . '%');
                });
            }

            $employees = $query->get();

            // Load variables for each employee
            foreach ($employees as $employee) {
                $variables = EmployeeDeductionVariable::where('employee_id', $employee->id)
                    ->where('salary_deduction_id', $request->salary_deduction_id)
                    ->where('payroll_month', $request->payroll_month)
                    ->get();
                
                $employee->current_variable_amount = $variables->sum('total_amount');
                $employee->current_variable_comments = '';
            }
        }

        return view('admin.data_capture.deduction_variable', compact(
            'businessUnits', 'locations', 'departments', 'deductionComponents', 
            'employees', 'deductionComponents'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_deduction_id' => 'required|exists:salary_deductions,id',
            'payroll_month' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        $employee = Employee::find($request->employee_id);

        DB::beginTransaction();
        try {
            // Find or create the parent record
            $variable = EmployeeDeductionVariable::firstOrCreate([
                'employee_id' => $request->employee_id,
                'salary_deduction_id' => $request->salary_deduction_id,
                'payroll_month' => $request->payroll_month
            ], [
                'business_id' => $employee->business_id ?? 1,
                'amount' => 0
            ]);

            // Create the detail record
            $variable->details()->create([
                'amount' => $request->amount,
                'comments' => $request->comments
            ]);

            // Recalculate and update the parent's total amount
            $totalAmount = $variable->details()->sum('amount');
            $variable->update(['total_amount' => $totalAmount]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Saved successfully', 'data' => $variable, 'total' => $totalAmount]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }

    public function transferNonCash(Request $request)
    {
        $request->validate([
            'source_component_id' => 'required|exists:salary_deductions,id',
            'target_component_id' => 'required|exists:salary_deductions,id',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $query = EmployeeDeductionVariable::where('salary_deduction_id', $request->source_component_id)
                ->whereBetween('created_at', [$request->date_from . ' 00:00:00', $request->date_to . ' 23:59:59']);

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
            }

            // Get sums grouped by employee
            $sums = $query->select('employee_id', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('employee_id')
                ->get();

            $targetPayrollMonth = $request->input('target_payroll_month', date('Y-m'));
            
            $transferredCount = 0;
            $totalAmountTransferred = 0;

            foreach ($sums as $sum) {
                if ($sum->total_amount > 0) {
                    $employee = Employee::find($sum->employee_id);
                    
                    // The view says "Import will overwrite existing data for selected employee and target component."
                    EmployeeDeductionVariable::where('employee_id', $sum->employee_id)
                        ->where('salary_deduction_id', $request->target_component_id)
                        ->where('payroll_month', $targetPayrollMonth)
                        ->delete();

                    EmployeeDeductionVariable::create([
                        'business_id' => $employee->business_id ?? 1,
                        'employee_id' => $sum->employee_id,
                        'salary_deduction_id' => $request->target_component_id,
                        'payroll_month' => $targetPayrollMonth,
                        'total_amount' => $sum->total_amount,
                        'comments' => 'Transferred from Non-Cash component'
                    ]);
                    
                    $transferredCount++;
                    $totalAmountTransferred += $sum->total_amount;
                }
            }

            DB::commit();
            return redirect()->back()->with('success', "Non-Cash Salary transferred successfully for {$transferredCount} employees. Total Amount: {$totalAmountTransferred}");

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error transferring non-cash salary: ' . $e->getMessage());
        }
    }

    public function getDetails(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_deduction_id' => 'required|exists:salary_deductions,id',
            'payroll_month' => 'required|string',
        ]);

        $parent = EmployeeDeductionVariable::where('employee_id', $request->employee_id)
            ->where('salary_deduction_id', $request->salary_deduction_id)
            ->where('payroll_month', $request->payroll_month)
            ->first();

        if (!$parent) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $details = $parent->details()->orderBy('created_at', 'desc')->get();

        return response()->json(['success' => true, 'data' => $details]);
    }

    public function deleteDetail($id)
    {
        $detail = \App\Models\EmployeeDeductionVariableDetail::findOrFail($id);
        $parent = $detail->EmployeeDeductionVariable;
        
        $detail->delete();

        // Recalculate total amount
        $totalAmount = $parent->details()->sum('amount');
        $parent->update(['amount' => $totalAmount]);

        return response()->json(['success' => true, 'message' => 'Deleted successfully', 'total' => $totalAmount]);
    }

    public function exportTemplate()
    {
        // Placeholder for Maatwebsite Excel export
        return redirect()->back()->with('info', 'Export Template feature will be implemented soon.');
    }

    public function uploadExcel(Request $request)
    {
        // Placeholder for Maatwebsite Excel import
        return redirect()->back()->with('info', 'Upload Excel feature will be implemented soon.');
    }

    public function downloadData(Request $request)
    {
        // Placeholder for Maatwebsite Excel export
        return redirect()->back()->with('info', 'Download Data feature will be implemented soon.');
    }
}
