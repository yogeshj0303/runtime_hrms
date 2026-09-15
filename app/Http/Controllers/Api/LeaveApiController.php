<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeaveApiController extends Controller
{

public function leaveTypes($business_id)
{
    $types = LeaveType::where('business_id', $business_id)->where('status', 'active')->get();

    $mappedTypes = $types->map(function($t) {
        return [
            'id' => $t->id,
            'name' => $t->name ?? $t->type_name ?? 'Leave',
            'available_balance' => $t->default_allowance ?? 0 // If available_balance needs to be per-employee, a new endpoint with user_id is recommended, but for now we supply the key.
        ];
    });

    return response()->json([
        'status' => true,
        'data' => $mappedTypes
    ]);
}
public function employeeLeaves($employee_id)
{
    $leaves = LeaveRequest::with('leaveType')
        ->where('employee_id', $employee_id)
        ->latest()
        ->get();

    $mappedLeaves = $leaves->map(function($l) {
        return [
            'id' => $l->id,
            'status' => ucfirst($l->status),
            'applied_on' => $l->created_at->format('Y-m-d'),
            'leave_type' => $l->leaveType ? ($l->leaveType->name ?? $l->leaveType->type_name) : 'N/A',
            'days_count' => $l->total_days,
            'from_date' => $l->from_date,
            'to_date' => $l->to_date,
            'reason' => $l->reason
        ];
    });

    return response()->json([
        'status' => true,
        'data' => $mappedLeaves
    ]);
}

public function employeeApprovedLeaves($employee_id)
{
    $leaves = LeaveRequest::with('leaveType')
        ->where('employee_id', $employee_id)
        ->where('status', 'approved')
        ->latest()
        ->get();

    $mappedLeaves = $leaves->map(function($l) {
        return [
            'id' => $l->id,
            'status' => ucfirst($l->status),
            'applied_on' => $l->created_at->format('Y-m-d'),
            'leave_type' => $l->leaveType ? ($l->leaveType->name ?? $l->leaveType->type_name) : 'N/A',
            'days_count' => $l->total_days,
            'from_date' => $l->from_date,
            'to_date' => $l->to_date,
            'reason' => $l->reason
        ];
    });

    return response()->json([
        'status' => true,
        'data' => $mappedLeaves
    ]);
}

public function employeeRejectedLeaves($employee_id)
{
    $leaves = LeaveRequest::with('leaveType')
        ->where('employee_id', $employee_id)
        ->where('status', 'rejected')
        ->latest()
        ->get();

    $mappedLeaves = $leaves->map(function($l) {
        return [
            'id' => $l->id,
            'status' => ucfirst($l->status),
            'applied_on' => $l->created_at->format('Y-m-d'),
            'leave_type' => $l->leaveType ? ($l->leaveType->name ?? $l->leaveType->type_name) : 'N/A',
            'days_count' => $l->total_days,
            'from_date' => $l->from_date,
            'to_date' => $l->to_date,
            'reason' => $l->reason
        ];
    });

    return response()->json([
        'status' => true,
        'data' => $mappedLeaves
    ]);
}
}
