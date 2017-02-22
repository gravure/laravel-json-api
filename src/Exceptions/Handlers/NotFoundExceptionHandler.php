<?php

namespace Gravure\Api\Exceptions\Handlers;

use Exception;
use Gravure\Api\Contracts\ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class NotFoundExceptionHandler implements ExceptionHandler
{

    /**
     * The HTTP status code to respond with.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return 404;
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
        return $e instanceof NotFoundHttpException || $e instanceof ModelNotFoundException;
    }

    /**
     * Handle the provided exception.
     *
     * @param Exception|NotFoundHttpException|ModelNotFoundException $e
     * @return ResponseBag
     */
    public function handle(Exception $e)
    {
        return null;
    }
}
