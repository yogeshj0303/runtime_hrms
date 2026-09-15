<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeOtHour;
use Illuminate\Support\Facades\DB;

class OtHourController extends Controller
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

            foreach ($employees as $employee) {
                $otHour = EmployeeOtHour::where('employee_id', $employee->id)
                    ->where('payroll_month', $request->payroll_month)
                    ->first();
                
                $employee->current_hours = $otHour ? $otHour->hours : 0;
                $employee->current_minutes = $otHour ? $otHour->minutes : 0;
            }
        }

        return view('admin.data_capture.ot_hours', compact(
            'businessUnits', 'locations', 'departments', 'employees'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_month' => 'required|string',
            'hours' => 'nullable|integer|min:0',
            'minutes' => 'nullable|integer|min:0|max:59'
        ]);

        try {
            $otHour = EmployeeOtHour::updateOrCreate(
                [
                    'employee_id' => $request->employee_id,
                    'payroll_month' => $request->payroll_month,
                ],
                [
                    'hours' => $request->hours ?? 0,
                    'minutes' => $request->minutes ?? 0
                ]
            );

            return response()->json(['success' => true, 'message' => 'Saved successfully', 'data' => $otHour]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }
}
