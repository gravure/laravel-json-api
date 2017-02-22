<?php

namespace Gravure\Api\Contracts;

use Tobscure\JsonApi\Exception\Handler\ExceptionHandlerInterface;

interface ExceptionHandler extends ExceptionHandlerInterface
{
    /**
     * The HTTP status code to respond with.
     *
     * @return int
     */
    public function getStatusCode(): int;
}
