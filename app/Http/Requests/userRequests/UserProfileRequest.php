<?php

namespace App\Http\Requests\userRequests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class UserProfileRequest extends FormRequest
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
            'first_name' => 'sometimes|required|string|max:255|min:3',
            'last_name' => 'sometimes|required|string|max:255|min:3',
            'mobile' => 'sometimes|required|string|max:255|unique:profiles,mobile,' . auth()->id(),
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'city' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string|max:255',
        ];
    }
}
