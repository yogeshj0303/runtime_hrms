<?php

namespace App\Http\Controllers;

use App\Models\AttendanceDaily;
use App\Models\AttendanceDailyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceDailyController extends Controller
{
    /**
     * Display the Daily Punches view with filters.
     */
    public function dailyPunches(Request $request)
    {
        $data = $this->getFilteredAttendanceData($request);

        return view('admin.employee.attendance.daily_punches', $data);
    }

    /**
     * Display the Daily Attendance view with timeline.
     */
    public function dailyAttendance(Request $request)
    {
        $data = $this->getFilteredAttendanceData($request);

        return view('admin.employee.attendance.daily', $data);
    }

    /**
     * Display the Monthly Attendance view with calendar.
     */
    public function monthlyAttendance(Request $request)
    {
        $businessUnits = \App\Models\BusinessUnit::all();
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        $monthYear = $request->input('month_year', date('Y-m')); // e.g. 2026-05
        $employeeId = $request->input('employee_id');

        $query = \App\Models\Employee::with(['user', 'designationInfo', 'workProfiles.designation', 'workProfiles.location', 'workProfiles.costCenter', 'workProfiles.department', 'policy.weekOffPolicy']);

        // Apply Filters
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

        $employees = $query->get();

        $selectedEmployee = null;
        $attendances = collect();

        if ($employeeId) {
            $selectedEmployee = $employees->firstWhere('user_id', $employeeId);
            
            if ($selectedEmployee) {
                $year = substr($monthYear, 0, 4);
                $month = substr($monthYear, 5, 2);
                
                $attendances = AttendanceDaily::with('details')
                    ->where('employee_id', $employeeId)
                    ->whereYear('attendance_date', $year)
                    ->whereMonth('attendance_date', $month)
                    ->get()
                    ->keyBy(function($item) {
                        return \Carbon\Carbon::parse($item->attendance_date)->format('Y-m-d');
                    });
            }
        }
        
        $shiftPolicies = \App\Models\ShiftPolicy::all()->keyBy('id');
        $shifts = \App\Models\Shift::all()->keyBy('id');
        $leaveTypes = \App\Models\LeaveType::where('status', 'active')->get();
        
        list($year, $month) = explode('-', $monthYear);
        $holidays = \App\Models\Holiday::whereMonth('date', $month)
            ->whereYear('date', $year)
            ->get()
            ->pluck('holiday_name', 'date');
            
        $defaultWeekOffPolicy = \App\Models\WeekOffPolicy::where('is_default', true)->first();

        return view('admin.employee.attendance.monthly', compact(
            'businessUnits',
            'locations',
            'costCenters',
            'departments',
            'employees',
            'monthYear',
            'employeeId',
            'selectedEmployee',
            'attendances',
            'shiftPolicies',
            'shifts',
            'leaveTypes',
            'holidays',
            'defaultWeekOffPolicy'
        ));
    }

    private function getFilteredAttendanceData(Request $request)
    {
        $businessUnits = \App\Models\BusinessUnit::all();
        $locations = \App\Models\Location::all();
        $costCenters = \App\Models\CostCenter::all();
        $departments = \App\Models\Department::all();

        $date = $request->input('date', date('Y-m-d'));
        $statusFilter = $request->input('status_filter', 'all'); // all, late, absent, no_punches

        $query = \App\Models\Employee::with(['user', 'designationInfo', 'workProfiles.designation', 'workProfiles.location', 'workProfiles.costCenter', 'workProfiles.department', 'policy']);

        // Apply Filters
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
        if ($request->filled('employee_search')) {
            $search = $request->employee_search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('employee_code', 'like', "%{$search}%");
            });
        }

        $employees = $query->get();

        // Now, let's fetch attendance for these employees on the given date
        $userIds = $employees->pluck('user_id')->filter()->toArray();

        $attendances = AttendanceDaily::with('details')
            ->whereIn('employee_id', $userIds)
            ->whereDate('attendance_date', $date)
            ->get()
            ->keyBy('employee_id');

        // Filter by radio status
        $filteredEmployees = collect();

        foreach ($employees as $employee) {
            $userId = $employee->user_id;
            $attendance = $attendances->get($userId);
            
            $include = false;
            
            if ($statusFilter === 'all') {
                $include = true;
            } elseif ($statusFilter === 'late') {
                if ($attendance && $attendance->is_late) {
                    $include = true;
                }
            } elseif ($statusFilter === 'absent') {
                if ($attendance && strtolower($attendance->status) === 'absent') {
                    $include = true;
                }
            } elseif ($statusFilter === 'no_punches') {
                // No punch implies either no attendance record or details are empty
                if (!$attendance || $attendance->details->isEmpty()) {
                    $include = true;
                }
            }

            if ($include) {
                $filteredEmployees->push((object)[
                    'employee' => $employee,
                    'attendance' => $attendance
                ]);
            }
        }

        // Paginate manually since we filtered a collection
        $perPage = 20;
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $paginatedItems = new \Illuminate\Pagination\LengthAwarePaginator(
            $filteredEmployees->forPage($page, $perPage),
            $filteredEmployees->count(),
            $perPage,
            $page,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        $shifts = \App\Models\Shift::all()->keyBy('id');
        $shiftPolicies = \App\Models\ShiftPolicy::all()->keyBy('id');

        return compact(
            'paginatedItems',
            'businessUnits',
            'locations',
            'costCenters',
            'departments',
            'date',
            'statusFilter',
            'shifts',
            'shiftPolicies'
        );
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = AttendanceDaily::with([
            'employee',
            'business',
            'details'
        ])
        ->latest()
        ->paginate(20);

        return view(
            'admin.employee.attendance.index',
            compact('attendances')
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.employee.attendance.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'business_id' => 'required',
            'employee_id' => 'required',
            'attendance_date' => 'required|date',
            'day' => 'required',
            'status' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $attendance = AttendanceDaily::create([
                'business_id' => $request->business_id,
                'employee_id' => $request->employee_id,
                'attendance_date' => $request->attendance_date,
                'day' => $request->day,
                'status' => $request->status,
                'working_time_for_day' => $request->working_time_for_day ?? 0,
                'total_working_time' => $request->total_working_time ?? 0,
                'is_late' => $request->is_late ?? false,
                'late_minutes' => $request->late_minutes ?? 0,
                'remark' => $request->remark
            ]);

            $attendance->details()->create([
                'business_id' => $request->business_id,
                'employee_id' => $request->employee_id,

                'punch_in_time' => $request->punch_in_time,
                'punch_out_time' => $request->punch_out_time,

                'punch_in_location' => $request->punch_in_location,
                'punch_out_location' => $request->punch_out_location,

                'punch_in_latitude' => $request->punch_in_latitude,
                'punch_in_longitude' => $request->punch_in_longitude,

                'punch_out_latitude' => $request->punch_out_latitude,
                'punch_out_longitude' => $request->punch_out_longitude,

                'device_name' => $request->device_name,
                'ip_address' => request()->ip(),

                'total_working_time' => $request->total_working_time ?? 0,

                'status_daily' => $request->status_daily,

                'remark' => $request->remark
            ]);

            DB::commit();

            return redirect()
                ->route('attendance.index')
                ->with('success', 'Attendance added successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $attendance = AttendanceDaily::with([
            'employee',
            'business',
            'details'
        ])->findOrFail($id);

        return view(
            'admin.employee.attendance.show',
            compact('attendance')
        );
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $attendance = AttendanceDaily::with('details')
            ->findOrFail($id);

        return view(
            'admin.employee.attendance.edit',
            compact('attendance')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $attendance = AttendanceDaily::findOrFail($id);

        $request->validate([
            'business_id' => 'required',
            'employee_id' => 'required',
            'attendance_date' => 'required|date',
            'day' => 'required',
            'status' => 'required'
        ]);

        DB::beginTransaction();

        try {

            $attendance->update([
                'business_id' => $request->business_id,
                'employee_id' => $request->employee_id,
                'attendance_date' => $request->attendance_date,
                'day' => $request->day,
                'status' => $request->status,
                'working_time_for_day' => $request->working_time_for_day ?? 0,
                'total_working_time' => $request->total_working_time ?? 0,
                'is_late' => $request->is_late ?? false,
                'late_minutes' => $request->late_minutes ?? 0,
                'remark' => $request->remark
            ]);

            if ($attendance->details()->exists()) {

                $attendance->details()->update([
                    'business_id' => $request->business_id,
                    'employee_id' => $request->employee_id,

                    'punch_in_time' => $request->punch_in_time,
                    'punch_out_time' => $request->punch_out_time,

                    'punch_in_location' => $request->punch_in_location,
                    'punch_out_location' => $request->punch_out_location,

                    'punch_in_latitude' => $request->punch_in_latitude,
                    'punch_in_longitude' => $request->punch_in_longitude,

                    'punch_out_latitude' => $request->punch_out_latitude,
                    'punch_out_longitude' => $request->punch_out_longitude,

                    'device_name' => $request->device_name,
                    'ip_address' => request()->ip(),

                    'total_working_time' => $request->total_working_time ?? 0,

                    'status_daily' => $request->status_daily,

                    'remark' => $request->remark
                ]);

            } else {

                $attendance->details()->create([
                    'business_id' => $request->business_id,
                    'employee_id' => $request->employee_id,

                    'punch_in_time' => $request->punch_in_time,
                    'punch_out_time' => $request->punch_out_time,

                    'punch_in_location' => $request->punch_in_location,
                    'punch_out_location' => $request->punch_out_location,

                    'punch_in_latitude' => $request->punch_in_latitude,
                    'punch_in_longitude' => $request->punch_in_longitude,

                    'punch_out_latitude' => $request->punch_out_latitude,
                    'punch_out_longitude' => $request->punch_out_longitude,

                    'device_name' => $request->device_name,
                    'ip_address' => request()->ip(),

                    'total_working_time' => $request->total_working_time ?? 0,

                    'status_daily' => $request->status_daily,

                    'remark' => $request->remark
                ]);
            }

            DB::commit();

            return redirect()
                ->route('attendance.index')
                ->with('success', 'Attendance updated successfully.');

        } catch (\Exception $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $attendance = AttendanceDaily::findOrFail($id);

        $attendance->delete();

        return redirect()
            ->route('attendance.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    public function getPunchDetails(Request $request)
    {
        $employeeId = $request->employee_id;
        $date = $request->date;

        $attendance = AttendanceDaily::where('employee_id', $employeeId)
            ->where('date', $date)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => true, 'data' => []]);
        }

        $details = $attendance->details()->orderBy('punch_in_time', 'asc')->get();
        
        $formatted = $details->map(function($d) {
            return [
                'punch_in_time' => $d->punch_in_time ? \Carbon\Carbon::parse($d->punch_in_time)->format('h:i A') : null,
                'punch_out_time' => $d->punch_out_time ? \Carbon\Carbon::parse($d->punch_out_time)->format('h:i A') : null,
                'punch_method' => $d->device_name ?? 'Web',
            ];
        });

        return response()->json(['success' => true, 'data' => $formatted]);
    }

    public function updatePunches(Request $request)
    {
        $request->validate([
            'attendance_id' => 'nullable|exists:attendance_dailies,id',
            'employee_id' => 'required_without:attendance_id',
            'attendance_date' => 'required_without:attendance_id',
            'status' => 'required|string',
            'punch_in_time' => 'nullable',
            'punch_out_time' => 'nullable',
        ]);

        $business_id = auth()->user()->business_id ?? 1; // Fallback if needed

        if ($request->attendance_id) {
            $attendance = AttendanceDaily::findOrFail($request->attendance_id);
            $attendance->update([
                'status' => $request->status
            ]);
        } else {
            $attendance = AttendanceDaily::create([
                'business_id' => $business_id,
                'employee_id' => $request->employee_id,
                'attendance_date' => $request->attendance_date,
                'day' => \Carbon\Carbon::parse($request->attendance_date)->format('l'),
                'status' => $request->status,
                'working_time_for_day' => 0,
                'total_working_time' => 0,
                'is_late' => false,
                'late_minutes' => 0,
            ]);
        }

        // Just mock updating details, or we can update the first and last
        // If they provided punch_in_time, we overwrite the first detail record (or create one)
        if ($request->punch_in_time) {
            $firstDetail = $attendance->details()->orderBy('id', 'asc')->first();
            if ($firstDetail) {
                // Prepend date to time to form datetime if required, but the DB field might just be time or datetime
                // Assuming the field is datetime based on previous usage
                $inTimeStr = $attendance->date . ' ' . $request->punch_in_time . ':00';
                $firstDetail->update(['punch_in_time' => $inTimeStr, 'device_name' => 'Manual']);
            }
        }

        if ($request->punch_out_time) {
            $lastDetail = $attendance->details()->orderBy('id', 'desc')->first();
            if ($lastDetail) {
                $outTimeStr = $attendance->date . ' ' . $request->punch_out_time . ':00';
                $lastDetail->update(['punch_out_time' => $outTimeStr, 'device_name' => 'Manual']);
            }
        }

        return redirect()->back()->with('success', 'Attendance punches updated successfully.');
    }

    public function exportTemplate(Request $request)
    {
        // Placeholder for exporting attendance Excel template using Maatwebsite\Excel
        // return \Excel::download(new \App\Exports\AttendanceTemplateExport, 'attendance_template.xlsx');
        return back()->with('info', 'Export Template feature will be available once the export class is generated.');
    }

    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);
        
        // Placeholder for uploading Excel using Maatwebsite\Excel
        // \Excel::import(new \App\Imports\AttendanceImport, $request->file('file'));
        
        return back()->with('info', 'Upload feature will be available once the import class is generated.');
    }

    public function downloadData(Request $request)
    {
        // Placeholder for downloading actual data
        return back()->with('info', 'Download Data feature will be available once the export class is generated.');
    }
}