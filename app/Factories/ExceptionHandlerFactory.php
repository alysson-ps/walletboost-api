<?php

declare(strict_types=1);

namespace App\Factories;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Strategies\ExceptionHandler\NotFounHttpExceptionStrategy;
use App\Strategies\ExceptionHandler\ValidationExceptionStrategy;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * A factory class for handling exceptions and returning appropriate JSON responses. 
 * 
 * @method static JsonResponse make(Throwable $exception) Handle the given exception and return a JSON response.
 */
final class ExceptionHandlerFactory
{
    /**
     * @var array A mapping of exception classes to their corresponding handling strategies.
     */
    private static array $exceptions = [
        // Add your custom exception mappings here
        NotFoundHttpException::class => NotFounHttpExceptionStrategy::class,
        ValidationException::class => ValidationExceptionStrategy::class,
    ];

    /**
     * Determine if the application is running in production environment.
     * @return bool True if the application is in production, false otherwise.
     */
    private static function isProduction(): bool
    {
        return config('app.env') === 'production';
    }

    /**
     * Render the exception using the appropriate strategy or return a generic error response.
     * 
     * @param Throwable $exception The exception to handle.
     * @return JsonResponse The JSON response to return to the client.
     */
    public static function make(Throwable $exception): JsonResponse
    {
        if (array_key_exists(get_class($exception), self::$exceptions)) {
            $strategy = self::$exceptions[get_class($exception)];

            if ($strategy) return app()
                ->make($strategy)
                ->render($exception);
        }

        return response()->json([
            'success' => false,
            'error' => [
                'message' => "Internal Server Error",
                'metadata' => !self::isProduction()
                    ? self::setMetadatas($exception)
                    : null,
                'code' => 500,
            ]
        ], 500);
    }

    /**
     * Set the metadata for the exception, including line number, file, and stack trace.
     * @param Throwable $exception The exception for which to set the metadata.
     * @return array An array containing the metadata for the exception.
     */
    private static function setMetadatas(Throwable $exception): array
    {
        return [
            'class' => get_class($exception),
            'reason' => $exception->getMessage(),
            'line' => $exception->getLine(),
            'file' => $exception->getFile(),
            'trace' => self::simplifyTrace($exception->getTrace()),
        ];
    }

    private static function simplifyTrace(array $trace): array
    {
        // Filter only files that are within the app directory
        $trace = array_filter($trace, function ($item) {
            return isset($item['file']) && str_contains($item['file'], app_path());
        });

        return array_map(function ($item) {
            return [
                'file' => $item['file'] ?? null,
                'line' => $item['line'] ?? null,
                'function' => $item['function'] ?? null,
                'class' => $item['class'] ?? null,
            ];
        }, $trace);
    }
}
