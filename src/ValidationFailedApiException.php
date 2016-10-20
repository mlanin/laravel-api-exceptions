<?php

namespace Lanin\Laravel\ApiExceptions;

use Exception;
use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class ValidationFailedApiException extends ApiException implements DontReport
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * Create a new ValidationFailedApiException.
     *
     * @param array $errors
     * @param string $message
     * @param Exception $previous
     */
    public function __construct(array $errors, $message = '', Exception $previous = null)
    {
        $this->errors = $errors;

        if (empty($message)) {
            $message = 'Validation failed.';
        }

        parent::__construct(422, 'validation_failed', $message, $previous);
    }

    /**
     * Get array of errors
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Add extra info to the output.
     *
     * @return mixed
     */
    public function getMeta()
    {
        return [
            'errors' => $this->getErrors(),
        ];
    }
}
