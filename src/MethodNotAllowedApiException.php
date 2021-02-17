<?php

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class MethodNotAllowedApiException extends ApiException implements DontReport
{
    /**
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct($message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'A request was made of a resource using a request method not supported by that resource.';
        }

        parent::__construct(405, 'method_not_allowed', $message, $previous);
    }
}
