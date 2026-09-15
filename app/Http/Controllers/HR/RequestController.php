<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MissingPunchRequest;
use App\Models\LeaveRequest;
use App\Models\HelpdeskPermissionRequest;
use App\Models\AttendanceDaily;
use App\Models\AttendanceDailyDetail;
use App\Models\LeaveBalance;
use App\Models\LeaveBalanceHistory;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    // === Attendance Requests (Missing Punch) ===
    public function attendance(Request $request)
    {
        $business_id = session('business_id');
        $query = MissingPunchRequest::with('employee');
        // if ($business_id) {
        //     $query->where('business_id', $business_id);
        // }
        $requests = $query->get();
        $pendingRequests = $requests->filter(function($r) { return strtolower($r->status) == 'pending'; });
        $approvedRequests = $requests->filter(function($r) { return strtolower($r->status) == 'approved'; });
        $rejectedRequests = $requests->filter(function($r) { return strtolower($r->status) == 'rejected'; });
        return view('admin.hr.requests.attendance', compact('pendingRequests', 'approvedRequests', 'rejectedRequests'));
    }

    public function attendanceAction(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_remark' => 'required|string|max:255',
            'punch_in' => 'required_if:action,approve',
            'punch_out' => 'required_if:action,approve',
        ]);

        $punchRequest = MissingPunchRequest::findOrFail($id);

        if ($request->action == 'approve') {
            DB::transaction(function () use ($punchRequest, $request) {
                $punchRequest->update([
                    'status' => 'Approved',
                    'admin_remark' => $request->admin_remark,
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                ]);

                $attendance = AttendanceDaily::updateOrCreate(
                    [
                        'business_id' => $punchRequest->business_id,
                        'employee_id' => $punchRequest->employee_id,
                        'attendance_date' => $punchRequest->attendance_date,
                    ],
                    [
                        'status' => 'Present',
                        'day' => Carbon::parse($punchRequest->attendance_date)->format('l'),
                    ]
                );

                AttendanceDailyDetail::create([
                    'attendance_daily_id' => $attendance->id,
                    'business_id' => $punchRequest->business_id,
                    'employee_id' => $punchRequest->employee_id,
                    'punch_in_time' => $request->punch_in,
                    'punch_out_time' => $request->punch_out,
                    'is_manual' => 1,
                    'manual_remark' => $request->admin_remark,
                ]);
            });

            return redirect()->back()->with('success', 'Attendance Request Approved Successfully');
        } else {
            $punchRequest->update([
                'status' => 'Rejected',
                'admin_remark' => $request->admin_remark,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Attendance Request Rejected');
        }
    }

    // === Leave Requests ===
    public function leave(Request $request)
    {
        $business_id = session('business_id');
        $query = LeaveRequest::with(['leaveType', 'employee']);
        // if ($business_id) {
        //     $query->where('business_id', $business_id);
        // }
        $requests = $query->get();
        $pendingRequests = $requests->filter(function($r) { return strtolower($r->status) == 'pending'; });
        $approvedRequests = $requests->filter(function($r) { return strtolower($r->status) == 'approved'; });
        $rejectedRequests = $requests->filter(function($r) { return strtolower($r->status) == 'rejected'; });
        return view('admin.hr.requests.leave', compact('pendingRequests', 'approvedRequests', 'rejectedRequests'));
    }

    public function leaveAction(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_remark' => 'required|string|max:255',
        ]);

        $leaveRequest = LeaveRequest::findOrFail($id);

        if ($request->action == 'approve') {
            // Check balance
            $balance = LeaveBalance::where('employee_id', $leaveRequest->employee_id)
                                    ->where('leave_type_id', $leaveRequest->leave_type_id)
                                    ->first();
            
            if (!$balance || $balance->balance < $leaveRequest->total_days) {
                return redirect()->back()->with('error', 'Insufficient leave balance.');
            }

            DB::transaction(function () use ($leaveRequest, $request, $balance) {
                // Deduct balance
                $balance->balance -= $leaveRequest->total_days;
                $balance->save();

                // Log History
                LeaveBalanceHistory::create([
                    'business_id' => $leaveRequest->business_id,
                    'employee_id' => $leaveRequest->employee_id,
                    'leave_type_id' => $leaveRequest->leave_type_id,
                    'action' => 'deduct',
                    'days' => $leaveRequest->total_days,
                    'reason' => 'Leave Approved. Remark: ' . $request->admin_remark,
                ]);

                // Update Request Status
                $leaveRequest->update([
                    'status' => 'Approved',
                    'remarks' => $request->admin_remark,
                ]);
            });

            return redirect()->back()->with('success', 'Leave Request Approved and Balance Deducted');
        } else {
            $leaveRequest->update([
                'status' => 'Rejected',
                'remarks' => $request->admin_remark,
            ]);

            return redirect()->back()->with('success', 'Leave Request Rejected');
        }
    }

    // === Helpdesk Permission Requests ===
    public function helpdesk(Request $request)
    {
        $business_id = session('business_id');
        $query = HelpdeskPermissionRequest::with('employee');
        // if ($business_id) {
        //     $query->where('business_id', $business_id);
        // }
        $requests = $query->get();
        $pendingRequests = $requests->filter(function($r) { return strtolower($r->status) == 'pending'; });
        $approvedRequests = $requests->filter(function($r) { return strtolower($r->status) == 'approved'; });
        $rejectedRequests = $requests->filter(function($r) { return strtolower($r->status) == 'rejected'; });
        return view('admin.hr.requests.helpdesk', compact('pendingRequests', 'approvedRequests', 'rejectedRequests'));
    }

    public function helpdeskAction(Request $request, $id)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_remark' => 'required|string|max:255',
        ]);

        $helpdeskReq = HelpdeskPermissionRequest::findOrFail($id);

        if ($request->action == 'approve') {
            DB::transaction(function () use ($helpdeskReq, $request) {
                $employee = Employee::find($helpdeskReq->employee_id);
                if ($employee) {
                    $employee->allow_cross_department_tickets = 1;
                    $employee->save();
                }

                $helpdeskReq->update([
                    'status' => 'Approved',
                ]);
            });
            return redirect()->back()->with('success', 'Helpdesk Request Approved');
        } else {
            $helpdeskReq->update([
                'status' => 'Rejected',
            ]);
            return redirect()->back()->with('success', 'Helpdesk Request Rejected');
        }
    }
}
