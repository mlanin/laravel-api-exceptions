<?php

namespace Notimatica\Exceptions;

use Exception;

class ForbiddenApiException extends ApiException
{
    /**
     * @param string $message
     * @param Exception $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        if (empty($message)) {
            $message = "You don't have permissions to perform this request.";
        }

        parent::__construct(403, 'forbidden', $message, $previous);
    }
}
