<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChairtyProfileRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'required|string',
            'picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'chairty_type' => 'required|string|max:255',
            'financial_license' => 'required|string|max:255',
            'ad_number' => 'required|string|max:255',
        ];
    }
}
