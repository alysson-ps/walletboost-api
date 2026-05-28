<?php

namespace App\Http\Requests;

use App\Enum\AccountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'type' => ['sometimes', Rule::enum(AccountTypeEnum::class)],
            'color' => 'sometimes|string|max:7',
        ];
    }
}
