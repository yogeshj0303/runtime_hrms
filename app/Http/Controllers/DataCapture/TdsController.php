<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeTdsCapture;
use App\Models\IncomeTaxSetting;
use Illuminate\Support\Facades\DB;

class TdsController extends Controller
{
    public function index(Request $request)
    {
        $businessUnits = BusinessUnit::all();
        $locations = Location::all();
        $departments = Department::all();
        
        $employees = [];

        // Assume default business ID is 1 for now if single business setup
        $businessId = 1;
        $incomeTaxSetting = IncomeTaxSetting::where('business_id', $businessId)->first();
        $isGlobalTdsEnabled = $incomeTaxSetting ? $incomeTaxSetting->enable_tds_deduction : false;

        if ($request->has('payroll_month')) {
            $query = Employee::with('currentWorkProfile');

            if ($request->business_unit_id) {
                $query->whereHas('workProfiles', function($q) use ($request) {
                    $q->where('business_unit_id', $request->business_unit_id);
                });
            }

            if ($request->location_id) {
                $query->whereHas('workProfiles', function($q) use ($request) {
                    $q->where('location_id', $request->location_id);
                });
            }

            if ($request->department_id) {
                $query->whereHas('workProfiles', function($q) use ($request) {
                    $q->where('department_id', $request->department_id);
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

            // Load TDS amount for each employee for the month
            foreach ($employees as $employee) {
                $tdsCapture = EmployeeTdsCapture::where('employee_id', $employee->id)
                    ->where('payroll_month', $request->payroll_month)
                    ->first();
                
                $employee->current_tds_amount = $tdsCapture ? $tdsCapture->amount : 0;
            }
        }

        return view('admin.data_capture.tds', compact(
            'businessUnits', 'locations', 'departments', 'employees', 'isGlobalTdsEnabled'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_month' => 'required|string',
            'amount' => 'required|numeric',
        ]);

        try {
            $tds = EmployeeTdsCapture::updateOrCreate(
                [
                    'employee_id' => $request->employee_id,
                    'payroll_month' => $request->payroll_month,
                ],
                [
                    'amount' => $request->amount
                ]
            );

            return response()->json(['success' => true, 'message' => 'Saved successfully', 'data' => $tds]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }

    public function copyPrevious(Request $request)
    {
        $request->validate([
            'payroll_month' => 'required|string',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,id'
        ]);

        try {
            $currentMonth = $request->payroll_month;
            // Get the source month from the request (defaults to previous month if not provided)
            $previousMonth = $request->input('copy_from_month', date('Y-m', strtotime($currentMonth . '-01 -1 month')));
            
            DB::beginTransaction();

            $copiedCount = 0;

            foreach ($request->employee_ids as $empId) {
                $prevTds = EmployeeTdsCapture::where('employee_id', $empId)
                    ->where('payroll_month', $previousMonth)
                    ->first();

                if ($prevTds && $prevTds->amount > 0) {
                    EmployeeTdsCapture::updateOrCreate(
                        [
                            'employee_id' => $empId,
                            'payroll_month' => $currentMonth,
                        ],
                        [
                            'amount' => $prevTds->amount
                        ]
                    );
                    $copiedCount++;
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Copied previous period TDS for {$copiedCount} employees.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error copying TDS: ' . $e->getMessage());
        }
    }
}
