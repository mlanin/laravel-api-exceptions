<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class TooManyRequestsApiException extends ApiException implements DontReport
{
    /**
     * @param int|null $retryAfter
     * @param array $headers
     * @param string $message
     * @param \Throwable|null $previous
     */
    public function __construct(protected ?int $retryAfter = null,
        array $headers = [],
        string $message = '',
        ?\Throwable $previous = null,
    ) {
        if (empty($message)) {
            $message = 'Rate limit exceeded.';
        }

        if ($retryAfter) {
            $headers['Retry-After'] = $retryAfter;
        }

        parent::__construct(429, 'too_many_requests', $message, $previous, $headers);
    }

    public function getMeta(): array
    {
        if ($this->retryAfter) {
            return [
                'retry_after' => $this->retryAfter
            ];
        }
        return [];
    }
}
