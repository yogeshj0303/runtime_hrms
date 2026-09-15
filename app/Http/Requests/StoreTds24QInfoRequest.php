<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTds24QInfoRequest extends FormRequest
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
            // General Info
            'deductor_type' => 'nullable|string|max:255',
            'section_code' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'ministry' => 'nullable|string|max:255',
            'ministry_name' => 'nullable|string|max:255',
            'ain_number' => 'nullable|string|max:255',
            'pao_code' => 'nullable|string|max:255',
            'pao_registration_number' => 'nullable|string|max:255',
            'ddo_code' => 'nullable|string|max:255',
            'ddo_registration_number' => 'nullable|string|max:255',

            // Employer Details
            'employer_name' => 'required|string|max:255',
            'branch_division' => 'required|string|max:255',
            'emp_address_line_1' => 'nullable|string|max:255',
            'emp_address_line_2' => 'nullable|string|max:255',
            'emp_address_line_3' => 'nullable|string|max:255',
            'emp_address_line_4' => 'nullable|string|max:255',
            'emp_address_line_5' => 'nullable|string|max:255',
            'emp_state' => 'required|string|max:255',
            'emp_pin' => 'required|string|digits:6',
            'emp_pan' => ['required', 'string', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'emp_tan' => ['required', 'string', 'regex:/^[A-Z]{4}[0-9]{5}[A-Z]{1}$/'],
            'emp_email' => 'nullable|email|max:255',
            'emp_std_code' => 'nullable|string|max:10',
            'emp_phone' => 'nullable|string|max:15',
            'emp_alternate_email' => 'nullable|email|max:255',
            'emp_alternate_std_code' => 'nullable|string|max:10',
            'emp_alternate_phone' => 'nullable|string|max:15',
            'emp_gst_number' => ['nullable', 'string', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],

            // Responsible Person Details
            'resp_name' => 'required|string|max:255',
            'resp_designation' => 'required|string|max:255',
            'resp_address_line_1' => 'nullable|string|max:255',
            'resp_address_line_2' => 'nullable|string|max:255',
            'resp_address_line_3' => 'nullable|string|max:255',
            'resp_address_line_4' => 'nullable|string|max:255',
            'resp_address_line_5' => 'nullable|string|max:255',
            'resp_state' => 'required|string|max:255',
            'resp_pin' => 'required|string|digits:6',
            'resp_pan' => ['required', 'string', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'resp_mobile' => 'required|string|max:15',
            'resp_email' => 'required|email|max:255',
            'resp_std_code' => 'nullable|string|max:10',
            'resp_phone' => 'nullable|string|max:15',
            'resp_alternate_email' => 'nullable|email|max:255',
            'resp_alternate_std_code' => 'nullable|string|max:10',
            'resp_alternate_phone' => 'nullable|string|max:15',
        ];
    }
    
    public function messages()
    {
        return [
            'emp_pan.regex' => 'Employer PAN is invalid.',
            'emp_tan.regex' => 'Employer TAN is invalid.',
            'emp_gst_number.regex' => 'Employer GST Number is invalid.',
            'resp_pan.regex' => 'Responsible Person PAN is invalid.',
        ];
    }
}
