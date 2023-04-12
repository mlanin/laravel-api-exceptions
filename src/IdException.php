<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

class IdException extends \RuntimeException
{
    public function __construct(
        protected string $id = '',
        string $message = '',
        ?\Throwable $previous = null,
        int $code = 0,
    ) {
        $this->id = $id;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
