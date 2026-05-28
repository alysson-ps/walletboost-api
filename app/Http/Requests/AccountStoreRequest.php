<?php

namespace App\Http\Requests;

use App\Enum\AccountTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AccountStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'type' => ['required', Rule::enum(AccountTypeEnum::class)],
            'color' => ['required','string','max:7'],
            'balance' => ['required', 'numeric', 'min:0']
        ];
    }
}
