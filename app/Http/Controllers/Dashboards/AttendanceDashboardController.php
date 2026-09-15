<?php

namespace App\Http\Controllers\Dashboards;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Business;
use App\Models\Employee;
use App\Models\Location;
use App\Models\Department;
use App\Models\AttendanceDaily;
use App\Models\AttendanceDailyDetail;
use Carbon\Carbon;

class AttendanceDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $business_id = $user->active_business_id;

        if (!$business_id) {
            return redirect()->route('business.dashboard');
        }

        // Filters
        $dateFilter = $request->input('date', date('Y-m-d'));
        $locationId = $request->input('location_id');
        $departmentId = $request->input('department_id');
        $topX = $request->input('top_x', 10);

        // Fetch filter options
        $locations = Location::where('business_id', $business_id)->get();
        $departments = Department::where('business_id', $business_id)->get();

        // Base query for employees based on filters
        $employeeQuery = Employee::where('business_id', $business_id)->where('status', 'Active');
        
        if ($locationId || $departmentId) {
            $employeeQuery->whereHas('workProfiles', function($q) use ($locationId, $departmentId) {
                if ($locationId) {
                    $q->where('location_id', $locationId);
                }
                if ($departmentId) {
                    $q->where('department_id', $departmentId);
                }
            });
        }
        
        $employeeIds = $employeeQuery->pluck('user_id')->toArray();
        $employeeRecordIds = $employeeQuery->pluck('id')->toArray();
        $totalEmployees = count($employeeIds);

        // Fetch Attendance for the selected date
        $attendances = AttendanceDaily::where('business_id', $business_id)
            ->whereIn('employee_id', $employeeIds)
            ->where('attendance_date', $dateFilter)
            ->get();

        // Calculations for Attendance Chart
        $presentCount = $attendances->where('status', 'Present')->count();
        $halfDayCount = $attendances->where('status', 'Half_Day')->count();
        $leavesCount = \App\Models\LeaveRequest::where('business_id', $business_id)
            ->whereIn('employee_id', $employeeRecordIds)
            ->where('status', 'Approved')
            ->where('from_date', '<=', $dateFilter)
            ->where('to_date', '>=', $dateFilter)
            ->count();

        $presentOrLeaveIds = $attendances->whereIn('status', ['Present', 'Half_Day'])->pluck('employee_id')->toArray();
        $onLeaveRecordIds = \App\Models\LeaveRequest::where('business_id', $business_id)
            ->whereIn('employee_id', $employeeRecordIds)
            ->where('status', 'Approved')
            ->where('from_date', '<=', $dateFilter)
            ->where('to_date', '>=', $dateFilter)
            ->pluck('employee_id')->toArray();
            
        $onLeaveUserIds = Employee::whereIn('id', $onLeaveRecordIds)->pluck('user_id')->toArray();
        $presentOrLeaveUserIds = array_unique(array_merge($presentOrLeaveIds, $onLeaveUserIds));

        // Evaluate WeekOffs, Holidays, and Absents dynamically per employee
        $isHoliday = \App\Models\Holiday::where('business_id', $business_id)->where('date', $dateFilter)->exists();
        $dayOfWeekString = strtolower(date('l', strtotime($dateFilter))) . '_weeks';
        
        // Calculate week of month correctly (e.g. 1st Saturday, 2nd Saturday)
        $firstDayOfMonth = date('Y-m-01', strtotime($dateFilter));
        $dayOfWeekFirstDay = date('w', strtotime($firstDayOfMonth)); // 0 (Sun) to 6 (Sat)
        $dayOfMonth = date('j', strtotime($dateFilter));
        $weekOfMonth = ceil(($dayOfMonth + $dayOfWeekFirstDay) / 7);
        // Better: week of month specifically for that weekday
        // e.g., If today is 25th, and it's a Saturday, it's the 4th Saturday. 25 / 7 = 3.57 => 4th.
        $weekOfMonthExact = ceil($dayOfMonth / 7);

        $weekOffUserIds = [];
        $holidayUserIds = [];
        $absentUserIds = [];

        $employeesWithPolicy = Employee::with('policy.weekOffPolicy')->whereIn('user_id', $employeeIds)->get();
        $attendanceRecordsByEmployee = $attendances->keyBy('employee_id');

        foreach ($employeesWithPolicy as $emp) {
            // Check if they are on approved leave
            if (in_array($emp->user_id, $onLeaveUserIds)) {
                continue; 
            }

            // If there's an explicit daily attendance record, respect its status first
            $record = $attendanceRecordsByEmployee->get($emp->user_id);
            if ($record) {
                $status = strtolower($record->status);
                if (in_array($status, ['present', 'half_day'])) {
                    continue; // Already counted in presents
                } elseif ($status === 'absent') {
                    $absentUserIds[] = $emp->user_id;
                    continue;
                } elseif ($status === 'weekoff') {
                    $weekOffUserIds[] = $emp->user_id;
                    continue;
                } elseif ($status === 'holiday') {
                    $holidayUserIds[] = $emp->user_id;
                    continue;
                }
            }

            // If no explicit record, deduce based on policy
            $isEmpWeekOff = false;
            $weekOffPolicy = $emp->policy->weekOffPolicy ?? null;
            if ($weekOffPolicy) {
                $weeks = $weekOffPolicy->$dayOfWeekString ?? [];
                if (is_array($weeks) && (in_array((string)$weekOfMonthExact, $weeks) || in_array($weekOfMonthExact, $weeks))) {
                    $isEmpWeekOff = true;
                }
            } else {
                // Fallback to default weekend (Sunday & Saturday)
                $w = date('w', strtotime($dateFilter));
                $isEmpWeekOff = ($w == 0 || $w == 6);
            }

            if ($isEmpWeekOff) {
                $weekOffUserIds[] = $emp->user_id;
            } elseif ($isHoliday) {
                $holidayUserIds[] = $emp->user_id;
            } else {
                $absentUserIds[] = $emp->user_id;
            }
        }

        $weekOffCount = count($weekOffUserIds);
        $holidayCount = count($holidayUserIds);
        $absentCount = count($absentUserIds);

        $attendanceData = [$presentCount, $leavesCount, $absentCount, $weekOffCount, $holidayCount];

        // Late Comers vs On-time (Among those who punched in)
        $lateComers = $attendances->where('is_late', true)->count();
        $onTime = max(0, $presentCount + $halfDayCount - $lateComers);
        $lateComersData = [$onTime, $lateComers];

        // Fetch employee lists for the UI tabs
        $leavesList = Employee::with(['workProfiles.department', 'workProfiles.location', 'user'])
            ->whereIn('id', $onLeaveRecordIds)
            ->take($topX)
            ->get();

        $lateComersList = collect();
        $earlyGoersList = collect();
        $earlyGoersCount = 0;

        foreach ($attendances as $att) {
            $empRecord = Employee::with(['workProfiles.department', 'workProfiles.location', 'user', 'policy.shiftPolicy.defaultShift'])
                ->where('user_id', $att->employee_id)->first();
            
            if ($empRecord) {
                // Fetch dynamic punch in and out times
                $firstDetail = AttendanceDailyDetail::where('attendance_daily_id', $att->id)->orderBy('punch_in_time', 'asc')->first();
                $lastDetail = AttendanceDailyDetail::where('attendance_daily_id', $att->id)->orderBy('punch_out_time', 'desc')->first();
                
                $empRecord->time_in = $firstDetail && $firstDetail->punch_in_time ? \Carbon\Carbon::parse($firstDetail->punch_in_time)->format('H:i') : '-';
                $empRecord->time_out = $lastDetail && $lastDetail->punch_out_time ? \Carbon\Carbon::parse($lastDetail->punch_out_time)->format('H:i') : '-';

                if ($att->is_late) {
                    $lateComersList->push($empRecord);
                }

                if ($lastDetail && $lastDetail->punch_out_time) {
                    $shift = $empRecord->policy->shiftPolicy->defaultShift ?? null;
                    if ($shift && $shift->end_time) {
                        $outTime = \Carbon\Carbon::parse($lastDetail->punch_out_time)->format('H:i:s');
                        if ($outTime < $shift->end_time) {
                            $earlyGoersCount++;
                            $earlyGoersList->push($empRecord);
                        }
                    }
                }
            }
        }
        
        $lateComersList = $lateComersList->take($topX);
        $earlyGoersList = $earlyGoersList->take($topX);

        $onTimeEarly = max(0, $presentCount + $halfDayCount - $earlyGoersCount);
        $earlyGoersData = [$onTimeEarly, $earlyGoersCount];

        $absentEmployeeIds = $absentUserIds;

        $topAbsentees = Employee::with(['workProfiles.department', 'workProfiles.location', 'user'])
            ->whereIn('user_id', $absentEmployeeIds)
            ->take($topX)
            ->get();

        return view('dashboards.attendance', compact(
            'dateFilter', 'locationId', 'departmentId', 'topX',
            'locations', 'departments',
            'attendanceData', 'lateComersData', 'earlyGoersData',
            'topAbsentees', 'leavesList', 'lateComersList', 'earlyGoersList'
        ));
    }
}
