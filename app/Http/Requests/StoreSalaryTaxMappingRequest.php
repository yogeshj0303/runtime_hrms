<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalaryTaxMappingRequest extends FormRequest
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
            'salary_component_id' => 'required|exists:salary_components,id',
            'basic' => 'boolean',
            'hra' => 'boolean',
            'profit' => 'boolean',
            'perk' => 'boolean',
            'entire_taxable' => 'boolean',
            'exempt' => 'boolean',
            'new_exempt' => 'boolean'
        ];
    }
}
