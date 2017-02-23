<?php

namespace Gravure\Api\Exceptions;

use Exception;
use Gravure\Api\Resources\Document;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler as HandlerContract;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\JsonResponse;

class ExceptionHandler extends Handler implements HandlerContract
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * @var array
     */
    protected $handlers = [
        Handlers\ValidationExceptionHandler::class,
        Handlers\NotFoundExceptionHandler::class,
        Handlers\InvalidArgumentExceptionHandler::class,
        Handlers\AuthenticationExceptionHandler::class,
        Handlers\FallbackHandler::class
    ];

    /**
     * @var bool
     */
    protected $debug;

    public function __construct(Container $container)
    {
        parent::__construct($container);

        $this->debug = $container['config']->get('app.debug', false);
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $e)
    {
        if (!$request->wantsJson()) {
            return parent::render($request, $e);
        }

        $errors = null;

        foreach ($this->handlers as $handler) {
            $handler = new $handler;

            if ($handler->manages($e)) {
                $errors = $handler->handle($e);
                break;
            }
        }

        $document = new Document();

        if ($errors !== null) {
            $document->setErrors($errors);
        }


        return new JsonResponse($document, $handler->getStatusCode());
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  AuthenticationException $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->wantsJson()) {
            return $this->render($request, $exception);
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }
}
