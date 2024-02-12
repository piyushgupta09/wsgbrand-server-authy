<?php

namespace Fpaipl\Authy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class RegisterUserRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $registerableRoles = collect(config('panel.registerable-roles'))->pluck('id')->implode(',');

        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,email',
            'password' => 'required|string|min:4|confirmed',
            'device' => 'required',
            'terms' => 'required|accepted',
            'type' => 'required|in:' . $registerableRoles
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'username.required' => 'Email is required',
            'password.required' => 'Password is required',
            'device.required' => 'Invalid Device Name',
            'terms.required' => 'You need to accept the Terms and Condition',
            'type.required' => 'User Type is required',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Name',
            'username' => 'Email',
            'password' => 'Password',
            'device' => 'Device Name',
            'terms' => 'Terms And Conditions',
            'type' => 'User Type',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $response = [
            'status' => 'error',
            'message' => 'Validation error',
            'errors' => $validator->errors(),
        ];

        throw new ValidationException($validator, response()->json($response, 422));
    }
}
