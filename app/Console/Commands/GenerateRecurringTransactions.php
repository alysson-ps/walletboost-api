<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Recurrence;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateRecurringTransactions extends Command
{
    protected $signature = 'recurrences:generate';
    protected $description = 'Generate pending transactions for all active recurrences';

    public function handle(): int
    {
        $recurrences = Recurrence::query()
            ->where('active', true)
            ->whereNotNull('account_id')
            ->whereNotNull('category_id')
            ->get();

        $generated = 0;

        foreach ($recurrences as $recurrence) {
            $generated += $this->generateFor($recurrence);
        }

        $this->info("Generated {$generated} transaction(s).");

        return self::SUCCESS;
    }

    private function generateFor(Recurrence $recurrence): int
    {
        $lastTransaction = Transaction::where('recurrence_id', $recurrence->id)
            ->orderBy('occurred_at', 'desc')
            ->first();

        // Primeira geração parte do starts_at; gerações seguintes avançam a partir do último lançamento
        $nextDate = $lastTransaction
            ? $this->advance(Carbon::parse($lastTransaction->occurred_at), $recurrence->frequency)
            : Carbon::parse($recurrence->starts_at);

        $today = Carbon::today();
        $count = 0;

        DB::beginTransaction();

        while ($nextDate->lte($today)) {
            Transaction::create([
                'user_id' => $recurrence->user_id,
                'account_id' => $recurrence->account_id,
                'category_id' => $recurrence->category_id,
                'recurrence_id' => $recurrence->id,
                'type' => $recurrence->type,
                'amount' => $recurrence->amount,
                'description' => $recurrence->description,
                'occurred_at' => $nextDate->toDateString(),
                'status' => 'pending',
            ]);

            $nextDate = $this->advance($nextDate, $recurrence->frequency);
            $count++;
        }

        DB::commit();

        return $count;
    }

    private function advance(Carbon $date, string $frequency): Carbon
    {
        return match ($frequency) {
            'daily'   => $date->copy()->addDay(),
            'weekly'  => $date->copy()->addWeek(),
            'monthly' => $date->copy()->addMonthNoOverflow(),
        };
    }
}
