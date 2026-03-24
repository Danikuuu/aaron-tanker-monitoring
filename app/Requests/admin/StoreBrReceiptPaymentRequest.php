<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrReceiptPaymentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'br_receipt_id'      => 'required|exists:br_receipts,id',
            'client_name'        => 'nullable|string|max:20',
            'total_amount'       => 'required|numeric|min:0|max:3000000',
            'down_payment'       => 'nullable|numeric|min:0|max:1000000',
            'down_payment_date'  => 'nullable|date',
            'final_payment'      => 'nullable|numeric|min:0|max:5000000',
            'final_payment_date' => 'nullable|date',
            'due_date'           => 'nullable|date',
            'notes'              => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'br_receipt_id.required'   => 'A receipt must be selected.',
            'br_receipt_id.exists'     => 'The selected receipt does not exist.',
            'total_amount.required'    => 'Total amount is required.',
            'total_amount.max'         => 'Total amount cannot exceed ₱3000000.',
            'total_amount.min'         => 'Total amount cannot be negative.',
            'down_payment.max'         => 'Down payment cannot exceed ₱1000000.',
            'down_payment.min'         => 'Down payment cannot be negative.',
            'final_payment.max'        => 'Final payment cannot exceed ₱5000000.',
            'final_payment.min'        => 'Final payment cannot be negative.',
            'down_payment_date.date'   => 'Down payment date must be a valid date.',
            'final_payment_date.date'  => 'Final payment date must be a valid date.',
            'due_date.date'            => 'Due date must be a valid date.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $total      = (float) $this->input('total_amount', 0);
            $down       = (float) $this->input('down_payment', 0);
            $final      = (float) $this->input('final_payment', 0);
            $totalPaid  = $down + $final;

            if ($down > $total) {
                $validator->errors()->add('down_payment', 'Down payment cannot exceed the total amount.');
            }

            if ($final > $total) {
                $validator->errors()->add('final_payment', 'Final payment cannot exceed the total amount.');
            }

            if ($totalPaid > $total) {
                $validator->errors()->add('final_payment', 'Combined down payment and final payment cannot exceed the total amount (₱' . number_format($total, 2) . ').');
            }
        });
    }
}