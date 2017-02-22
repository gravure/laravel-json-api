<?php

namespace Gravure\Api\Exceptions;

use Exception;
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
        $document = new Document();

        $document->setErrors([
            $this->processError($e)
        ]);

        return new JsonResponse($document, $this->retrieveStatusCode($e));
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
