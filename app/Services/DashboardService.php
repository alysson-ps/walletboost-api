<?php

declare(strict_types=1);

namespace App\Services;

use App\Enum\TransactionTypeEnum;
use App\Models\User;
use Carbon\Carbon;

final class DashboardService
{
    /**
     * Get the summary data for the dashboard cards.
     * @return array The data for the dashboard cards, including balance, expenses, income, and investments.
     */
    public function getSummary(User $user): array
    {
        $startOfMonth = Carbon::parse("2026-04-15")->startOfMonth();

        $cards = [
            'balance' => [
                'title' => 'Balance',
                'percent' => 0,
                'value' => $user->transactions()
                    ->whereIn('type', [TransactionTypeEnum::EXPENSE, TransactionTypeEnum::INCOME])
                    ->where('occurred_at', '>=', $startOfMonth)
                    ->sum('amount'),
            ],
            'expenses' => [
                'title' => 'Expenses',
                'percent' => 0,
                'graph' => [
                    'data' => $user->transactions()->where('type', TransactionTypeEnum::EXPENSE)
                        ->where('occurred_at', '>=', $startOfMonth)
                        ->groupBy('occurred_at')
                        ->selectRaw('DATE(occurred_at) as date, SUM(amount) as amount')
                        ->get()
                ],
                'value' => $user->transactions()
                    ->where('type', TransactionTypeEnum::EXPENSE)
                    ->where('occurred_at', '>=', $startOfMonth)
                    ->sum('amount'),
            ],
            'income' => [
                'title' => 'Income',
                'percent' => 0,
                'graph' => [
                    'data' => $user->transactions()->where('type', TransactionTypeEnum::INCOME)
                        ->where('occurred_at', '>=', $startOfMonth)
                        ->groupBy('occurred_at')
                        ->selectRaw('DATE(occurred_at) as date, SUM(amount) as amount')
                        ->get()
                ],
                'value' => $user->transactions()
                    ->where('type', TransactionTypeEnum::INCOME)
                    ->where('occurred_at', '>=', $startOfMonth)
                    ->sum('amount'),
            ],
            'investments' => [
                'title' => 'Investments',
                'percent' => 0,
                'graph' => [
                    'data' => $user->transactions()->accountInvestment()
                        ->whereIn('type', [TransactionTypeEnum::EXPENSE, TransactionTypeEnum::INCOME])
                        ->where('occurred_at', '>=', $startOfMonth)
                        ->groupBy('occurred_at')
                        ->selectRaw('DATE(occurred_at) as date, SUM(amount) as amount')
                        ->get()
                ],
                'value' => $user->transactions()->accountInvestment()
                    ->whereIn('type', [TransactionTypeEnum::EXPENSE, TransactionTypeEnum::INCOME])
                    ->where('occurred_at', '>=', $startOfMonth)
                    ->sum('amount'),
            ],
        ];

        return $cards;
    }

    /**
     * Get the data for the expense and income charts.
     * @param User $user The user for whom to get the data.
     * @param string $range The range for the charts (e.g., 'month', 'year').
     * @return array The data for the expense and income charts, including the month 
     */
    public function getExpenseAndIncomeCharts(User $user, string $range = 'month'): array
    {
        $data = [];
        $labels = match ($range) {
            'month' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        };

        $transactions = $user->transactions()
            ->whereBetween('occurred_at', [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()])
            ->whereIn('type', [TransactionTypeEnum::EXPENSE, TransactionTypeEnum::INCOME])
            ->orderBy('occurred_at')
            ->get();

        foreach ($labels as $index => $item) {
            $monthTransactions = $transactions->filter(function ($transaction) use ($index) {
                return Carbon::parse($transaction->occurred_at)->month === $index + 1;
            });

            $data['expenses'][] = [
                'value' => round($monthTransactions->where('type', TransactionTypeEnum::EXPENSE)->sum('amount'), 2),
                'label' => $item,
            ];

            $data['incomes'][] = [
                'value' => round($monthTransactions->where('type', TransactionTypeEnum::INCOME)->sum('amount'), 2),
                'label' => $item,
            ];
        }

        return $data;
    }

    public function getExpenseCategories(User $user)
    {
        return $user->transactions()->where('type', TransactionTypeEnum::EXPENSE)->with('category')
            ->groupBy('category_id')
            ->selectRaw('category_id, SUM(amount) as amount')
            ->get();
    }
}
