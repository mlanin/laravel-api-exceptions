<?php

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class UnauthorizedApiException extends ApiException implements DontReport
{
    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Sent credentials are invalid.';
        }

        parent::__construct(401, 'invalid_credentials', $message, $previous);
    }
}
