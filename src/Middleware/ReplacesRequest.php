<?php

namespace Gravure\Api\Middleware;

use Closure;
use Gravure\Api\Http\Request;
use Illuminate\Http\Request as Http;

class ReplacesRequest
{
    /**
     * @param Http $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Http $request, Closure $next)
    {
        return $next(Request::createFromBase($request));
    }
}
