<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ParticipantController;
use App\Http\Controllers\RecurrenceController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register']);

// Verificação de sessão e logout — aceitam Bearer token expirado + cookie remember_me
Route::get('/me', [AuthController::class, 'me'])->middleware('remember');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('remember');

Route::post('/external/transactions', [TransactionController::class, 'store'])
    ->middleware(['auth:sanctum', 'ability:api-key']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/token/verify', [AuthController::class, 'tokenVerify']);

    Route::group(['prefix' => 'accounts'], function () {
        Route::get('/', [AccountController::class, 'index']);
        Route::get('/create', [AccountController::class, 'create']);
        Route::post('/', [AccountController::class, 'store']);
        Route::put('/{id}', [AccountController::class, 'update']);
        Route::delete('/{id}', [AccountController::class, 'destroy']);
    });

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('/', [TransactionController::class, 'index']);
        Route::post('/', [TransactionController::class, 'store']);
        Route::get('/create', [TransactionController::class, 'create']);
        Route::get('/{id}/edit', [TransactionController::class, 'edit']);
        Route::get('recents', [TransactionController::class, 'recents']);
    });

    Route::group(['prefix' => 'participants'], function () {
        Route::get('/', [ParticipantController::class, 'index']);
        Route::post('/', [ParticipantController::class, 'store']);
        Route::delete('/{id}', [ParticipantController::class, 'destroy']);
        Route::get('/{id}/edit', [ParticipantController::class, 'edit']);
    });

    Route::group(['prefix' => 'categories'], function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
        Route::get('/create', [CategoryController::class, 'create']);
        Route::get('/{id}/edit', [CategoryController::class, 'edit']);
    });

    Route::group(['prefix' => 'recurrences'], function () {
        Route::get('/', [RecurrenceController::class, 'index']);
        Route::post('/', [RecurrenceController::class, 'store']);
        Route::put('/{id}', [RecurrenceController::class, 'update']);
        Route::delete('/{id}', [RecurrenceController::class, 'destroy']);
    });

    Route::group(['prefix' => 'goals'], function () {
        Route::get('/', [GoalController::class, 'index']);
        Route::post('/', [GoalController::class, 'store']);
        Route::put('/{id}', [GoalController::class, 'update']);
        Route::delete('/{id}', [GoalController::class, 'destroy']);
    });

    // Route::post('/logout', [AuthController::class, 'logout']);

    Route::group(['prefix' => 'api-keys'], function () {
        Route::get('/', [ApiKeyController::class, 'index']);
        Route::post('/', [ApiKeyController::class, 'store']);
        Route::delete('/{id}', [ApiKeyController::class, 'destroy']);
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('/summary', [DashboardController::class, 'summary']);
        Route::get('/goals', [DashboardController::class, 'goals']);
        Route::get('/expense-and-income-charts', [DashboardController::class, 'expenseAndIncomeCharts']);
        Route::get('/expense-categories', [DashboardController::class, 'expenseCategories']);
    });
});
