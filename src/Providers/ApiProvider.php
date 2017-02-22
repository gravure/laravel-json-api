<?php

namespace Gravure\Api\Providers;

use Gravure\Api\Middleware\EnrichesOutput;
use Gravure\Api\Middleware\ReplacesRequest;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;

class ApiProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->make(Kernel::class)
            ->prependMiddleware(ReplacesRequest::class);
    }
}
