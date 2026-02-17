<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AnalyticsFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period' => 'sometimes|string|in:daily,weekly,monthly,yearly',
        ];
    }

    public function period(): string
    {
        return $this->get('period', 'monthly');
    }
}