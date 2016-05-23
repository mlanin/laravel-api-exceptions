<?php

namespace Notimatica\Exceptions;

use Exception;

class TooManyRequestsApiException extends ApiException
{
    /**
     * @param int|null $retryAfter
     * @param array $headers
     * @param string $message
     * @param Exception $previous
     */
    public function __construct($retryAfter = null, $headers = [],  $message = '', Exception $previous = null)
    {
        if (empty($message)) {
            $message = 'Rate limit exceed.';
        }

        if ($retryAfter) {
            $headers['Retry-After'] = $retryAfter;
        }

        parent::__construct(429, 'too_many_requests', $message, $previous, $headers);
    }
}
