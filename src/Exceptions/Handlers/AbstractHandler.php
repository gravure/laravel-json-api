<?php

namespace Gravure\Api\Exceptions\Handlers;

use Gravure\Api\Contracts\ExceptionHandler;

abstract class AbstractHandler implements ExceptionHandler
{
    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * Http status code.
     *
     * @var int
     */
    protected $code;

    /**
     * {@inheritdoc}
     */
    public function setDebug(bool $debug = true)
    {
        $this->debug = $debug;

        return $this;
    }

    /**
     * The HTTP status code to respond with.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->code ?? 500;
    }
}
