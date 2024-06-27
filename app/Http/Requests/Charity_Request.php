<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Charity_Request extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'job' => 'required|string',
            'charity_name' => 'required|string',
            'charity_address' => 'required|string',
            'charity_type' => 'required|string|in:medical,educational,social,environmental,humanitarian,cultural,sports,economic,other',
            'financial_license' => 'required|string',
            'financial_license_image' => 'required|string',
            'ad_number' => 'required|string',
        ];
    }
}
