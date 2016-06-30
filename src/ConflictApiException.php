<?php

namespace Notimatica\ApiExceptions;

use Exception;
use Notimatica\ApiExceptions\Contracts\DontReport;

class ConflictApiException extends ApiException implements DontReport
{
    /**
     * @param string $message
     * @param Exception $previous
     */
    public function __construct($message = '', Exception $previous = null)
    {
        if (empty($message)) {
            $message = 'Request could not be processed because of conflict.';
        }

        parent::__construct(409, 'conflict', $message, $previous);
    }
}
