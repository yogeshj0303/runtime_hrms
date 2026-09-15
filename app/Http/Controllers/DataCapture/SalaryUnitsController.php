<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\SalaryComponent;
use App\Models\EmployeeSalaryUnit;
use App\Models\EmployeeSalaryUnitDetail;
use Illuminate\Support\Facades\DB;

class SalaryUnitsController extends Controller
{
    public function index(Request $request)
    {
        $businessUnits = BusinessUnit::all();
        $locations = Location::all();
        $departments = Department::all();
        
        $variableComponents = SalaryComponent::where('active', true)->get();

        $employees = [];

        if ($request->has('salary_component_id') && $request->has('payroll_month')) {
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
                $units = EmployeeSalaryUnit::where('employee_id', $employee->id)
                    ->where('salary_component_id', $request->salary_component_id)
                    ->where('payroll_month', $request->payroll_month)
                    ->where('is_arrear', $request->has('arrear') ? true : false)
                    ->get();
                
                $employee->current_total_units = $units->sum('total_units');
                $employee->current_units_comments = '';
            }
        }

        return view('admin.data_capture.salary_units', compact(
            'businessUnits', 'locations', 'departments', 'variableComponents', 
            'employees'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_component_id' => 'required|exists:salary_components,id',
            'payroll_month' => 'required|string',
            'quantity' => 'required|numeric',
        ]);

        $employee = Employee::find($request->employee_id);

        DB::beginTransaction();
        try {
            // Find or create the parent record
            $unit = EmployeeSalaryUnit::firstOrCreate([
                'employee_id' => $request->employee_id,
                'salary_component_id' => $request->salary_component_id,
                'payroll_month' => $request->payroll_month,
                'is_arrear' => $request->boolean('is_arrear', false)
            ], [
                'total_units' => 0
            ]);

            // Create the detail record
            $unit->details()->create([
                'quantity' => $request->quantity,
                'comment' => $request->comments,
                'capture_date' => now()->format('Y-m-d')
            ]);

            // Recalculate and update the parent's total units
            $totalUnits = $unit->details()->sum('quantity');
            $unit->update(['total_units' => $totalUnits]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Saved successfully', 'data' => $unit, 'total' => $totalUnits]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }

    public function getDetails(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'salary_component_id' => 'required|exists:salary_components,id',
            'payroll_month' => 'required|string',
        ]);

        $parent = EmployeeSalaryUnit::where('employee_id', $request->employee_id)
            ->where('salary_component_id', $request->salary_component_id)
            ->where('payroll_month', $request->payroll_month)
            ->where('is_arrear', $request->boolean('is_arrear', false))
            ->first();

        if (!$parent) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $details = $parent->details()->orderBy('created_at', 'desc')->get();

        return response()->json(['success' => true, 'data' => $details]);
    }

    public function deleteDetail($id)
    {
        $detail = EmployeeSalaryUnitDetail::findOrFail($id);
        $parent = $detail->employeeSalaryUnit;
        
        $detail->delete();

        // Recalculate total units
        $totalUnits = $parent->details()->sum('quantity');
        $parent->update(['total_units' => $totalUnits]);

        return response()->json(['success' => true, 'message' => 'Deleted successfully', 'total' => $totalUnits]);
    }

    public function exportExcel()
    {
        // Placeholder for Maatwebsite Excel export
        return redirect()->back()->with('info', 'Export Data feature will be implemented soon.');
    }

    public function uploadExcel(Request $request)
    {
        // Placeholder for Maatwebsite Excel import
        return redirect()->back()->with('info', 'Upload Excel feature will be implemented soon.');
    }

    public function importTravel(Request $request)
    {
        // Placeholder for importing from travel module
        return redirect()->back()->with('info', 'Import from Travel feature will be implemented soon.');
    }
}
