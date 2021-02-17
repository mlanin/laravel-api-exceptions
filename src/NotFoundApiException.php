<?php

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class NotFoundApiException extends ApiException implements DontReport
{
    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Requested object not found.';
        }

        parent::__construct(404, 'not_found', $message, $previous);
    }
}
