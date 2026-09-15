<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Business;
use App\Models\UserBusinessSession;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    // Dashboard Page
    public function index(Request $request)
    {
        $businessId = Auth::user()->active_business_id;
        if (!$businessId) {
            $business = \App\Models\Business::where('user_id', Auth::id())->first();
            if ($business) {
                $businessId = $business->id;
                Auth::user()->update(['active_business_id' => $businessId]);
            }
        }
        $query = \App\Models\Employee::with(['workProfiles' => function($q) {
            $q->where('is_current', true)->with('designation', 'department', 'location', 'costCenter');
        }]);
        if ($businessId) {
            $query->where('business_id', $businessId);
        }

        // Check if there is an active/inactive filter
        $status = $request->input('status', 'active');
        if ($status === 'active') {
            $query->where('status', '!=', 'inactive')->orWhereNull('status');
        } elseif ($status === 'inactive') {
            $query->where('status', 'inactive');
        }

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        // Dropdown Filters
        $hasWorkProfileFilters = $request->filled('business_unit_id') || $request->filled('location_id') || $request->filled('cost_center_id') || $request->filled('department_id') || $request->filled('designation_id');
        
        if ($hasWorkProfileFilters) {
            $query->whereHas('workProfiles', function($q) use ($request) {
                $q->where('is_current', true);
                if ($request->filled('business_unit_id')) {
                    $q->where('business_unit_id', $request->business_unit_id);
                }
                if ($request->filled('location_id')) {
                    $q->where('location_id', $request->location_id);
                }
                if ($request->filled('cost_center_id')) {
                    $q->where('cost_center_id', $request->cost_center_id);
                }
                if ($request->filled('department_id')) {
                    $q->where('department_id', $request->department_id);
                }
                if ($request->filled('designation_id')) {
                    $q->where('designation_id', $request->designation_id);
                }
            });
        }

        $employees = $query->get();

        // Dropdowns for UI
        $departments = \App\Models\Department::where('business_id', $businessId)->get();
        $designations = \App\Models\Designation::where('business_id', $businessId)->get();
        $locations = \App\Models\Location::where('business_id', $businessId)->get();
        $costCenters = \App\Models\CostCenter::where('business_id', $businessId)->get();
        $businessUnits = \App\Models\BusinessUnit::where('business_id', $businessId)->get();

        return view('admin.employee.index', compact('employees', 'departments', 'designations', 'locations', 'costCenters', 'businessUnits'));
    }

    // Add Main Page
  
    public function toggleStatus($id)
    {
        $employee = \App\Models\Employee::findOrFail($id);
        if ($employee->status === 'inactive') {
            $employee->status = 'active';
            $employee->exit_date = null;
        } else {
            $employee->status = 'inactive';
            $employee->exit_date = now();
        }
        $employee->save();
        
        return redirect()->back()->with('success', 'Employee status updated successfully.');
    }

    public function downloadStatement($id)
    {
        // This is a placeholder for downloading an employee statement pdf.
        // It should normally return a PDF stream or download.
        $employee = \App\Models\Employee::findOrFail($id);
        
        return response("Employee statement for {$employee->first_name} {$employee->last_name} (Code: {$employee->employee_code})", 200, [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="Statement_'.$employee->employee_code.'.txt"',
        ]);
    }


}