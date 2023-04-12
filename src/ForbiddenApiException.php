<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class ForbiddenApiException extends ApiException implements DontReport
{
    public function __construct(string $message = '', ?\Throwable $previous = null)
    {
        if (empty($message)) {
            $message = "You don't have permissions to perform this request.";
        }

        parent::__construct(403, 'forbidden', $message, $previous);
    }
}
