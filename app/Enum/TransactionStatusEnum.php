<?php

declare(strict_types=1);

namespace App\Enum;

enum TransactionStatusEnum: string {
    case PAID = 'paid';
    case PENDING = 'pending';
    case PARTIAL = 'partial';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}