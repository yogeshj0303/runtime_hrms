<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeExtraDay;
use Illuminate\Support\Facades\DB;

class ExtraDayController extends Controller
{
    public function index(Request $request)
    {
        $businessUnits = BusinessUnit::all();
        $locations = Location::all();
        $departments = Department::all();

        $employees = [];

        if ($request->has('payroll_month')) {
            $query = Employee::query()->with(['currentWorkProfile.designation', 'currentWorkProfile.department', 'currentWorkProfile.location']);

            if ($request->filled('business_unit_id')) {
                $query->whereHas('workProfiles', function ($q) use ($request) {
                    $q->where('business_unit_id', $request->business_unit_id);
                });
            }

            if ($request->filled('location_id')) {
                $query->whereHas('workProfiles', function ($q) use ($request) {
                    $q->where('location_id', $request->location_id);
                });
            }

            if ($request->filled('department_id')) {
                $query->whereHas('workProfiles', function ($q) use ($request) {
                    $q->where('department_id', $request->department_id);
                });
            }

            if ($request->filled('employee_search')) {
                $searchTerm = $request->employee_search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('first_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('last_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('employee_code', 'like', '%' . $searchTerm . '%');
                });
            }

            $employees = $query->get();

            // Load extra days for each employee
            foreach ($employees as $employee) {
                $extraDay = EmployeeExtraDay::where('employee_id', $employee->id)
                    ->where('payroll_month', $request->payroll_month)
                    ->first();
                
                $employee->current_extra_days = $extraDay ? $extraDay->extra_days : 0;
                $employee->current_arrear_days = $extraDay ? $extraDay->arrear_days : 0;
                $employee->current_ot_days = $extraDay ? $extraDay->ot_days : 0;
                $employee->current_comments = $extraDay ? $extraDay->comments : '';
            }
        }

        return view('admin.data_capture.extra_days', compact(
            'businessUnits', 'locations', 'departments', 'employees'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_month' => 'required|string',
            'extra_days' => 'nullable|numeric',
            'arrear_days' => 'nullable|numeric',
            'ot_days' => 'nullable|numeric',
            'comments' => 'nullable|string'
        ]);

        try {
            $extraDay = EmployeeExtraDay::updateOrCreate(
                [
                    'employee_id' => $request->employee_id,
                    'payroll_month' => $request->payroll_month,
                ],
                [
                    'extra_days' => $request->extra_days ?? 0,
                    'arrear_days' => $request->arrear_days ?? 0,
                    'ot_days' => $request->ot_days ?? 0,
                    'comments' => $request->comments
                ]
            );

            return response()->json(['success' => true, 'message' => 'Saved successfully', 'data' => $extraDay]);
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
            $previousMonth = $request->input('copy_from_month', date('Y-m', strtotime($currentMonth . '-01 -1 month')));
            
            DB::beginTransaction();

            $copiedCount = 0;

            foreach ($request->employee_ids as $empId) {
                $prevRecord = EmployeeExtraDay::where('employee_id', $empId)
                    ->where('payroll_month', $previousMonth)
                    ->first();

                if ($prevRecord && ($prevRecord->extra_days > 0 || $prevRecord->arrear_days > 0 || $prevRecord->ot_days > 0 || !empty($prevRecord->comments))) {
                    EmployeeExtraDay::updateOrCreate(
                        [
                            'employee_id' => $empId,
                            'payroll_month' => $currentMonth,
                        ],
                        [
                            'extra_days' => $prevRecord->extra_days,
                            'arrear_days' => $prevRecord->arrear_days,
                            'ot_days' => $prevRecord->ot_days,
                            'comments' => $prevRecord->comments
                        ]
                    );
                    $copiedCount++;
                }
            }

            DB::commit();

            return redirect()->back()->with('success', "Copied previous period extra days for {$copiedCount} employees.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error copying extra days: ' . $e->getMessage());
        }
    }
}
