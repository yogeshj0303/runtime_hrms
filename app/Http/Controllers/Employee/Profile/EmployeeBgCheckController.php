<?php

namespace App\Http\Controllers\Employee\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeBgCheck;

class EmployeeBgCheckController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->id ?? $request->employee_id;
        $employee = Employee::findOrFail($id);
        $bgChecks = EmployeeBgCheck::where('employee_id', $id)->latest()->get();
        return view('admin.employee.profile.bg-check', compact('employee', 'bgChecks'));
    }

    public function store(Request $request)
    {
        $id = $request->employee_id ?? $request->id ?? $request->query('id');
        
        if (!$id) {
            return redirect()->back()->with('error', 'Employee ID is required.');
        }

        $request->validate([
            'check_type' => 'required|string',
            'agency_name' => 'nullable|string',
            'status' => 'required|in:Pending,Cleared,Failed',
            'remarks' => 'nullable|string',
        ]);

        EmployeeBgCheck::create([
            'employee_id' => $id,
            'check_type' => $request->check_type,
            'agency_name' => $request->agency_name,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('success', 'Background check added successfully.');
    }

    public function destroy($id)
    {
        $check = EmployeeBgCheck::findOrFail($id);
        $check->delete();

        return redirect()->back()->with('success', 'Background check deleted successfully.');
    }
}
