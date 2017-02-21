<?php

namespace Gravure\Api\Controllers;

use Gravure\Api\Contracts\Repository;
use Gravure\Traits\ParsesPaginationRequests;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Resource;
use Tobscure\JsonApi\SerializerInterface;

abstract class ResourceController extends Controller
{
    use ParsesPaginationRequests;
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

        return new Collection(
            $query->get(),
            $this->serializer
        );
    }

    /**
     * @method GET
     * @param $id
     * @return Resource
     */
    public function show($id)
    {
        $item = $this->repository()->find($id);

        if (!$item) {
            throw new ModelNotFoundException;
        }

        return new Resource(
            $item,
            $this->serializer
        );
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

        return new Resource(
            $item,
            $this->serializer
        );
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
            return new Resource(
                $item,
                $this->serializer
            );
        } else {
            abort(204);
        }
    }

    /**
     * @return Repository
     */
    abstract protected function repository(): Repository;
}
