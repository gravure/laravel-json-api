<?php

namespace Gravure\Api\Controllers;

use Gravure\Api\Contracts\Repository;
use Gravure\Traits\ParsesPaginationRequests;
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
     * @param $id
     * @return Resource
     */
    public function show($id)
    {
        $item = $this->repository()->find($id);

        if (!$item) {
            abort(404);
        }

        return new Resource(
            $item,
            $this->serializer
        );
    }

    /**
     * @return Repository
     */
    abstract protected function repository(): Repository;
}
