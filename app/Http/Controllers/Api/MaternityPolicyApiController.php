<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\MaternityLeavePolicy;
use App\Models\MaternityPolicyAuditLog;

class MaternityPolicyApiController extends Controller
{
    /**
     * Get all 4 maternity leave policies for a given business/user.
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'user_id'     => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $policies = MaternityLeavePolicy::where('business_id', $request->business_id)
            ->where('user_id', $request->user_id)
            ->get();

        return response()->json([
            'status'  => true,
            'message' => 'Maternity Leave Policies fetched successfully',
            'data'    => $policies
        ]);
    }

    /**
     * Batch update maternity leave policies via API.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required|exists:businesses,id',
            'user_id'     => 'required|exists:users,id',
            'policies'    => 'required|array',
            'policies.*.child_type'                 => 'required|in:first_child,second_child,third_child,subsequent_child',
            'policies.*.normal_leave_weeks'         => 'nullable|integer|min:0',
            'policies.*.adoption_leave_weeks'       => 'nullable|integer|min:0',
            'policies.*.surrogacy_leave_weeks'      => 'nullable|integer|min:0',
            'policies.*.tubectomy_leave_weeks'      => 'nullable|integer|min:0',
            'policies.*.miscarriage_leave_weeks'    => 'nullable|integer|min:0',
            'policies.*.miscarriage_over_and_above' => 'nullable|boolean',
            'policies.*.maternity_extension_days'   => 'nullable|integer|min:0',
            'policies.*.allow_extension'            => 'nullable|boolean',
            'policies.*.minimum_working_days'       => 'nullable|integer|min:0',
            'policies.*.leave_split'                => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $businessId = $request->business_id;
        $userId     = $request->user_id;

        foreach ($request->policies as $data) {
            $childType = $data['child_type'];
            
            $policy = MaternityLeavePolicy::where('business_id', $businessId)
                ->where('child_type', $childType)
                ->first();

            if ($policy) {
                // Determine new values (fallback to existing if not provided in payload)
                $newValues = [
                    'normal_leave_weeks'         => $data['normal_leave_weeks'] ?? $policy->normal_leave_weeks,
                    'adoption_leave_weeks'       => $data['adoption_leave_weeks'] ?? $policy->adoption_leave_weeks,
                    'surrogacy_leave_weeks'      => $data['surrogacy_leave_weeks'] ?? $policy->surrogacy_leave_weeks,
                    'tubectomy_leave_weeks'      => $data['tubectomy_leave_weeks'] ?? $policy->tubectomy_leave_weeks,
                    'miscarriage_leave_weeks'    => $data['miscarriage_leave_weeks'] ?? $policy->miscarriage_leave_weeks,
                    'miscarriage_over_and_above' => $data['miscarriage_over_and_above'] ?? $policy->miscarriage_over_and_above,
                    'maternity_extension_days'   => $data['maternity_extension_days'] ?? $policy->maternity_extension_days,
                    'allow_extension'            => $data['allow_extension'] ?? $policy->allow_extension,
                    'minimum_working_days'       => $data['minimum_working_days'] ?? $policy->minimum_working_days,
                    'leave_split'                => $data['leave_split'] ?? $policy->leave_split,
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

        return response()->json([
            'status'  => true,
            'message' => 'Maternity Leave Policies updated successfully'
        ]);
    }
}
