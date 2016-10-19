<?php

namespace Lanin\Laravel\ApiExceptions;

use Exception;
use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class TooManyRequestsApiException extends ApiException implements DontReport
{
    /**
     * @var int|null
     */
    protected $retryAfter = null;

    /**
     * @param int|null $retryAfter
     * @param array $headers
     * @param string $message
     * @param Exception $previous
     */
    public function __construct($retryAfter = null, $headers = [],  $message = '', Exception $previous = null)
    {
        $this->retryAfter = $retryAfter;

        if (empty($message)) {
            $message = 'Rate limit exceed.';
        }

        if ($retryAfter) {
            $headers['Retry-After'] = $retryAfter;
        }

        parent::__construct(429, 'too_many_requests', $message, $previous, $headers);
    }

    /**
     * Add extra info to the output.
     *
     * @return mixed
     */
    public function getMeta()
    {
        if ($this->retryAfter) {
            return [
                'retry_after' => $this->retryAfter
            ];
        }
    }
}
