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
            'tanker_number'  => 'required|string|max:20',
            'driver'         => 'nullable|string|max:25',
            'departure_date' => 'required|date',

            'fuel_type'      => 'required|array',
            'fuel_type.*'    => 'nullable|string|in:diesel,premium,unleaded,methanol',

            'liters'         => 'required|array',
            'liters.*'       => 'nullable|numeric|min:1|max:60000',
        ];
    }

    public function messages(): array
    {
        return [
            'fuel_type.1.required' => 'At least one fuel type is required.',
            'liters.1.required'    => 'Liters for first fuel slot is required.',
            'liters.*.max'         => 'Liters per fuel slot cannot exceed 60,000 L.',
            'liters.*.min'         => 'Liters must be at least 1 L.',
        ];
    }
}