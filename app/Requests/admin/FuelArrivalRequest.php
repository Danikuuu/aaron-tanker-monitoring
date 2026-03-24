<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FuelArrivalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanker_number'          => ['required', 'string', 'max:10'],
            'arrival_date'           => ['required', 'date'],
            'fuels'                  => ['required', 'array', 'min:1'],
            'fuels.*.id'             => ['required', 'integer', 'exists:tanker_arrival_fuels,id'],
            'fuels.*.liters'         => ['required', 'numeric', 'min:0', 'max:10000'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanker_number.required'   => 'Tanker number is required.',
            'arrival_date.required'    => 'Arrival date is required.',
            'arrival_date.date'        => 'Arrival date must be a valid date.',
            'fuels.required'           => 'At least one fuel entry is required.',
            'fuels.*.id.required'      => 'Fuel record ID is missing.',
            'fuels.*.id.exists'        => 'One or more fuel records are invalid.',
            'fuels.*.liters.required'  => 'Liters is required for each fuel entry.',
            'fuels.*.liters.numeric'   => 'Liters must be a number.',
            'fuels.*.liters.min'       => 'Liters cannot be negative.',
        ];
    }
}