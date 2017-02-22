<?php

namespace Gravure\Api\Providers;

use Gravure\Api\Exceptions\ExceptionHandler;
use Gravure\Api\Http\Request;
use Gravure\Api\Middleware\ReplacesRequest;
use Illuminate\Contracts\Debug\ExceptionHandler as BindingHandler;
use Illuminate\Contracts\Http\Kernel;
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
        if ($this->app['request']->wantsJson()) {
            $this->app->singleton(BindingHandler::class, function ($app) {
                return new ExceptionHandler($app['config']->get('app.debug'));
            });
            $this->app->singleton(Request::class, function ($app) {
                return Request::createFromBase($app['request']);
            });
        }
    }
}
