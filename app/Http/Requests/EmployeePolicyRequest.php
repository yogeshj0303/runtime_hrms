<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeePolicyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'effective_from' => 'required|date',
            'shift_policy_id' => 'required|exists:shift_policies,id',
            'week_off_policy_id' => 'required|exists:week_off_policies,id',
            'overtime_policy_id' => 'nullable|exists:overtime_policies,id',
            'auto_shift_selection' => 'nullable|boolean',
            'leave_policy_ids' => 'nullable|array',
            'leave_policy_ids.*' => 'exists:leave_policies,id',
            'time_rule_ids' => 'nullable|array',
            'time_rule_ids.*' => 'exists:time_rules,id',
        ];
    }
}
