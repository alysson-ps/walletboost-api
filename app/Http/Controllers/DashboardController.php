<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Resources\Dashboard\SummaryResource;
use App\Http\Resources\Goal\GoalCollection;
use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private DashboardService $service
    ) {}

    public function summary(Request $request)
    {
        $user = $request->user();

        $data = $this->service->getSummary($user);

        return response()->json([
            'success' => true,
            'data' => SummaryResource::make($data)
        ]);
    }

    public function expenseAndIncomeCharts(Request $request)
    {
        $user = $request->user();

        $data = $this->service->getExpenseAndIncomeCharts($user);

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    public function expenseCategories(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => $this->service->getExpenseCategories($user)
        ]);
    }

    public function accounts()
    {
        // 
    }

    public function recentTransactions()
    {
        // 
    }
}
