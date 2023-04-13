<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class NotFoundApiException extends ApiException implements DontReport
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = 'Requested object not found.';
        }

        parent::__construct(404, 'not_found', $message, $previous);
    }
}
