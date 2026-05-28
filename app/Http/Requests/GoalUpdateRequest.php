<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GoalUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'target_amount' => 'sometimes|numeric|min:0.01',
            'current_amount' => 'sometimes|numeric|min:0',
            'target_date' => 'sometimes|date|after:today',
            'color' => 'sometimes|string|max:7',
        ];
    }
}
