<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ManualAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $businessUnits = \App\Models\BusinessUnit::all();
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();
        
        $monthYear = $request->input('month_year', date('Y-m'));
        $employeeId = $request->input('employee_id');
        
        $query = \App\Models\Employee::with(['user', 'designationInfo', 'workProfiles.designation', 'workProfiles.location', 'workProfiles.costCenter', 'workProfiles.department']);
        
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
        if ($request->filled('cost_center_id')) {
            $query->whereHas('workProfiles', function ($q) use ($request) {
                $q->where('cost_center_id', $request->cost_center_id);
            });
        }
        if ($request->filled('department_id')) {
            $query->whereHas('workProfiles', function ($q) use ($request) {
                $q->where('department_id', $request->department_id);
            });
        }
        if ($request->filled('employee_id')) {
            $query->where('user_id', $employeeId);
        }
        
        $employees = $query->paginate(20);
        
        // Fetch leave types for dynamic headers
        $leaveTypes = \App\Models\LeaveType::where('status', 'active')->get();
        
        // Fetch manual attendances for these employees
        $manualAttendances = \App\Models\ManualAttendance::where('month_year', $monthYear)
            ->whereIn('employee_id', $employees->pluck('user_id'))
            ->get()
            ->keyBy('employee_id');

        return view('admin.employee.attendance.manual', compact(
            'businessUnits',
            'locations',
            'costCenters',
            'departments',
            'employees',
            'monthYear',
            'employeeId',
            'leaveTypes',
            'manualAttendances'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'month_year' => 'required',
            'present_days' => 'nullable|numeric',
            'absent_days' => 'nullable|numeric',
            'holiday_days' => 'nullable|numeric',
            'week_off_days' => 'nullable|numeric',
            'leaves' => 'nullable|array',
        ]);
        
        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->firstOrFail();
        
        $data = [
            'business_id' => $employee->business_id,
            'present_days' => $request->present_days ?: 0,
            'absent_days' => $request->absent_days ?: 0,
            'holiday_days' => $request->holiday_days ?: 0,
            'week_off_days' => $request->week_off_days ?: 0,
            'leave_days' => $request->leaves ?: [],
        ];
        
        $attendance = \App\Models\ManualAttendance::firstOrNew([
            'employee_id' => $request->employee_id,
            'month_year' => $request->month_year,
        ]);
        
        $action = $attendance->exists ? 'updated' : 'created';
        
        // Determine changes for history
        $changes = [];
        if ($action == 'updated') {
            foreach ($data as $key => $value) {
                if ($attendance->$key != $value) {
                    $changes[$key] = [
                        'old' => $attendance->$key,
                        'new' => $value
                    ];
                }
            }
        } else {
            $changes = $data;
        }
        
        $attendance->fill($data);
        $attendance->save();
        
        // Only log history if there are changes
        if (!empty($changes)) {
            \App\Models\ManualAttendanceHistory::create([
                'manual_attendance_id' => $attendance->id,
                'changed_by' => \Illuminate\Support\Facades\Auth::id() ?? 1, // Fallback for testing
                'action' => $action,
                'changes' => $changes,
            ]);
        }
        
        return response()->json(['success' => true, 'message' => 'Attendance saved successfully']);
    }

    public function history($employeeId, Request $request)
    {
        $monthYear = $request->query('month_year');
        
        $attendance = \App\Models\ManualAttendance::where('employee_id', $employeeId)
            ->where('month_year', $monthYear)
            ->first();
            
        if (!$attendance) {
            return response()->json(['history' => []]);
        }
        
        $history = \App\Models\ManualAttendanceHistory::with('user')
            ->where('manual_attendance_id', $attendance->id)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json(['history' => $history]);
    }
}
