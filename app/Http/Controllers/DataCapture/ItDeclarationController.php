<?php

namespace App\Http\Controllers\DataCapture;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusinessUnit;
use App\Models\Location;
use App\Models\Department;
use App\Models\Employee;
use App\Models\ItDeclarationItem;
use App\Models\EmployeeItDeclarationDetail;
use DB;

class ItDeclarationController extends Controller
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

            // Append totals
            foreach ($employees as $employee) {
                $totals = EmployeeItDeclarationDetail::where('employee_id', $employee->id)
                    ->where('financial_year', $request->financial_year)
                    ->select(DB::raw('SUM(declared_amount) as total_declared'), DB::raw('SUM(verified_amount) as total_verified'))
                    ->first();
                
                $employee->total_declared = $totals->total_declared ?? 0;
                $employee->total_verified = $totals->total_verified ?? 0;
            }
        }

        return view('admin.data_capture.it_declarations', compact(
            'businessUnits', 'locations', 'departments', 'employees'
        ));
    }

    public function getDetails(Request $request)
    {
        $employeeId = $request->employee_id;
        $financialYear = $request->financial_year;

        $items = ItDeclarationItem::where('is_active', true)->orderBy('section')->get();
        
        $details = EmployeeItDeclarationDetail::where('employee_id', $employeeId)
            ->where('financial_year', $financialYear)
            ->get()
            ->keyBy('it_declaration_item_id');

        $groupedItems = [];
        foreach ($items as $item) {
            $detail = $details->get($item->id);
            $groupedItems[$item->section][] = [
                'id' => $item->id,
                'name' => $item->name,
                'max_limit' => $item->max_limit,
                'declared_amount' => $detail ? $detail->declared_amount : 0,
                'verified_amount' => $detail ? $detail->verified_amount : 0,
                'remarks' => $detail ? $detail->remarks : '',
            ];
        }

        return response()->json(['success' => true, 'data' => $groupedItems]);
    }

    public function storeDetails(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'financial_year' => 'required|string',
            'items' => 'required|array'
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->items as $itemId => $data) {
                $declaredAmount = isset($data['declared']) ? floatval($data['declared']) : 0;
                $verifiedAmount = isset($data['verified']) ? floatval($data['verified']) : 0;
                $remarks = isset($data['remarks']) ? $data['remarks'] : null;

                if ($declaredAmount > 0 || $verifiedAmount > 0 || !empty($remarks)) {
                    EmployeeItDeclarationDetail::updateOrCreate(
                        [
                            'employee_id' => $request->employee_id,
                            'financial_year' => $request->financial_year,
                            'it_declaration_item_id' => $itemId,
                        ],
                        [
                            'declared_amount' => $declaredAmount,
                            'verified_amount' => $verifiedAmount,
                            'remarks' => $remarks
                        ]
                    );
                } else {
                    EmployeeItDeclarationDetail::where([
                        'employee_id' => $request->employee_id,
                        'financial_year' => $request->financial_year,
                        'it_declaration_item_id' => $itemId,
                    ])->delete();
                }
            }

            DB::commit();

            // Fetch new totals
            $totals = EmployeeItDeclarationDetail::where('employee_id', $request->employee_id)
                ->where('financial_year', $request->financial_year)
                ->select(DB::raw('SUM(declared_amount) as total_declared'), DB::raw('SUM(verified_amount) as total_verified'))
                ->first();

            return response()->json([
                'success' => true, 
                'message' => 'Details saved successfully',
                'total_declared' => $totals->total_declared ?? 0,
                'total_verified' => $totals->total_verified ?? 0
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Error saving data: ' . $e->getMessage()], 500);
        }
    }
}
