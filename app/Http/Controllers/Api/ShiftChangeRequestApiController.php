<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShiftChangeRequest;
use App\Models\EmployeeShiftHistory;

class ShiftChangeRequestApiController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'business_id' => 'required',
            'employee_id' => 'required|exists:users,id',
            'requested_shift_id' => 'required',
            'effective_from' => 'required|date',
            'reason' => 'nullable|string'
        ]);

        // Current active shift
        $currentShift = EmployeeShiftHistory::where('employee_id', $request->employee_id)
            ->where('is_active', 1)
            ->latest()
            ->first();

        if (!$currentShift) {
            return response()->json([
                'status' => false,
                'message' => 'No active shift assigned to employee.'
            ], 404);
        }

        // Prevent duplicate pending requests
        $pendingRequest = ShiftChangeRequest::where('employee_id', $request->employee_id)
            ->where('status', 'pending')
            ->first();

        if ($pendingRequest) {
            return response()->json([
                'status' => false,
                'message' => 'A shift change request is already pending approval.'
            ], 422);
        }

        $shiftRequest = ShiftChangeRequest::create([
            'business_id' => $request->business_id,
            'employee_id' => $request->employee_id,
            'current_shift_id' => $currentShift->shift_id,
            'requested_shift_id' => $request->requested_shift_id,
            'effective_from' => $request->effective_from,
            'reason' => $request->reason
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Shift change request submitted successfully.',
            'data' => $shiftRequest
        ]);
    }

    public function requestList($employee_id)
    {
        $requests = ShiftChangeRequest::with([
            'currentShift',
            'requestedShift'
        ])
            ->where('employee_id', $employee_id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Shift request list fetched successfully.',
            'data' => $requests
        ]);
    }

    public function approvedList($employee_id)
    {
        $requests = ShiftChangeRequest::with([
            'currentShift',
            'requestedShift'
        ])
            ->where('employee_id', $employee_id)
            ->where('status', 'approved')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Approved shift request list fetched successfully.',
            'data' => $requests
        ]);
    }
}