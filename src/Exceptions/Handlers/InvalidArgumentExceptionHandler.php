<?php

namespace Gravure\Api\Exceptions\Handlers;

use Exception;
use Gravure\Api\Contracts\ExceptionHandler;
use InvalidArgumentException;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class InvalidArgumentExceptionHandler implements ExceptionHandler
{

    /**
     * The HTTP status code to respond with.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return 400;
    }

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

    /**
     * Handle the provided exception.
     *
     * @param Exception $e
     * @return ResponseBag
     */
    public function handle(Exception $e)
    {
        return [
            (string)$e
        ];
    }
}
