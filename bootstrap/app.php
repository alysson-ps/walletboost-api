<?php

use App\Console\Commands\PruneExpiredRememberTokens;
use App\Factories\ExceptionHandlerFactory;
use App\Http\Middleware\CheckRememberToken;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'remember' => CheckRememberToken::class,
        ]);
    })
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command(PruneExpiredRememberTokens::class)->daily();
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, Request $request) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            if ($request->is("api/*")) {
                return ExceptionHandlerFactory::make($exception);
            }
        });
    })->create();
