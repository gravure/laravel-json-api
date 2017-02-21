<?php

namespace Gravure\Api\Controllers;

use Gravure\Api\Contracts\Repository;
use Gravure\Traits\ParsesIncludesRequests;
use Gravure\Traits\ParsesPaginationRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\SerializerInterface;

abstract class ResourceController extends Controller
{
    use ParsesPaginationRequests;
    use ParsesIncludesRequests;
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @method GET
     * @param Request $request
     * @return Collection
     */
    public function index(Request $request)
    {
        $query = $this->repository()->query();

        $this->readPaginationRequest($query, $request);

        $document = new Collection(
            $query->get(),
            $this->serializer
        );

        $document->with($this->readIncludes($request));

        return new JsonResponse($document);
    }

    /**
     * @method GET
     * @param Request $request
     * @param int $id
     * @return Resource
     */
    public function show(Request $request, int $id)
    {
        $item = $this->repository()->find($id);

        if (!$item) {
            throw new ModelNotFoundException;
        }

        $document = new Resource(
            $item,
            $this->serializer
        );

        $document->with($this->readIncludes($request));

        return new JsonResponse($document);
    }

    /**
     * Creates a Model using the provided Request input.
     *
     * @method POST
     * @param Request $request
     * @return Resource
     */
    public function create(Request $request)
    {
        $item = $this->repository()->create($request);

        $document = new Resource(
            $item,
            $this->serializer
        );

        $document->with($this->readIncludes($request));

        return new JsonResponse($document, 201);
    }

    /**
     * Updates a Model using the provided Request input.
     *
     * @method PATCH
     *
     * @param Request $request
     * @param int $id
     * @return Resource
     */
    public function store(Request $request, int $id)
    {
        $item = $this->repository()->find($id);

        if (!$item) {
            throw new ModelNotFoundException;
        }

        $item = $this->repository()->update($item, $request);

        if ($item) {
            $document = new Resource(
                $item,
                $this->serializer
            );

            $document->with($this->readIncludes($request));

            return new JsonResponse($document);
        } else {
            return new JsonResponse(null, 204);
        }
    }

    /**
     * @return Repository
     */
    abstract protected function repository(): Repository;
}
