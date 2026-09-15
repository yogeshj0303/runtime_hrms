<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIncomeTaxSettingRequest extends FormRequest
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
            'enable_tds_deduction' => 'boolean',
            'declaration_window_open' => 'boolean',
            'financial_year_id' => 'nullable|exists:financial_years,id',
            'window_start_date' => 'nullable|date',
            'window_end_date' => 'nullable|date|after_or_equal:window_start_date'
        ];
    }
}
