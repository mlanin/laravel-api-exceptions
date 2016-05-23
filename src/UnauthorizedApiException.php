<?php

namespace Notimatica\Exceptions;

use Exception;

class UnauthorizedApiException extends ApiException
{
    /**
     * @param string $message
     * @param Exception $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        if (empty($message)) {
            $message = 'Sent credentials are invalid.';
        }

        parent::__construct(401, 'invalid_credentials', $message, $previous);
    }
}
