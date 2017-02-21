<?php

namespace Gravure\Api\Middleware;

use Closure;
use Gravure\Api\Http\Request;
use Gravure\Api\Resources\Collection;
use Gravure\Api\Resources\Document;
use Gravure\Api\Resources\Item;
use Illuminate\Http\JsonResponse;
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

        return $this->processResources($response);
    }

    /**
     * @param $response
     * @return JsonResponse|mixed
     */
    protected function processResources($response)
    {
        if ($response instanceof Collection || $response instanceof Item) {
            $document = new Document($response);
        }

        if ($response instanceof Collection && $response->hasPaginator()) {
            $document->setPaginator($response->getPaginator());
        }

        if ($document) {
            unset($response);
            return new JsonResponse($document);
        }

        return $response;
    }
}
