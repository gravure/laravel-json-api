<?php

namespace Gravure\Api\Exceptions\Handlers;

use Exception;
use Gravure\Api\Contracts\ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class ValidationExceptionHandler implements ExceptionHandler
{

    /**
     * If the exception handler is able to format a response for the provided exception,
     * then the implementation should return true.
     *
     * @param Exception $e
     * @return bool
     */
    public function manages(Exception $e)
    {
        return $e instanceof ValidationException;
    }

    /**
     * Handle the provided exception.
     *
     * @param ValidationException|Exception $e
     * @return ResponseBag
     */
    public function handle(Exception $e)
    {
        return $e->validator->getMessageBag()->toArray();
    }

    /**
     * The HTTP status code to respond with.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return 400;
    }
}
