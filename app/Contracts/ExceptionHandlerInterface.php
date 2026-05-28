<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

interface ExceptionHandlerInterface
{
    /**
     * Render the exception into an HTTP response.
     * @param mixed $exception The exception to render.
     * @throws InvalidArgumentException If the provided exception is not of the expected type.
     * @return JsonResponse The JSON response containing the error details.
     */
    public function render(mixed $exception): JsonResponse;
}
