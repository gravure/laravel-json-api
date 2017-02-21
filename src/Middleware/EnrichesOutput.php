<?php

namespace Gravure\Api\Middleware;

use Closure;
use Gravure\Api\Http\Request;
use Illuminate\Http\Request as Http;

class EnrichesOutput
{
    /**
     * @param Http|Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);



        return $response;
    }
}
