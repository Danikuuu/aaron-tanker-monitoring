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
            'months' => 'sometimes|integer|in:3,6,12',
        ];
    }

    public function months(): int
    {
        return (int) $this->get('months', 6);
    }
}