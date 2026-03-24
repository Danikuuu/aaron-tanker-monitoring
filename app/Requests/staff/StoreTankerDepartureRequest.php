<?php

namespace App\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StoreTankerDepartureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tanker_number'  => 'required|string|max:20',
            'driver'         => 'required|string|max:25',
            'departure_date' => 'required|date',

            'fuel_type'         => 'required|array',
            'fuel_type.*'       => 'nullable|string|in:diesel,premium,unleaded,methanol',

            'liters'            => 'required|array',
            'liters.*'          => 'nullable|numeric|min:1|max:60000',

            'methanol_liters'   => 'nullable|array',
            'methanol_liters.*' => 'nullable|numeric|min:0|max:60000',
        ];
    }

    public function messages(): array
    {
        return [
            'liters.*.max'          => 'Liters per fuel slot cannot exceed 60,000 L.',
            'liters.*.min'          => 'Liters must be at least 1 L.',
            'methanol_liters.*.max' => 'Methanol liters cannot exceed 60,000 L.',
            'methanol_liters.*.min' => 'Methanol liters cannot be negative.',
        ];
    }
}