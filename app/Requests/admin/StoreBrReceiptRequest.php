<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreBrReceiptRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'tanker_departure_id' => 'required|exists:tanker_departures,id',
            'receipt_no'          => 'required|string|max:50',
            'delivered_to'        => 'nullable|string|max:255',
            'address'             => 'nullable|string|max:255',
            'tin'                 => 'nullable|string|max:50',
            'terms'               => 'nullable|string|max:100',
            'grand_total'         => 'required|numeric|min:0',
            'fuels'               => 'required|array|min:1',
            'fuels.*.fuel_type'   => 'required|string',
            'fuels.*.liters'      => 'required|numeric|min:0',
            'fuels.*.unit_price'  => 'required|numeric|min:0',
            'fuels.*.amount'      => 'required|numeric|min:0',
            'fuels.*.remarks'     => 'nullable|string|max:255',
        ];
    }
}