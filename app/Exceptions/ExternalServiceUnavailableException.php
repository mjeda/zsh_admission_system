<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Inarushwa pale mfumo wa nje (NECTA / NACTE) haupatikani:
 * hakuna connection, timeout, au server imejibu kwa 5xx.
 */
class ExternalServiceUnavailableException extends Exception
{
    public function __construct(
        public string $service,
        public string $action,
        ?Throwable $previous = null
    ) {
        parent::__construct("{$service} haipatikani wakati wa: {$action}", 503, $previous);
    }

    public function report(): void
    {
        Log::error('External service unavailable', [
            'service' => $this->service,
            'action' => $this->action,
            'error' => $this->getPrevious()?->getMessage(),
        ]);
    }

    public function render($request)
    {
        return response()->view('errors.service-unavailable', [
            'service' => $this->service,
            'action' => $this->action,
        ], 503);
    }
}
