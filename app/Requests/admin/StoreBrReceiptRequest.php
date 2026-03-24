<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrReceiptRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    /**
     * Force downpayment to 0 if not provided, so it is never null or missing.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'downpayment' => $this->input('downpayment') ?? 0,
        ]);
    }

    public function rules(): array
    {
        return [
            'tanker_departure_id' => 'required|exists:tanker_departures,id',
            'receipt_no'          => 'required|string|max:50',
            'delivered_to'        => 'nullable|string|max:50',
            'address'             => 'nullable|string|max:50',
            'tin'                 => 'nullable|string|max:50',
            'terms'               => 'nullable|string|max:50',
            'grand_total'         => 'required|numeric|min:0|max:7000000',
            'fuels'               => 'required|array|min:1',
            'fuels.*.fuel_type'   => 'required|string',
            'fuels.*.liters'      => 'required|numeric|min:0|max:60000',
            'fuels.*.unit_price'  => 'required|numeric|min:0|max:300',
            'fuels.*.amount'      => 'required|numeric|min:0|max:3000000',
            'fuels.*.remarks'     => 'nullable|string|max:50',
            'downpayment'         => 'required|numeric|min:0|max:1000000',
        ];
    }

    public function messages(): array
    {
        return [
            'grand_total.max'        => 'Grand total cannot exceed ₱6,000,000.',
            'grand_total.min'        => 'Grand total cannot be negative.',
            'fuels.*.liters.max'     => 'Liters per fuel cannot exceed 60,000 L.',
            'fuels.*.unit_price.max' => 'Unit price cannot exceed ₱100.',
            'fuels.*.amount.max'     => 'Fuel amount cannot exceed ₱3,000,000.',
            'downpayment.max'        => 'Downpayment cannot exceed ₱1,000,000.',
            'downpayment.min'        => 'Downpayment cannot be negative.',
        ];
    }
}