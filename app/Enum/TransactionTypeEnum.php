<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionTypeEnum: string {
    case EXPENSE = 'expense';
    case INCOME = 'income';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}