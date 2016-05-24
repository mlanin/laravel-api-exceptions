<?php

namespace Notimatica\ApiExceptions;

use Exception;

class BadRequestApiException extends ApiException
{
    /**
     * @param string $message
     * @param Exception $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        if (empty($message)) {
            $message = 'The server cannot process the request due to its malformed syntax.';
        }

        parent::__construct(400, 'bad_request', $message, $previous);
    }
}
