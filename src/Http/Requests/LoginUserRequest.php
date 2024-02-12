<?php

namespace Fpaipl\Authy\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class LoginUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'username' => [
                'required',
                function ($attribute, $value, $fail) {
                    // Check if the username exists as an email or mobile
                    $exists = \App\Models\User::where('email', $value)->orWhere('mobile', $value)->exists();
                    if (!$exists) {
                        return $fail(__('validation.exists', ['attribute' => $attribute]));
                    }
                },
            ],
            'device' => 'required|string',
        ];

        // Add conditional rules for password and otpcode
        if ($this->has('password') && !empty($this->password)) {
            $rules['password'] = 'required|string|min:4';
        } elseif ($this->has('otpcode') && !empty($this->otpcode)) {
            $rules['otpcode'] = 'required|string|size:4';
        } else {
            // Require at least one of password or otpcode if none are provided initially
            $rules['password'] = 'required_without:otpcode|string|min:4';
            $rules['otpcode'] = 'required_without:password|string|size:4';
        }

        return $rules;
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
