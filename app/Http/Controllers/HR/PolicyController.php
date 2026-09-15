<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeavePolicy;
use App\Models\ShiftPolicy;
use App\Models\WeekOffPolicy;
use App\Models\OvertimePolicy;
use App\Models\MaternityLeavePolicy;

class PolicyController extends Controller
{
    public function index()
    {
        $activeBusiness = \App\Models\UserBusinessSession::where('user_id', \Illuminate\Support\Facades\Auth::id())
            ->where('status', 'ACTIVE')
            ->latest()
            ->first();

        $business_id = $activeBusiness ? $activeBusiness->business_id : session('business_id');
        
        if (!$business_id) {
            $business_id = \App\Models\Business::first()->id ?? 1;
        }

        $leavePolicies = LeavePolicy::with('leaveType')->where('business_id', $business_id)->get();
        $shiftPolicies = ShiftPolicy::where('business_id', $business_id)->get();
        $weekOffPolicies = WeekOffPolicy::where('business_id', $business_id)->get();
        $overtimePolicies = OvertimePolicy::where('business_id', $business_id)->get();
        $maternityPolicies = MaternityLeavePolicy::where('business_id', $business_id)->get();

        return view('admin.hr.policies.index', compact(
            'leavePolicies', 
            'shiftPolicies', 
            'weekOffPolicies', 
            'overtimePolicies', 
            'maternityPolicies'
        ));
    }
}
