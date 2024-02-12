<?php

namespace Fpaipl\Authy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class SendLoginOtpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => 'required|email|exists:users,email',
            'device' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Email is required',
            'username.exists' => 'User with given email does not exist',
            'device.required' => 'Device is required',
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
