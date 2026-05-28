<?php

namespace App\Strategies\ExceptionHandler;

use App\Contracts\ExceptionHandlerInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Override;

final class ValidationExceptionStrategy implements ExceptionHandlerInterface
{
    #[Override]
    public function render(mixed $exception): JsonResponse
    {
        throw_if(
            !$exception instanceof ValidationException,
            new InvalidArgumentException("Expected instance of ValidationException")
        );

        return response()->json([
            'success' => false,
            'error' => [
                'message' => "Validation Error",
                'fields' => $exception->errors(),
                'code' => 422,
            ]
        ], 422);
    }
}
