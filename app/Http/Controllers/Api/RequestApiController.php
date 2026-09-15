<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use App\Models\MissingPunchRequest;

class RequestApiController extends Controller
{
  public function storeMissingPunch(Request $request)
{
    $validator = Validator::make($request->all(),[
        'business_id'=>'required|exists:businesses,id',
        'employee_id'=>'required|exists:users,id',
        'attendance_date'=>'required|date',
        'request_type'=>'required|in:punch_in,punch_out,both',
        'reason'=>'required',
    ]);

    if($validator->fails()){
        return response()->json([
            'status'=>false,
            'errors'=>$validator->errors()
        ],422);
    }

    $requestData = MissingPunchRequest::create([
        'business_id'=>$request->business_id,
        'employee_id'=>$request->employee_id,
        'attendance_date'=>$request->attendance_date,
        'requested_punch_in'=>$request->requested_punch_in,
        'requested_punch_out'=>$request->requested_punch_out,
        'request_type'=>$request->request_type,
        'reason'=>$request->reason
    ]);

    return response()->json([
        'status'=>true,
        'message'=>'Missing punch request submitted successfully.',
        'data'=>$requestData
    ]);
}
public function employeeMissingPunchRequests($employee_id)
{
    $requests = MissingPunchRequest::where(
        'employee_id',
        $employee_id
    )->latest()->get();

    return response()->json([
        'status'=>true,
        'data'=>$requests
    ]);
}

public function employeeApprovedMissingPunchRequests($employee_id)
{
    $requests = MissingPunchRequest::where('employee_id', $employee_id)
        ->where('status', 'approved')
        ->latest()
        ->get();

    return response()->json([
        'status'=>true,
        'data'=>$requests
    ]);
}

 public function requestLeave(Request $request)
{
    $validator = Validator::make($request->all(), [
        'business_id' => 'required|exists:businesses,id',
        'user_id' => 'required|exists:users,id',
        'leave_type_id' => 'required|exists:leave_types,id',
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'reason' => 'required',
        'attachment' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ], 422);
    }

    $days = Carbon::parse($request->start_date)
        ->diffInDays(
            Carbon::parse($request->end_date)
        ) + 1;

    $attachmentPath = null;
    if ($request->hasFile('attachment')) {
        $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
    }

    $leave = LeaveRequest::create([
        'business_id' => $request->business_id,
        'employee_id' => $request->user_id,
        'leave_type_id' => $request->leave_type_id,
        'from_date' => $request->start_date,
        'to_date' => $request->end_date,
        'total_days' => $days,
        'reason' => $request->reason,
        'attachment' => $attachmentPath
    ]);

    return response()->json([
        'status' => true,
        'message' => 'Leave request submitted successfully.',
        'data' => $leave
    ]);
}
}


