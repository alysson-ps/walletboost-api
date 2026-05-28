<?php

declare(strict_types=1);

namespace App\Strategies\ExceptionHandler;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Contracts\ExceptionHandlerInterface;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;
use Override;

final class NotFounHttpExceptionStrategy implements ExceptionHandlerInterface
{
    #[Override]
    public function render(mixed $exception): JsonResponse
    {
        throw_if(
            !$exception instanceof NotFoundHttpException,
            new InvalidArgumentException("Expected instance of NotFoundHttpException")
        );

        return response()->json([
            'success' => false,
            'error' => [
                'message' => "Resource Not Found",
                'reason' => $exception->getMessage(),
                'code' => 404,
            ]
        ], 404);
    }
}
