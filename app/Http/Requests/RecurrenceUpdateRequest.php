<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecurrenceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'sometimes|in:income,expense',
            'amount' => 'sometimes|numeric|min:0.01',
            'description' => 'sometimes|string|max:255',
            'frequency' => 'sometimes|in:daily,weekly,monthly',
            'starts_at' => 'sometimes|date',
            'active' => 'sometimes|boolean',
            'color' => 'sometimes|string|max:7',
        ];
    }
}
