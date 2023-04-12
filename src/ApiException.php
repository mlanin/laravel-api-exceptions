<?php

declare(strict_types=1);

namespace Lanin\Laravel\ApiExceptions;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Str;
use Lanin\Laravel\ApiExceptions\Contracts\ShowsPrevious;
use Lanin\Laravel\ApiExceptions\Contracts\ShowsTrace;

abstract class ApiException extends IdException implements Jsonable, \JsonSerializable, Arrayable
{
    public function __construct(
        int $statusCode = 0,
        string $id = '',
        string $message = '',
        ?\Throwable $previous = null,
        protected array $headers = [],
    ) {
        parent::__construct($id, $message, $previous, $statusCode);
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray());
    }

    public function toArray(): array
    {
        $e = $this;

        if (env('APP_DEBUG') && $e instanceof ShowsPrevious && $this->getPrevious() !== null) {
            $e = $this->getPrevious();
        }

        $return = [];
        $return['id'] = $e instanceof IdException ? $e->getId() : Str::snake(class_basename($e));
        $return['message'] = $e->getMessage();

        if ($e instanceof ApiException) {
            $meta = $this->getMeta();
            if (!empty($meta)) {
                $return['meta'] = $meta;
            }
        }

        if (env('APP_DEBUG') && $this instanceof ShowsTrace) {
            $return['trace'] = \Symfony\Component\ErrorHandler\Exception\FlattenException::createFromThrowable($e)->getTrace();
        }

        return $return;
    }

    public function toReport(): self
    {
        return $this;
    }

    /**
     * Add extra info to the output.
     */
    public function getMeta(): array
    {
        return [];
    }
}
