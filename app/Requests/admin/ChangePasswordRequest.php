<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // already guarded by auth middleware on the route
    }

    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',          // requires password_confirmation field
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required'  => 'Please enter a new password.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }
}