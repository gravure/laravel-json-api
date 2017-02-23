<?php

namespace Gravure\Api\Exceptions;

use Exception;
use Gravure\Api\Resources\Document;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Debug\ExceptionHandler as HandlerContract;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\JsonResponse;

class ExceptionHandler extends Handler implements HandlerContract
{
    protected $handlers = [
        Handlers\ValidationExceptionHandler::class,
        Handlers\NotFoundExceptionHandler::class,
        Handlers\InvalidArgumentExceptionHandler::class,
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
}
