<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionTypeEnum: string {
    case EXPENSE = 'expense';
    case INCOME = 'income';
    case TRANSFER = 'transfer';
    case INVESTMENT = 'investment'; 

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}