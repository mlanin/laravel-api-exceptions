<?php

namespace Notimatica\ApiExceptions;

use Exception;

class MethodNotAllowedApiException extends ApiException implements DontReport
{
    /**
     * @param string $message
     * @param Exception $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        if (empty($message)) {
            $message = 'A request was made of a resource using a request method not supported by that resource.';
        }

        parent::__construct(405, 'method_not_allowed', $message, $previous);
    }
}
