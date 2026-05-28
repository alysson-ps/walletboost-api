<?php

declare(strict_types=1);

namespace App\Enum;

enum AccountTypeEnum: string
{
    case CHECKING = 'checking';
    case SAVINGS = 'savings';
    case DIGITAL = 'digital';
    case CREDIT_CARD = 'credit_card';
    case INVESTMENT = 'investment';

    public function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
