<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class UnauthorizedApiException extends ApiException implements DontReport
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Sent credentials are invalid.';
        }

        parent::__construct(401, 'invalid_credentials', $message, $previous);
    }
}
