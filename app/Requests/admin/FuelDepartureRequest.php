<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class FuelDepartureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanker_number'                  => ['required', 'string', 'max:10'],
            'driver'                         => ['required', 'string', 'max:20'],
            'departure_date'                 => ['required', 'date'],
            'fuels'                          => ['required', 'array', 'min:1'],
            'fuels.*.id'                     => ['required', 'integer', 'exists:tanker_departure_fuels,id'],
            'fuels.*.liters'                 => ['required', 'numeric', 'min:0', 'max:10000'],
            'fuels.*.methanol_liters'        => ['nullable', 'numeric', 'min:0', 'max:10000'],
            'fuels.*.methanol_percent'       => ['nullable', 'numeric', 'min:15', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'tanker_number.required'             => 'Tanker number is required.',
            'driver.required'                    => 'Driver name is required.',
            'departure_date.required'            => 'Departure date is required.',
            'departure_date.date'                => 'Departure date must be a valid date.',
            'fuels.required'                     => 'At least one fuel entry is required.',
            'fuels.*.id.required'                => 'Fuel record ID is missing.',
            'fuels.*.id.exists'                  => 'One or more fuel records are invalid.',
            'fuels.*.liters.required'            => 'Liters is required for each fuel entry.',
            'fuels.*.liters.numeric'             => 'Liters must be a number.',
            'fuels.*.liters.min'                 => 'Liters cannot be negative.',
            'fuels.*.methanol_liters.numeric'    => 'Methanol liters must be a number.',
            'fuels.*.methanol_liters.min'        => 'Methanol liters cannot be negative.',
            'fuels.*.methanol_percent.numeric'   => 'Methanol percent must be a number.',
            'fuels.*.methanol_percent.min'       => 'Methanol percent cannot be negative.',
            'fuels.*.methanol_percent.max'       => 'Methanol percent cannot exceed 100.',
        ];
    }
}