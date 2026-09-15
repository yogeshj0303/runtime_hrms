<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\LeavePolicy;
use App\Models\LeaveType;

class LeavePolicyController extends Controller
{
    public function index()
    {
        $businessId = Auth::user()->active_business_id;

        $leavePolicies = LeavePolicy::with('leaveType')
            ->where('business_id', $businessId)
            ->latest()
            ->get();
            
        $leaveTypes = LeaveType::where('business_id', $businessId)
            ->where('status', 'active')
            ->get();
        
        return view('admin.setup.attandance.leave-policies.index', compact('leavePolicies', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $businessId = Auth::user()->active_business_id;

        $request->validate([
            'leave_type_id' => [
                'required',
                'exists:leave_types,id',
                Rule::unique('leave_policies')->where(function ($query) use ($businessId) {
                    return $query->where('business_id', $businessId);
                })
            ],
            'policy_description' => 'nullable|string|max:255',
            'minimum_presence_days' => 'nullable|numeric|min:0',
        ]);

        LeavePolicy::create([
            'user_id' => Auth::id(),
            'business_id' => $businessId,
            'leave_type_id' => $request->leave_type_id,
            'policy_description' => $request->policy_description,
            'grant_leaves' => $request->has('grant_leaves'),
            'minimum_presence_days' => $request->minimum_presence_days ?? 0,
            'jan_grant' => $request->jan_grant ?? 0,
            'feb_grant' => $request->feb_grant ?? 0,
            'mar_grant' => $request->mar_grant ?? 0,
            'apr_grant' => $request->apr_grant ?? 0,
            'may_grant' => $request->may_grant ?? 0,
            'jun_grant' => $request->jun_grant ?? 0,
            'jul_grant' => $request->jul_grant ?? 0,
            'aug_grant' => $request->aug_grant ?? 0,
            'sep_grant' => $request->sep_grant ?? 0,
            'oct_grant' => $request->oct_grant ?? 0,
            'nov_grant' => $request->nov_grant ?? 0,
            'dec_grant' => $request->dec_grant ?? 0,
            'reset_negative_balance' => $request->has('reset_negative_balance'),
            'lapse_leaves' => $request->has('lapse_leaves'),
            'jan_lapse' => $request->jan_lapse ?? 0,
            'feb_lapse' => $request->feb_lapse ?? 0,
            'mar_lapse' => $request->mar_lapse ?? 0,
            'apr_lapse' => $request->apr_lapse ?? 0,
            'may_lapse' => $request->may_lapse ?? 0,
            'jun_lapse' => $request->jun_lapse ?? 0,
            'jul_lapse' => $request->jul_lapse ?? 0,
            'aug_lapse' => $request->aug_lapse ?? 0,
            'sep_lapse' => $request->sep_lapse ?? 0,
            'oct_lapse' => $request->oct_lapse ?? 0,
            'nov_lapse' => $request->nov_lapse ?? 0,
            'dec_lapse' => $request->dec_lapse ?? 0,
            'during_probation' => $request->has('during_probation'),
            'after_probation' => $request->has('after_probation'),
            'auto_apply' => $request->has('auto_apply'),
        ]);

        return redirect()->route('leave-policies.index')
            ->with('success', 'Leave Policy Created Successfully.');
    }

    public function edit($id)
    {
        $leavePolicy = LeavePolicy::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        return response()->json($leavePolicy);
    }

    public function update(Request $request, $id)
    {
        $businessId = Auth::user()->active_business_id;

        $leavePolicy = LeavePolicy::where('business_id', $businessId)
            ->findOrFail($id);

        $request->validate([
            'leave_type_id' => [
                'required',
                'exists:leave_types,id',
                Rule::unique('leave_policies')->where(function ($query) use ($businessId) {
                    return $query->where('business_id', $businessId);
                })->ignore($id)
            ],
            'policy_description' => 'nullable|string|max:255',
            'minimum_presence_days' => 'nullable|numeric|min:0',
        ]);

        $leavePolicy->update([
            'leave_type_id' => $request->leave_type_id,
            'policy_description' => $request->policy_description,
            'grant_leaves' => $request->has('grant_leaves'),
            'minimum_presence_days' => $request->minimum_presence_days ?? 0,
            'jan_grant' => $request->jan_grant ?? 0,
            'feb_grant' => $request->feb_grant ?? 0,
            'mar_grant' => $request->mar_grant ?? 0,
            'apr_grant' => $request->apr_grant ?? 0,
            'may_grant' => $request->may_grant ?? 0,
            'jun_grant' => $request->jun_grant ?? 0,
            'jul_grant' => $request->jul_grant ?? 0,
            'aug_grant' => $request->aug_grant ?? 0,
            'sep_grant' => $request->sep_grant ?? 0,
            'oct_grant' => $request->oct_grant ?? 0,
            'nov_grant' => $request->nov_grant ?? 0,
            'dec_grant' => $request->dec_grant ?? 0,
            'reset_negative_balance' => $request->has('reset_negative_balance'),
            'lapse_leaves' => $request->has('lapse_leaves'),
            'jan_lapse' => $request->jan_lapse ?? 0,
            'feb_lapse' => $request->feb_lapse ?? 0,
            'mar_lapse' => $request->mar_lapse ?? 0,
            'apr_lapse' => $request->apr_lapse ?? 0,
            'may_lapse' => $request->may_lapse ?? 0,
            'jun_lapse' => $request->jun_lapse ?? 0,
            'jul_lapse' => $request->jul_lapse ?? 0,
            'aug_lapse' => $request->aug_lapse ?? 0,
            'sep_lapse' => $request->sep_lapse ?? 0,
            'oct_lapse' => $request->oct_lapse ?? 0,
            'nov_lapse' => $request->nov_lapse ?? 0,
            'dec_lapse' => $request->dec_lapse ?? 0,
            'during_probation' => $request->has('during_probation'),
            'after_probation' => $request->has('after_probation'),
            'auto_apply' => $request->has('auto_apply'),
        ]);

        return redirect()->route('leave-policies.index')
            ->with('success', 'Leave Policy Updated Successfully.');
    }

    public function destroy($id)
    {
        $leavePolicy = LeavePolicy::where('business_id', Auth::user()->active_business_id)
            ->findOrFail($id);

        $leavePolicy->delete();

        return redirect()->route('leave-policies.index')
            ->with('success', 'Leave Policy Deleted Successfully.');
    }

    public function recalculate(Request $request)
    {
        $request->validate([
            'recalculate_period' => 'required|date_format:Y-m',
        ]);

        // Logic to recalculate policies for the specific business
        // Ensure to scope by Auth::user()->active_business_id

        return redirect()->route('leave-policies.index')
            ->with('success', 'Leave Policies recalculation initiated for ' . $request->recalculate_period);
    }
}
