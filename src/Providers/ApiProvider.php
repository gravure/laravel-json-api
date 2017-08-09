<?php

namespace Gravure\Api\Providers;

use Gravure\Api\Exceptions\ExceptionHandler;
use Gravure\Api\Http\Request;
use Gravure\Api\Middleware\ReplacesRequest;
use Illuminate\Contracts\Debug\ExceptionHandler as BindingHandler;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request as BoundRequest;
use Illuminate\Support\ServiceProvider;
use Spatie\JsonApiPaginate\JsonApiPaginateServiceProvider;

class ApiProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->make(Kernel::class)
            ->prependMiddleware(ReplacesRequest::class);
    }

    public function register()
    {
        $this->register(JsonApiPaginateServiceProvider::class);

        $this->app->extend(BindingHandler::class, function ($handler, $app) {
            return new ExceptionHandler($app);
        });

        $this->app->singleton(Request::class, function ($app) {
            return Request::createFromBase($app['request']);
        });

        $this->app->extend(BoundRequest::class, function ($request, $app) {
            if ($request->expectsJson()) {
                return $app->make(Request::class);
            }
            return $request;
        });
    }
}
