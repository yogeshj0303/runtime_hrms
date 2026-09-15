<?php

namespace App\Http\Controllers\Setup\Attendance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MaternityLeavePolicy;
use App\Models\MaternityPolicyAuditLog;

class MaternityLeavePolicyController extends Controller
{
    /**
     * Display all maternity leave policies for the active business.
     */
    public function index()
    {
        $businessId = Auth::user()->active_business_id;
        $userId = Auth::id();

        // Ensure default 4 rows exist
        $this->ensureDefaultPoliciesExist($businessId, $userId);

        $policies = MaternityLeavePolicy::where('business_id', $businessId)
            ->get()
            ->keyBy('child_type'); // Key by child_type for easy blade access

        return view('admin.setup.attandance.maternity-leave-policy.index', compact('policies'));
    }

    /**
     * Batch update all maternity policies from the form.
     */
    public function update(Request $request)
    {
        $businessId = Auth::user()->active_business_id;
        $userId = Auth::id();

        $request->validate([
            'policies' => 'required|array',
            'policies.*.normal_leave_weeks' => 'required|integer|min:0',
            'policies.*.adoption_leave_weeks' => 'required|integer|min:0',
            'policies.*.surrogacy_leave_weeks' => 'required|integer|min:0',
            'policies.*.tubectomy_leave_weeks' => 'required|integer|min:0',
            'policies.*.miscarriage_leave_weeks' => 'required|integer|min:0',
            'policies.*.maternity_extension_days' => 'required|integer|min:0',
            'policies.*.minimum_working_days' => 'required|integer|min:0',
        ]);

        $inputPolicies = $request->input('policies');

        foreach ($inputPolicies as $childType => $data) {
            $policy = MaternityLeavePolicy::where('business_id', $businessId)
                ->where('child_type', $childType)
                ->first();

            if ($policy) {
                // Determine new values, casting checkboxes properly
                $newValues = [
                    'normal_leave_weeks'         => (int) $data['normal_leave_weeks'],
                    'adoption_leave_weeks'       => (int) $data['adoption_leave_weeks'],
                    'surrogacy_leave_weeks'      => (int) $data['surrogacy_leave_weeks'],
                    'tubectomy_leave_weeks'      => (int) $data['tubectomy_leave_weeks'],
                    'miscarriage_leave_weeks'    => (int) $data['miscarriage_leave_weeks'],
                    'miscarriage_over_and_above' => isset($data['miscarriage_over_and_above']),
                    'maternity_extension_days'   => (int) $data['maternity_extension_days'],
                    'allow_extension'            => isset($data['allow_extension']),
                    'minimum_working_days'       => (int) $data['minimum_working_days'],
                    'leave_split'                => isset($data['leave_split']),
                    'updated_by'                 => $userId,
                ];

                // Write audit log for any changed fields
                foreach ($newValues as $field => $newValue) {
                    if ($field !== 'updated_by' && $policy->$field !== $newValue) {
                        MaternityPolicyAuditLog::create([
                            'business_id' => $businessId,
                            'user_id'     => $policy->user_id,
                            'child_type'  => $childType,
                            'field_name'  => $field,
                            'old_value'   => $policy->$field,
                            'new_value'   => $newValue,
                            'updated_by'  => $userId,
                        ]);
                    }
                }

                $policy->update($newValues);
            }
        }

        return redirect()->route('maternity-leave-policy.index')
            ->with('success', 'Maternity Leave Policies Updated Successfully.');
    }

    /**
     * Helper to auto-seed the 4 child types if they don't exist yet.
     */
    private function ensureDefaultPoliciesExist($businessId, $userId)
    {
        $childTypes = array_keys(MaternityLeavePolicy::CHILD_TYPES);

        foreach ($childTypes as $type) {
            MaternityLeavePolicy::firstOrCreate(
                ['business_id' => $businessId, 'child_type' => $type],
                [
                    'user_id'    => $userId,
                    'created_by' => $userId,
                ]
            );
        }
    }
}
