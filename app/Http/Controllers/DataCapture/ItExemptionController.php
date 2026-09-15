<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeItExemption;

class ItExemptionController extends Controller
{
    public function index(Request $request)
    {
        $businessUnits = BusinessUnit::all();
        $locations = Location::all();
        $departments = Department::all();

        $employees = [];

        if ($request->has('financial_year')) {
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
                $exemption = EmployeeItExemption::where('employee_id', $employee->id)
                    ->where('financial_year', $request->financial_year)
                    ->first();
                
                $employee->additional_exemptions = $exemption ? $exemption->additional_exemptions : 0;
            }
        }

        return view('admin.data_capture.it_exemptions', compact(
            'businessUnits', 'locations', 'departments', 'employees'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'financial_year' => 'required|string',
            'additional_exemptions' => 'required|numeric|min:0'
        ]);

        try {
            $exemption = EmployeeItExemption::updateOrCreate(
                [
                    'employee_id' => $request->employee_id,
                    'financial_year' => $request->financial_year,
                ],
                [
                    'additional_exemptions' => $request->additional_exemptions
                ]
            );

            return response()->json(['success' => true, 'message' => 'Saved successfully', 'data' => $exemption]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }
}
