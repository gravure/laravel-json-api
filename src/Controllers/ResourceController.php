<?php

namespace Gravure\Api\Controllers;

use Gravure\Api\Contracts\Repository;
use Gravure\Api\Traits\HandlesPagination;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

abstract class ResourceController extends Controller
{
    use HandlesPagination;

    /**
     * Provides a list of items with pagination functionality.
     *
     * @method GET
     * @return JsonResponse
     */
    public function index()
    {
        $query = $this->repository()->query();

        $paginator = $this->mutateQueryForPagination($query, $this->request);

        return $this->collection($paginator->items(), $paginator);
    }

    /**
     * Loads one item.
     *
     * @method GET
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $item = $this->repository()->find($id);

        if (!$item) {
            throw new ModelNotFoundException;
        }

        return $this->item($item);
    }

    /**
     * Creates a Model using the provided Request input.
     *
     * @method POST
     * @return JsonResponse
     */
    public function create()
    {
        $item = $this->repository()->create($this->request);

        return $this->item($item, 201);
    }

    /**
     * Updates a Model using the provided Request input.
     *
     * @method PATCH
     *
     * @param int $id
     * @return JsonResponse
     */
    public function store(int $id)
    {
        $item = $this->repository()->find($id);

        if (!$item) {
            throw new ModelNotFoundException;
        }

        $item = $this->repository()->update($item, $this->request);

        if ($item) {
            return $this->item($item);
        }

        return $this->response(204);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function delete(int $id)
    {
        $item = $this->repository()->find($id);

        if (!$item) {
            throw new ModelNotFoundException;
        }

        if ($this->repository()->delete($id)) {
            return $this->response(204);
        }

        return $this->response(400);
    }

    /**
     * @return Repository
     */
    abstract protected function repository(): Repository;
}
