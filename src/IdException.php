<?php

namespace Lanin\Laravel\ApiExceptions;

class IdException extends \RuntimeException
{
    protected $id = '';

    /**
     * @param string $id
     * @param string $message
     * @param \Exception $previous
     * @param int $code
     */
    public function __construct($id = '', $message = '', \Exception $previous = null, $code = 0)
    {
        $this->id = $id;

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
}
