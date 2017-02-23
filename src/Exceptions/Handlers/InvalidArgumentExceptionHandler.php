<?php

namespace Gravure\Api\Exceptions\Handlers;

use Exception;
use Gravure\Api\Contracts\ExceptionHandler;
use InvalidArgumentException;

class InvalidArgumentExceptionHandler extends FallbackHandler implements ExceptionHandler
{

    protected $code = 400;

    /**
     * If the exception handler is able to format a response for the provided exception,
     * then the implementation should return true.
     *
     * @param Exception $e
     * @return bool
     */
    public function manages(Exception $e)
    {
        return $e instanceof InvalidArgumentException;
    }
}
