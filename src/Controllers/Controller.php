<?php

namespace Gravure\Api\Controllers;

use Gravure\Api\Contracts\Serializer;
use Gravure\Api\Http\Request;
use Gravure\Api\Resources\Collection;
use Gravure\Api\Resources\Document;
use Gravure\Api\Resources\Item;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as IlluminateController;
use Tobscure\JsonApi\ElementInterface;

abstract class Controller extends IlluminateController
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var Request
     */
    protected $request;

    /**
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
        $document = $this->document(new Collection($collection));

        if ($paginator) {
            $document->setPaginator($paginator);
        }

        return new JsonResponse($document, $statusCode);
    }

    /**
     * @param $item
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function item($item, int $statusCode = 200): JsonResponse
    {
        $document = $this->document(new Item($item));

        return new JsonResponse($document, $statusCode);
    }

    /**
     * @param ElementInterface $element
     * @return Document
     */
    protected function document(ElementInterface $element): Document
    {
        $element->with($this->request->includes()->all());

        return new Document($element);
    }

    /**
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function response(int $statusCode = 200): JsonResponse
    {
        return new JsonResponse(null, $statusCode);
    }
}
