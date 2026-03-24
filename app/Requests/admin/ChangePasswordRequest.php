<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'password.required'  => 'A new password is required.',
            'password.min'       => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if (Hash::check($this->input('password'), Auth::user()->password)) {
                $validator->errors()->add(
                    'password',
                    'Your new password cannot be the same as your current password.'
                );
            }
        });
    }
}