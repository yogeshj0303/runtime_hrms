<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeeActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::findOrFail($request->id);
        $logs = EmployeeActivityLog::where('employee_id', $employee->id)
            ->latest()
            ->paginate(20);

        return view('admin.employee.profile.activity-logs', compact('employee', 'logs'));
    }
}
