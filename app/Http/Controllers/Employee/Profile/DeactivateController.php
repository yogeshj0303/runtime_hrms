<?php

namespace App\Http\Controllers\Employee\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class DeactivateController extends Controller
{
    public function index($id)
    {
        $employee = Employee::with(['assets', 'workProfiles'])->findOrFail($id);
        
        // Dependencies check
        $assetsHold = $employee->assets()->whereNull('returned_date')->count();
        $reportingEmployees = \App\Models\EmployeeWorkProfile::where('reporting_manager_id', $id)->count();
        $pendingApprovals = 0; // Mocked pending approvals

        return view('admin.employee.profile.deactivate', compact('employee', 'assetsHold', 'reportingEmployees', 'pendingApprovals'));
    }

    public function deactivate(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $employee->status = 'inactive';
        $employee->exit_date = $request->input('exit_date', now());
        $employee->save();

        return redirect()->route('business.employee')->with('success', 'Employee has been deactivated successfully.');
    }
}
