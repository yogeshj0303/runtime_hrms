<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreForm16InfoRequest extends FormRequest
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
            'full_name' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'signature_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            
            'employer_name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'required|string|max:255',
            'address_line_3' => 'nullable|string|max:255',
            'place_of_issue' => 'required|string|max:255',
            
            'cit_name' => 'required|string|max:255',
            'cit_address_line_1' => 'required|string|max:255',
            'cit_address_line_2' => 'required|string|max:255',
            'cit_address_line_3' => 'nullable|string|max:255',
        ];
    }
}
