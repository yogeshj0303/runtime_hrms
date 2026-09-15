<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\EmployeePolicy;
use App\Models\ShiftPolicy;
use App\Models\WeekOffPolicy;
use App\Models\OvertimePolicy;
use App\Models\LeavePolicy;
use App\Models\TimeRule;
use App\Http\Requests\EmployeePolicyRequest;

class EmployeePolicyController extends Controller
{
    public function edit($id)
    {
        $employee = Employee::with('policy.leavePolicies', 'policy.timeRules', 'policies')->findOrFail($id);
        
        $businessId = $employee->business_id;

        // Fetching policies only for the current business
        $shiftPolicies = ShiftPolicy::where('business_id', $businessId)->get(); // Assuming status is handled or doesn't exist uniformly, add where('status', 'active') if column exists.
        $weekOffPolicies = WeekOffPolicy::where('business_id', $businessId)->get();
        $overtimePolicies = OvertimePolicy::where('business_id', $businessId)->get();
        $leavePolicies = LeavePolicy::with('leaveType')->where('business_id', $businessId)->get();
        $timeRules = TimeRule::where('business_id', $businessId)->get();

        return view('admin.employee.profile.policies', compact(
            'employee',
            'shiftPolicies',
            'weekOffPolicies',
            'overtimePolicies',
            'leavePolicies',
            'timeRules'
        ));
    }

    public function update(EmployeePolicyRequest $request, $id)
    {
        $employee = Employee::findOrFail($id);
        
        $data = $request->validated();
        
        // Ensure auto_shift_selection is handled as boolean
        $data['auto_shift_selection'] = $request->has('auto_shift_selection');
        $data['business_id'] = $employee->business_id;

        // Mark old policies as not current
        EmployeePolicy::where('employee_id', $employee->id)->update(['is_current' => false]);

        // Create new active policy
        $policy = EmployeePolicy::create([
            'employee_id' => $employee->id,
            'business_id' => $employee->business_id,
            'effective_from' => $data['effective_from'],
            'shift_policy_id' => $data['shift_policy_id'],
            'week_off_policy_id' => $data['week_off_policy_id'],
            'overtime_policy_id' => $data['overtime_policy_id'] ?? null,
            'auto_shift_selection' => $data['auto_shift_selection'],
            'updated_by' => auth()->id() ?? 1,
            'is_current' => true,
        ]);

        // Sync many-to-many relationships
        if (isset($data['leave_policy_ids'])) {
            $policy->leavePolicies()->sync($data['leave_policy_ids']);
        } else {
            $policy->leavePolicies()->sync([]);
        }

        if (isset($data['time_rule_ids'])) {
            $policy->timeRules()->sync($data['time_rule_ids']);
        } else {
            $policy->timeRules()->sync([]);
        }

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

        return redirect()->back()->with('success', 'Employee policies updated successfully.');
    }

    public function destroy($id, $policy_id)
    {
        $employee = Employee::findOrFail($id);
        $policy = EmployeePolicy::where('employee_id', $employee->id)->findOrFail($policy_id);
        
        $policy->delete();

        if ($policy->is_current) {
            $latest = EmployeePolicy::where('employee_id', $employee->id)->orderBy('effective_from', 'desc')->first();
            if ($latest) {
                $latest->update(['is_current' => true]);
            }
        }

        return redirect()->back()->with('success', 'Policy revision deleted successfully');
    }
}
