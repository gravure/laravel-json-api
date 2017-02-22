<?php

namespace Gravure\Api\Tests\Repositories;

use Gravure\Api\Contracts\Repository;
use Gravure\Api\Contracts\Request;
use Gravure\Api\Tests\Models\Dummy;
use Illuminate\Database\Eloquent\Model;

class DummyRepository implements Repository
{
    protected $model;

    public function __construct(Dummy $dummy)
    {
        $this->model = $dummy;
    }

    /**
     * Returns a pre-filtered modified Builder instance.
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return $this->model->newQuery();
    }


    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->query()->find($id);
    }

    /**
     * Updates provided model, returns the modified model on success or triggers an exception.
     *
     * @param Model $model
     * @param Request $request
     * @return Model|null
     */
    public function update(Model $model, Request $request): ?Model
    {
        // TODO: Implement update() method.
    }

    /**
     * Creates model, returns Model on success or triggers an exception.
     *
     * @param Request $request
     * @return Model|null
     */
    public function create(Request $request): ?Model
    {
        // TODO: Implement create() method.
    }

    /**
     * Deletes a model, true on success or triggers an exception.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->query()->findOrFail($id)->delete();
    }
}
