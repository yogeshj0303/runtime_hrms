<?php

namespace App\Http\Controllers\Setup\Salary;

use App\Http\Controllers\Controller;
use App\Models\OvertimePolicy;
use App\Models\OvertimeRule;
use App\Models\UserBusinessSession;
use App\Models\SalaryComponent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OvertimeController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
{
    $activeBusiness = UserBusinessSession::where(
        'user_id',
        Auth::id()
    )
    ->where(
        'status',
        'ACTIVE'
    )
    ->latest()
    ->first();

    $policies = OvertimePolicy::where(
        'business_id',
        $activeBusiness->business_id
    )
    ->latest()
    ->get();

    $selectedPolicy = $policies->first();

    $rules = collect();

    if ($selectedPolicy) {

        $rules = OvertimeRule::where(
            'overtime_policy_id',
            $selectedPolicy->id
        )
        ->latest()
        ->get();

    }

    $components = SalaryComponent::where(
        'business_id',
        $activeBusiness->business_id
    )
    ->get();

    return view(
        'admin.setup.salary-deduction.overtime.index',
        compact(
            'policies',
            'selectedPolicy',
            'rules',
            'components'
        )
    );
}


    /*
    |--------------------------------------------------------------------------
    | Store Policy
    |--------------------------------------------------------------------------
    */

    public function storePolicy(Request $request)
    {
        $request->validate([

            'policy_name' => 'required'

        ]);

        $activeBusiness = UserBusinessSession::where(
            'user_id',
            Auth::id()
        )
        ->where(
            'status',
            'ACTIVE'
        )
        ->latest()
        ->first();

        OvertimePolicy::create([

            'business_id' => $activeBusiness->business_id,

            'auth_id' => Auth::id(),

            'policy_name' => $request->policy_name,

            'salary_treatment' => $request->salary_treatment,

            'days_in_month' => $request->days_in_month,

            'hours_in_day' => $request->hours_in_day

        ]);

        return back()->with(
            'success',
            'Overtime Policy Added Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Update Policy
    |--------------------------------------------------------------------------
    */

    public function updatePolicy(Request $request, $id)
    {
        $policy = OvertimePolicy::findOrFail($id);

        $policy->update([

            'policy_name' => $request->policy_name,

            'salary_treatment' => $request->salary_treatment,

            'days_in_month' => $request->days_in_month,

            'hours_in_day' => $request->hours_in_day

        ]);

        return back()->with(
            'success',
            'Overtime Policy Updated Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Policy
    |--------------------------------------------------------------------------
    */

    public function deletePolicy($id)
    {
        OvertimeRule::where(
            'overtime_policy_id',
            $id
        )->delete();

        OvertimePolicy::findOrFail(
            $id
        )->delete();

        return back()->with(
            'success',
            'Overtime Policy Deleted Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Store Rule
    |--------------------------------------------------------------------------
    */

    public function storeRule(Request $request, $policyId)
    {
        $policy = OvertimePolicy::findOrFail(
            $policyId
        );

        OvertimeRule::create([

            'overtime_policy_id' => $policy->id,

            'business_id' => $policy->business_id,

            'auth_id' => Auth::id(),

            'attendance_type' => $request->attendance_type,

            'time_basis' => $request->time_basis,

            'from_hours' => $request->from_hours,

            'from_minutes' => $request->from_minutes,

            'to_hours' => $request->to_hours,

            'to_minutes' => $request->to_minutes,

            'calculation_method' => $request->calculation_method,

            'multiplier' => $request->multiplier,

            'overtime_min_type' => $request->overtime_min_type,

            'overtime_minutes' => $request->overtime_minutes

        ]);

        return back()->with(
            'success',
            'Overtime Rule Added Successfully'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Rule
    |--------------------------------------------------------------------------
    */

    public function deleteRule($id)
    {
        OvertimeRule::findOrFail(
            $id
        )->delete();

        return back()->with(
            'success',
            'Overtime Rule Deleted Successfully'
        );
    }
}