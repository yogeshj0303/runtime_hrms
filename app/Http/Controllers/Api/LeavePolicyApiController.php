<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LeavePolicy;

class LeavePolicyApiController extends Controller
{
    public function index($business_id)
    {
        $policies = LeavePolicy::with([
            'leaveType',
            'business',
            'user'
        ])
        ->where('business_id', $business_id)
        ->get();

        return response()->json([
            'status' => true,
            'message' => 'Leave policies fetched successfully',
            'data' => $policies
        ]);
    }
}
