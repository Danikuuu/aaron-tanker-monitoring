<?php

namespace App\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TransactionHistoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type'      => 'sometimes|in:all,arrival,departure',
            'search'    => 'sometimes|string|max:100',
            'date_from' => 'sometimes|date|nullable',
            'date_to'   => 'sometimes|date|nullable|after_or_equal:date_from',
        ];
    }

    public function type(): string     { return $this->get('type', 'all'); }
    public function search(): string   { return $this->get('search', '') ?? '';  }
    public function dateFrom(): string { return $this->get('date_from', '') ?? ''; }
    public function dateTo(): string   { return $this->get('date_to', '') ?? ''; }
}