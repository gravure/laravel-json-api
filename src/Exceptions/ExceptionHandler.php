<?php

namespace Gravure\Api\Exceptions;

use Exception;
use Gravure\Api\Exceptions\Handlers\ValidationExceptionHandler;
use Gravure\Api\Resources\Document;
use HttpRequestMethodException;
use Illuminate\Contracts\Debug\ExceptionHandler as HandlerContract;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandler implements HandlerContract
{
    protected $handlers = [
        ValidationExceptionHandler::class
    ];

    /**
     * @var bool
     */
    protected $debug;

    public function __construct(bool $debug = false)
    {
        $this->debug = $debug;
    }

    /**
     * Report or log an exception.
     *
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        // TODO: Implement report() method.
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
        /** @var null|\Gravure\Api\Contracts\ExceptionHandler $exceptionHandler */
        $exceptionHandler = null;

        foreach ($this->handlers as $handler) {
            $exceptionHandler = new $handler;

            if ($exceptionHandler->manages($e)) {
                $errors = $exceptionHandler->handle($e);
                break;
            }
        }

        $document = new Document();

        if ($errors !== null) {
            $document->setErrors($errors);
        }

        return new JsonResponse($document, $exceptionHandler->getStatusCode());
    }

    /**
     * Render an exception to the console.
     *
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @param  \Exception $e
     * @return void
     */
    public function renderForConsole($output, Exception $e)
    {
        // TODO: Implement renderForConsole() method.
    }

    /**
     * @param ValidationException $e
     * @return array
     */
    protected function processValidationError(ValidationException $e)
    {
        return $e->validator->getMessageBag()->toArray();
    }

    /**
     * @param Exception $e
     * @return array
     */
    protected function processError(Exception $e)
    {
        $error = [
            'code' => $this->retrieveStatusCode($e),
            'title' => 'Internal server error'
        ];

        if ($this->debug) {
            $error['detail'] = (string)$e;
        }

        return $error;
    }

    /**
     * @param Exception $e
     * @return int
     */
    protected function retrieveStatusCode(Exception $e)
    {
        if ($e instanceof InvalidArgumentException) {
            return 400;
        }

        if ($e instanceof ValidationException) {
            return 400;
        }

        if ($e instanceof ModelNotFoundException) {
            return 404;
        }

        if ($e instanceof NotFoundHttpException) {
            return 404;
        }

        if ($e instanceof HttpRequestMethodException) {
            return 405;
        }

        return 500;
    }
}
