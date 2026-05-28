<?php

namespace App\Observers;

use App\Enum\TransactionStatusEnum;
use App\Enum\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Transaction;

use function Illuminate\Support\now;

class AccountObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(Account $account): void
    {
        Transaction::create([
            'user_id' => $account->user_id,
            'amount' => $account->balance,
            'category_id' => 9,
            'account_id' => $account->id,
            'type' => TransactionTypeEnum::INCOME,
            'description' => "Saldo inicial da conta {$account->name}",
            'status' => TransactionStatusEnum::PAID,
            'occurred_at' => now()
        ]);
    }
}
