<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxSlabRequest extends FormRequest
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
            'financial_year_id' => 'required|exists:financial_years,id',
            'scheme' => 'required|string|in:Old Scheme,New Scheme',
            'age_category' => 'required|string|in:Below 60,60-80,Above 80',
            'income_from' => 'required|numeric|min:0',
            'income_to' => 'nullable|numeric|gt:income_from',
            'fixed_tax' => 'nullable|numeric|min:0',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'cess' => 'nullable|numeric|min:0|max:100',
            'surcharge' => 'nullable|numeric|min:0|max:100',
            'rebate' => 'nullable|numeric|min:0',
            'sort_order' => 'nullable|integer|min:0'
        ];
    }
}
