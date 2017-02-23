<?php

namespace Gravure\Api\Exceptions\Handlers;

use Exception;
use Gravure\Api\Contracts\ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tobscure\JsonApi\Exception\Handler\ResponseBag;

class AuthenticationExceptionHandler extends AbstractHandler implements ExceptionHandler
{
    protected $code = 401;

    /**
     * If the exception handler is able to format a response for the provided exception,
     * then the implementation should return true.
     *
     * @param Exception $e
     * @return bool
     */
    public function manages(Exception $e)
    {
        return $e instanceof AuthenticationException;
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
