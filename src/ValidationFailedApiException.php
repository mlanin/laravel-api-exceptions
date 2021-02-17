<?php

namespace Lanin\Laravel\ApiExceptions;

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
     * @param \Throwable|null $previous
     */
    public function __construct(array $errors, $message = '', ?\Throwable $previous = null)
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
     * Convert and return validations errors in native Laravel way.
     * 
     * @return array
     */
    public function getNativeErrors()
    {
        $return = [];

        foreach ($this->errors as $field => $errors) {
            $return[$field] = [];
            foreach ($errors as $error) {
                $return[$field][] = $error['message'];
            }
        }

        return $return;
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
