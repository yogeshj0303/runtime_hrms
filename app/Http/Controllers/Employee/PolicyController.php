<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;

class PolicyController extends Controller
{
    public function index(Request $request)
    {
        $employee = Employee::with('policyAssignment')->findOrFail($request->id);

        $shiftPolicies = \App\Models\ShiftPolicy::all();
        $weekoffPolicies = \App\Models\WeekOffPolicy::all();
        $overtimePolicies = \App\Models\OvertimePolicy::all();
        $leavePolicies = \App\Models\LeavePolicy::with('leaveType')->get();

        return view('admin.employee.profile.policies', compact(
            'employee', 'shiftPolicies', 'weekoffPolicies', 'overtimePolicies', 'leavePolicies'
        ));
    }

    public function store(Request $request)
    {
        $employee = Employee::findOrFail($request->id);

        $request->validate([
            'effective_from' => 'required|date_format:Y-m-d',
            'shift_policy_id' => 'required',
            'weekoff_policy_id' => 'required',
            'leave_policy_ids' => 'nullable|array',
        ]);

        $effectiveFrom = $request->effective_from;

        // Mark previous policies as old history
        $employee->policyAssignments()->update(['is_current' => false]);

        // Create new active policy
        $employee->policyAssignments()->create([
            'effective_from' => $effectiveFrom,
            'shift_policy_id' => $request->shift_policy_id,
            'auto_shift_selection' => $request->has('auto_shift_selection'),
            'weekoff_policy_id' => $request->weekoff_policy_id,
            'overtime_policy_id' => $request->overtime_policy_id,
            'leave_policy_ids' => $request->leave_policy_ids ?? [],
            'is_current' => true,
        ]);

        // Update ESS Permissions
        if ($employee->permission) {
            $employee->permission->update([
                'web_chat_punch' => $request->has('web_chat_punch'),
                'selfie_at_all_locations' => $request->has('selfie_at_all_locations'),
                'live_travel_attendance' => $request->has('live_travel_attendance'),
                'auto_punch_in_out' => $request->has('auto_punch_in_out'),
            ]);
        } else {
            $employee->permission()->create([
                'web_chat_punch' => $request->has('web_chat_punch'),
                'selfie_at_all_locations' => $request->has('selfie_at_all_locations'),
                'live_travel_attendance' => $request->has('live_travel_attendance'),
                'auto_punch_in_out' => $request->has('auto_punch_in_out'),
            ]);
        }

        return redirect()->back()->with('success', 'Policies assigned successfully');
    }

    public function destroy(Request $request, $policy_id)
    {
        $employee = Employee::findOrFail($request->id);
        $policy = $employee->policyAssignments()->findOrFail($policy_id);
        
        $policy->delete();

        if ($policy->is_current) {
            $latest = $employee->policyAssignments()->orderBy('effective_from', 'desc')->first();
            if ($latest) {
                $latest->update(['is_current' => true]);
            }
        }

        return redirect()->back()->with('success', 'Policy revision deleted successfully');
    }
}
