<?php

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class ConflictApiException extends ApiException implements DontReport
{
    /**
     * @param string $message
     * @param \Throwable $previous
     */
    public function __construct($message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Request could not be processed because of conflict.';
        }

        parent::__construct(409, 'conflict', $message, $previous);
    }
}
