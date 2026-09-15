<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Location;
use App\Models\CostCenter;
use App\Models\Department;
use App\Models\AttendanceDaily;
use App\Models\EmployeeStrike;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class AttendanceReportController extends Controller
{
    public function attendanceRegister(Request $request)
    {
        $locations = Location::all();
        $costCenters = CostCenter::all();
        $departments = Department::all();
        $employeesList = Employee::orderBy('first_name')->get();

        $fromDate = $request->input('from_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $query = Employee::query();

        $workProfileFilters = ['location_id', 'cost_center_id', 'department_id'];
        foreach ($workProfileFilters as $filter) {
            if ($request->filled($filter) && $request->$filter !== 'All') {
                $query->whereHas('workProfiles', function($q) use ($filter, $request) {
                    $q->where($filter, $request->$filter)
                      ->where('is_current', true);
                });
            }
        }

        if ($request->filled('employee_id') && $request->employee_id !== 'All') {
            $query->where('id', $request->employee_id);
        }
        if ($request->filled('record_status')) {
            if ($request->record_status == 'active') {
                $query->where('status', 'active');
            } elseif ($request->record_status == 'inactive') {
                $query->where('status', '!=', 'active');
            }
        }

        $employees = $query->get();

        $period = CarbonPeriod::create($fromDate, $toDate);
        $dates = [];
        foreach ($period as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        $showPunches = $request->has('show_punches');
        $showStrikes = $request->has('show_strikes');
        $showSummary = $request->has('show_summary');

        // Preload attendances
        $attendances = AttendanceDaily::with('details')
            ->whereIn('employee_id', $employees->pluck('user_id'))
            ->whereBetween('attendance_date', [$fromDate, $toDate])
            ->get()
            ->groupBy(function($item) {
                return $item->employee_id . '_' . $item->attendance_date->format('Y-m-d');
            });

        // Preload strikes
        $strikes = [];
        if ($showStrikes) {
            $strikes = EmployeeStrike::whereIn('employee_id', $employees->pluck('id'))
                ->whereBetween('strike_date', [$fromDate, $toDate])
                ->get()
                ->groupBy(function($item) {
                    return $item->employee_id . '_' . $item->strike_date->format('Y-m-d');
                });
        }

        $reportData = [];

        foreach ($employees as $employee) {
            $empData = [
                'employee' => $employee,
                'attendance' => [],
                'summary' => [
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'early_going' => 0,
                    'strikes' => 0,
                ]
            ];

            foreach ($dates as $date) {
                $key = $employee->user_id . '_' . $date;
                $empKey = $employee->id . '_' . $date;
                
                $dailyRecord = $attendances->get($key) ? $attendances->get($key)->first() : null;
                $strikeRecord = isset($strikes[$empKey]) ? $strikes[$empKey]->first() : null;
                
                $status = 'A';
                $punches = [];
                $isLate = false;
                
                if ($dailyRecord) {
                    if ($dailyRecord->status == 'Present' || $dailyRecord->status == 'punched_in' || $dailyRecord->status == 'punched_out' || $dailyRecord->status == 'Present (Late)') {
                        $status = 'P';
                        $empData['summary']['present']++;
                    } elseif ($dailyRecord->status == 'Week Off') {
                        $status = 'WO';
                    } elseif ($dailyRecord->status == 'Holiday') {
                        $status = 'H';
                    } elseif ($dailyRecord->status == 'Leave') {
                        $status = 'L';
                    } else {
                        $empData['summary']['absent']++;
                    }

                    if ($dailyRecord->is_late) {
                        $isLate = true;
                        $empData['summary']['late']++;
                    }

                    if ($showPunches && $dailyRecord->details) {
                        foreach ($dailyRecord->details as $detail) {
                            $in = $detail->punch_in_time ? $detail->punch_in_time->format('H:i') : '--:--';
                            $out = $detail->punch_out_time ? $detail->punch_out_time->format('H:i') : '--:--';
                            $punches[] = $in . ' - ' . $out;
                        }
                    }
                } else {
                    $empData['summary']['absent']++;
                }

                $hasStrike = false;
                if ($showStrikes && $strikeRecord) {
                    $hasStrike = true;
                    $empData['summary']['strikes']++;
                }

                $empData['attendance'][$date] = [
                    'status' => $status,
                    'punches' => $punches,
                    'is_late' => $isLate,
                    'has_strike' => $hasStrike
                ];
            }

            $reportData[] = $empData;
        }

        return view('admin.report.attendance_register.index', compact(
            'locations', 'costCenters', 'departments', 'employeesList',
            'fromDate', 'toDate', 'dates', 'reportData',
            'showPunches', 'showStrikes', 'showSummary'
        ));
    }
}
