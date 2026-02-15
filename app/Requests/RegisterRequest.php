<?php

namespace App\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'          => 'required|string|max:255',
            'last_name'           => 'required|string|max:255',
            'email'               => 'required|email|unique:users,email',
            'password'            => 'required|string|min:6|confirmed',
            'g-recaptcha-response' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required'              => 'First name is required.',
            'first_name.string'                => 'First name must be a valid string.',
            'first_name.max'                   => 'First name may not exceed 255 characters.',
            'last_name.required'               => 'Last name is required.',
            'last_name.string'                 => 'Last name must be a valid string.',
            'last_name.max'                    => 'Last name may not exceed 255 characters.',
            'email.required'                   => 'Email is required.',
            'email.email'                      => 'Enter a valid email address.',
            'email.unique'                     => 'This email address is already registered.',
            'password.required'                => 'Password is required.',
            'password.min'                     => 'Password must be at least 8 characters.',
            'password.confirmed'               => 'Passwords do not match.',
            'g-recaptcha-response.required'    => 'Please verify that you are not a robot.',
        ];
    }
}
