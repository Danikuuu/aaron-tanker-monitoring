<?php

namespace App\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreTankerArrivalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanker_number'   => 'required|string|max:10',
            'driver'          => 'required|string|max:10',
            'departure_date'  => 'required|date',

            'fuel_type'       => 'required|array',
            'fuel_type.*'     => 'nullable|string|in:diesel,premium,unleaded,methanol',

            'liters'          => 'required|array',
            'liters.*'        => 'nullable|numeric|min:1|max:10000',

            'methanol_liters' => 'nullable|array',
            'methanol_liters.*' => 'nullable|numeric|min:0|max:10000'
        ];
    }

    public function messages(): array
    {
        return [
            'fuel_type.1.required' => 'At least one fuel type is required.',
            'liters.1.required'    => 'Liters for first fuel slot is required.'
        ];
    }
}
