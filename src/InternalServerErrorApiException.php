<?php

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\ShowsPrevious;
use Lanin\Laravel\ApiExceptions\Contracts\ShowsTrace;

class InternalServerErrorApiException extends ApiException implements ShowsTrace, ShowsPrevious
{
    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'The server encountered an internal error or misconfiguration and was unable to complete your request.';
        }

        parent::__construct(500, 'internal_server_error', $message, $previous);
    }
}
