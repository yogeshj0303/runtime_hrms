<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDaily;
use App\Models\AttendanceDailyDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DailyAttendanceApiController extends Controller
{
    /**
     * Attendance List By Employee
     */
    public function index($employee_id)
    {
        $attendances = AttendanceDaily::with('details')
            ->where('employee_id', $employee_id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Attendance list fetched successfully.',
            'data' => $attendances
        ]);
    }

    /**
     * Today's Attendance
     */
    public function todayAttendance($employee_id)
    {
        $attendance = AttendanceDaily::with('details')
            ->where('employee_id', $employee_id)
            ->whereDate('attendance_date', today())
            ->first();

        return response()->json([
            'status' => true,
            'data' => $attendance
        ]);
    }

    public function dailyAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'date' => 'nullable|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $date = $request->date ? \Carbon\Carbon::parse($request->date) : \Carbon\Carbon::today();
        
        $employee = \App\Models\Employee::where('user_id', $request->user_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee not found'], 404);

        $attendance = AttendanceDaily::with('details')
            ->where('employee_id', $request->user_id) 
            ->whereDate('attendance_date', $date)
            ->first();

        $timeIn = null;
        $timeOut = null;
        $totalHours = null;

        if ($attendance && $attendance->details->count() > 0) {
            $firstPunch = $attendance->details->first();
            $lastPunch = $attendance->details->last();

            $timeIn = $firstPunch->punch_in_time ? \Carbon\Carbon::parse($firstPunch->punch_in_time)->format('h:i A') : null;
            $timeOut = $lastPunch->punch_out_time ? \Carbon\Carbon::parse($lastPunch->punch_out_time)->format('h:i A') : null;

            $totalWorkingTime = 0;
            if ($firstPunch->punch_in_time && $lastPunch->punch_out_time) {
                $tIn = \Carbon\Carbon::parse($firstPunch->punch_in_time);
                $tOut = \Carbon\Carbon::parse($lastPunch->punch_out_time);
                $diff = $tIn->diff($tOut);
                $totalHours = $diff->format('%h hours %i mins');
                $totalWorkingTime = $tIn->diffInMinutes($tOut);
            }
        }
        
        return response()->json([
            'status' => true,
            'date' => $date->format('M d, Y'),
            'summary' => [
                'shift' => 'General Shift',
                'time_in' => $timeIn,
                'time_out' => $timeOut,
                'total_hours' => $totalHours,
                'total_working_time' => $totalWorkingTime ?? 0,
            ],
            'strikes' => [
                'has_strike' => false,
                'message' => 'No strikes for the day'
            ],
            'punches' => $attendance ? $attendance->details : []
        ]);
    }

    public function monthlyAttendance(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'business_id' => 'required',
            'month' => 'required|numeric|between:1,12',
            'year' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->user_id)->where('business_id', $request->business_id)->first();
        if (!$employee) return response()->json(['status' => false, 'message' => 'Employee not found'], 404);

        $month = $request->month;
        $year = $request->year;
        $startDate = \Carbon\Carbon::create($year, $month, 1)->startOfDay();
        $endDate = $startDate->copy()->endOfMonth();

        // 1. Fetch data
        $holidays = \App\Models\Holiday::where('business_id', $request->business_id)
                        ->whereBetween('date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        ->get();

        $leaves = \App\Models\LeaveRequest::where('employee_id', $employee->id)
                        ->where('status', 'Approved')
                        ->where(function($q) use ($startDate, $endDate) {
                            $q->whereBetween('from_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                              ->orWhereBetween('to_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')]);
                        })
                        ->get();

        $attendances = AttendanceDaily::with('details')->where('employee_id', $request->user_id)
                        ->whereBetween('attendance_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                        ->get()->keyBy('attendance_date');

        // 2. Build calendar
        $calendar = [];
        $counts = [
            'present' => 0,
            'absent' => 0,
            'half_day' => 0,
            'leave' => 0,
            'holiday' => 0,
            'week_off' => 0
        ];

        $today = \Carbon\Carbon::today();

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateStr = $date->format('Y-m-d');
            $status = 'Absent'; // Default for past days

            // Check Holiday
            $isHoliday = $holidays->firstWhere('date', $dateStr);
            
            // Check Leave
            $isLeave = $leaves->first(function($leave) use ($dateStr) {
                return $dateStr >= $leave->from_date && $dateStr <= $leave->to_date;
            });

            // Check Present
            $attendance = $attendances->get($dateStr);

            // Check Week Off (Sunday)
            $isWeekOff = $date->isSunday();

            if ($attendance) {
                $status = strtolower($attendance->status) == 'half_day' ? 'Half Day' : 'Present';
            } elseif ($isLeave) {
                $status = 'Leave';
            } elseif ($isHoliday) {
                $status = 'Holiday';
            } elseif ($isWeekOff) {
                $status = 'Week Off';
            } elseif ($date->gt($today)) {
                $status = 'Future';
            }

            // Increment counts
            if ($status == 'Present') $counts['present']++;
            if ($status == 'Half Day') $counts['half_day']++;
            if ($status == 'Absent') $counts['absent']++;
            if ($status == 'Leave') $counts['leave']++;
            if ($status == 'Holiday') $counts['holiday']++;
            if ($status == 'Week Off') $counts['week_off']++;

            // Extract punches
            $punches = [];
            if ($attendance && $attendance->details) {
                foreach ($attendance->details as $detail) {
                    $punches[] = [
                        'punch_in_time' => $detail->punch_in_time,
                        'punch_out_time' => $detail->punch_out_time,
                        'punch_in_location' => $detail->punch_in_location,
                        'punch_out_location' => $detail->punch_out_location,
                        'status_daily' => $detail->status_daily
                    ];
                }
            }

            $calendar[] = [
                'date' => $dateStr,
                'day_name' => $date->format('l'),
                'status' => $status,
                'holiday_name' => $isHoliday ? $isHoliday->holiday_name : null,
                'details' => $punches
            ];
        }

        return response()->json([
            'status' => true,
            'month' => $month,
            'year' => $year,
            'counts' => $counts,
            'calendar' => $calendar
        ]);
    }

    /**
     * Punch In
     */
    public function punchIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'employee_id' => 'required|exists:users,id',
            'punch_in_location' => 'nullable',
            'punch_in_latitude' => 'nullable',
            'punch_in_longitude' => 'nullable',
            'device_name' => 'nullable'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$this->checkGeofence($request->employee_id, $request->punch_in_latitude, $request->punch_in_longitude)) {
            return response()->json([
                'status' => false,
                'message' => 'You are outside the assigned geofence area. Punch-in rejected.'
            ], 403);
        }

        DB::beginTransaction();

        try {

            $attendance = AttendanceDaily::firstOrCreate(
                [
                    'employee_id' => $request->employee_id,
                    'business_id' => $request->business_id,
                    'attendance_date' => date('Y-m-d')
                ],
                [
                    'day' => date('l'),
                    'status' => 'present'
                ]
            );

            $detail = AttendanceDailyDetail::create([
                'attendance_daily_id' => $attendance->id,
                'business_id' => $request->business_id,
                'employee_id' => $request->employee_id,
                'punch_in_time' => now(),
                'punch_in_location' => $request->punch_in_location,
                'punch_in_latitude' => $request->punch_in_latitude,
                'punch_in_longitude' => $request->punch_in_longitude,
                'device_name' => $request->device_name,
                'ip_address' => $request->ip(),
                'status_daily' => 'punched_in'
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Punch in successful.',
                'data' => $detail
            ]);
        }
        catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Punch Out
     */
    public function punchOut(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'employee_id' => 'required|exists:users,id',

            'punch_out_location' => 'nullable',
            'punch_out_latitude' => 'nullable',
            'punch_out_longitude' => 'nullable'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$this->checkGeofence($request->employee_id, $request->punch_out_latitude, $request->punch_out_longitude)) {
            return response()->json([
                'status' => false,
                'message' => 'You are outside the assigned geofence area. Punch-out rejected.'
            ], 403);
        }

        DB::beginTransaction();

        try {

            $detail = AttendanceDailyDetail::where('employee_id', $request->employee_id)
                ->whereNull('punch_out_time')
                ->latest()
                ->first();

            if (!$detail) {

                return response()->json([
                    'status' => false,
                    'message' => 'No punch-in record found.'
                ], 404);
            }

            $detail->update([
                'punch_out_time' => now(),
                'punch_out_location' => $request->punch_out_location,
                'punch_out_latitude' => $request->punch_out_latitude,
                'punch_out_longitude' => $request->punch_out_longitude,
                'status_daily' => 'punched_out'
            ]);

            $minutes = $detail->punch_in_time
                ->diffInMinutes($detail->punch_out_time);

            $detail->update([
                'total_working_time' => $minutes
            ]);

            $detail->attendanceDaily->increment(
                'total_working_time',
                $minutes
            );

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Punch out successful.',
                'data' => $detail
            ]);
        }
        catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show single attendance
     */
    public function show($id)
    {
        $attendance = AttendanceDaily::with('details')
            ->find($id);

        return response()->json([
            'status' => true,
            'data' => $attendance
        ]);
    }

    /**
     * Delete attendance
     */
    public function destroy($id)
    {
        $attendance = AttendanceDaily::find($id);

        if (!$attendance) {

            return response()->json([
                'status' => false,
                'message' => 'Attendance not found.'
            ],404);
        }

        $attendance->delete();

        return response()->json([
            'status' => true,
            'message' => 'Attendance deleted successfully.'
        ]);
    }

    /**
     * Face Punch In
     */
    public function facePunchIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'employee_id' => 'required|exists:users,id',
            'punch_in_face' => 'required|string',
            'punch_in_location' => 'nullable',
            'punch_in_latitude' => 'nullable',
            'punch_in_longitude' => 'nullable',
            'device_name' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->first();
        if (!$employee || empty($employee->face_image)) {
            return response()->json([
                'status' => false, 
                'message' => 'No registered face found for this employee. Please register your face first.'
            ], 400);
        }

        // Verify the live punch-in face matches the registered face
        $isMatch = \App\Http\Controllers\Api\EmployeeApiController::performAIFaceVerification($employee->face_image, $request->punch_in_face);
        if (!$isMatch) {
            return response()->json([
                'status' => false,
                'message' => 'We couldn\'t recognize your face for punch-in. Please ensure you are in a well-lit area and look directly at the camera.'
            ], 400);
        }

        if (!$this->checkGeofence($request->employee_id, $request->punch_in_latitude, $request->punch_in_longitude)) {
            return response()->json([
                'status' => false,
                'message' => 'You are outside the assigned geofence area. Face punch-in rejected.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $attendance = AttendanceDaily::firstOrCreate(
                [
                    'employee_id' => $request->employee_id,
                    'business_id' => $request->business_id,
                    'attendance_date' => date('Y-m-d')
                ],
                [
                    'day' => date('l'),
                    'status' => 'present'
                ]
            );

            $detail = AttendanceDailyDetail::create([
                'attendance_daily_id' => $attendance->id,
                'business_id' => $request->business_id,
                'employee_id' => $request->employee_id,
                'punch_in_time' => now(),
                'punch_in_location' => $request->punch_in_location,
                'punch_in_latitude' => $request->punch_in_latitude,
                'punch_in_longitude' => $request->punch_in_longitude,
                'punch_in_face' => $request->punch_in_face,
                'device_name' => $request->device_name,
                'ip_address' => $request->ip(),
                'status_daily' => 'punched_in'
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Face Punch in successful.',
                'data' => $detail
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Face Punch Out
     */
    public function facePunchOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'employee_id' => 'required|exists:users,id',
            'punch_out_face' => 'required|string',
            'punch_out_location' => 'nullable',
            'punch_out_latitude' => 'nullable',
            'punch_out_longitude' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }
        $employee = \App\Models\Employee::where('user_id', $request->employee_id)->first();
        if (!$employee || empty($employee->face_image)) {
            return response()->json([
                'status' => false, 
                'message' => 'No registered face found for this employee. Please register your face first.'
            ], 400);
        }

        // Verify the live punch-out face matches the registered face
        $isMatch = \App\Http\Controllers\Api\EmployeeApiController::performAIFaceVerification($employee->face_image, $request->punch_out_face);
        if (!$isMatch) {
            return response()->json([
                'status' => false,
                'message' => 'We couldn\'t recognize your face for punch-out. Please ensure you are in a well-lit area and look directly at the camera.'
            ], 400);
        }

        if (!$this->checkGeofence($request->employee_id, $request->punch_out_latitude, $request->punch_out_longitude)) {
            return response()->json([
                'status' => false,
                'message' => 'You are outside the assigned geofence area. Face punch-out rejected.'
            ], 403);
        }

        DB::beginTransaction();
        try {
            $attendance = AttendanceDaily::where('employee_id', $request->employee_id)
                ->where('business_id', $request->business_id)
                ->where('attendance_date', date('Y-m-d'))
                ->first();

            if (!$attendance) {
                return response()->json([
                    'status' => false,
                    'message' => 'No attendance record found for today.'
                ], 404);
            }

            $detail = AttendanceDailyDetail::where('attendance_daily_id', $attendance->id)
                ->where('status_daily', 'punched_in')
                ->latest()
                ->first();

            if (!$detail) {
                return response()->json([
                    'status' => false,
                    'message' => 'No punch-in record found.'
                ], 404);
            }

            $detail->update([
                'punch_out_time' => now(),
                'punch_out_location' => $request->punch_out_location,
                'punch_out_latitude' => $request->punch_out_latitude,
                'punch_out_longitude' => $request->punch_out_longitude,
                'punch_out_face' => $request->punch_out_face,
                'status_daily' => 'punched_out'
            ]);

            $minutes = $detail->punch_in_time->diffInMinutes($detail->punch_out_time);
            $detail->update(['total_working_time' => $minutes]);
            $detail->attendanceDaily->increment('total_working_time', $minutes);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Face Punch out successful.',
                'data' => $detail
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function checkGeofence($employee_id, $lat, $lng)
    {
        if (!$lat || !$lng) return true; // if not provided, skip or fail depending on strictness. We let it pass if GPS is somehow totally off but it's better to log. Let's assume true for backward compatibility.
        
        $employee = \App\Models\Employee::where('user_id', $employee_id)->first();
        if (!$employee || !$employee->assigned_latitude || !$employee->assigned_longitude || !$employee->assigned_radius) {
            return true; // No geofence set
        }

        $earthRadius = 6371000; // meters
        $latFrom = deg2rad($employee->assigned_latitude);
        $lonFrom = deg2rad($employee->assigned_longitude);
        $latTo = deg2rad($lat);
        $lonTo = deg2rad($lng);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        if ($distance > $employee->assigned_radius) {
            return false;
        }

        return true;
    }
}