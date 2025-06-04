<?php
namespace App\Services\Logging;

use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorLogger
{
    public function log(string $context, Throwable $exception, array $extra = []): void
    {
        Log::error("[$context] " . $exception->getMessage(), array_merge([
            'exception' => $exception,
        ], $extra));
    }
}