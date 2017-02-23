<?php

namespace Gravure\Api\Providers;

use Gravure\Api\Exceptions\ExceptionHandler;
use Gravure\Api\Http\Request;
use Gravure\Api\Middleware\ReplacesRequest;
use Illuminate\Contracts\Debug\ExceptionHandler as BindingHandler;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request as BoundRequest;
use Illuminate\Support\ServiceProvider;

class ApiProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->make(Kernel::class)
            ->prependMiddleware(ReplacesRequest::class);
    }

    public function register()
    {
        $this->app->extend(BindingHandler::class, function ($handler, $app) {
            return new ExceptionHandler($app);
        });
        $this->app->extend(BoundRequest::class, function ($request, $app) {
            if ($request->wantsJson()) {
                return Request::createFromBase($app['request']);
            }
            return $request;
        });
    }
}
