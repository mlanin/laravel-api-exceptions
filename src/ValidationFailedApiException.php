<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Lanin\Laravel\ApiExceptions\Contracts\DontReport;

class ValidationFailedApiException extends ApiException implements DontReport
{
    public function __construct(protected array $errors, string $message = '', ?\Throwable $previous = null)
    {
        $this->errors = $errors;

        if (empty($message)) {
            $message = 'Validation failed.';
        }

        parent::__construct(422, 'validation_failed', $message, $previous);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Convert and return validations errors in native Laravel way.
     */
    public function getNativeErrors(): array
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

    public function getMeta(): array
    {
        return [
            'errors' => $this->getErrors(),
        ];
    }
}
