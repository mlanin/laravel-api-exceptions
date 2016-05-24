<?php

namespace Notimatica\ApiExceptions;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

abstract class ApiException extends IdException implements Jsonable, \JsonSerializable, Arrayable
{
    protected $headers = [];

    /**
     * @param int        $statusCode
     * @param string     $id
     * @param string     $message
     * @param \Exception $previous
     * @param array      $headers
     */
    public function __construct($statusCode = 0, $id = '', $message = '', \Exception $previous = null, array $headers = [])
    {
        $this->headers = $headers;

        parent::__construct($id, $message, $previous, $statusCode);
    }

    /**
     * Return headers array.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Convert exception to JSON.
     *
     * @param  int $options
     * @return array
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    /**
     * Convert exception to array.
     *
     * @return string
     */
    public function toArray()
    {
        $e = $this;

        if (env('APP_DEBUG') && $this->getPrevious() !== null) {
            $e = $this->getPrevious();
        }

        $return = [];
        $return['id'] = $e instanceof IdException ? $e->getId() : snake_case(class_basename($e));
        $return['message'] = $e->getMessage();

        $meta = $this->getMeta();
        if (! empty($meta)) {
            $return['meta'] = $meta;
        }

        if (env('APP_DEBUG')) {
            $return['trace'] = $e->getTrace();
        }

        return $return;
    }

    /**
     * Prepare exception for report.
     *
     * @return string
     */
    public function toReport()
    {
        if ($this->getPrevious() !== null) {
            return $this->getPrevious();
        }

        return new \Exception($this->getMessage(), $this->getCode());
    }

    /**
     * Add extra info to the output.
     *
     * @return mixed
     */
    public function getMeta() {}
}
