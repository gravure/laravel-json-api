<?php

namespace Gravure\Api\Controllers;

use Gravure\Api\Contracts\Serializer;
use Gravure\Api\Http\Request;
use Gravure\Api\Resources\Collection;
use Gravure\Api\Resources\Document;
use Gravure\Api\Resources\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as IlluminateController;
use Tobscure\JsonApi\ElementInterface;

abstract class Controller extends IlluminateController
{
    use ValidatesRequests;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var Request
     */
    protected $request;

    /**
     * Generates a collection resource, that may include pagination functionality.
     *
     * @param $collection
     * @param LengthAwarePaginator|null $paginator
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function collection(
        $collection,
        LengthAwarePaginator $paginator = null,
        int $statusCode = 200
    ): JsonResponse {
        $document = $this->document(new Collection($collection, $this->serializer));

        if ($paginator) {
            $document->setPaginator($paginator);
        }

        return new JsonResponse($document, $statusCode);
    }

    /**
     * Generates an item resource.
     *
     * @param $item
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function item($item, int $statusCode = 200): JsonResponse
    {
        $document = $this->document(new Item($item, $this->serializer));

        return new JsonResponse($document, $statusCode);
    }

    /**
     * Generates a document based on the provided resource.
     *
     * @param ElementInterface $element
     * @return Document
     */
    protected function document(ElementInterface $element): Document
    {
        $element->with($this->request->includes()->all());

        return new Document($element);
    }

    /**
     * Generates a generic no-content json response.
     *
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function response(int $statusCode = 200): JsonResponse
    {
        return new JsonResponse(null, $statusCode);
    }
}
