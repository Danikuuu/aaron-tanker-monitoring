<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrReceiptPaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'client_name'         => 'required|string|max:255',
            // 'total_amount'        => 'required|numeric|min:0',
            'down_payment'        => 'nullable|numeric|min:0',
            'down_payment_date'   => 'nullable|date',
            'final_payment'       => 'nullable|numeric|min:0',
            'final_payment_date'  => 'nullable|date',
            'due_date'            => 'nullable|date',
            'notes'               => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'client_name.required' => 'Client name is required.',
            'total_amount.required' => 'Total amount is required.',
        ];
    }
}