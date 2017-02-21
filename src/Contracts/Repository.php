<?php

namespace Gravure\Api\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

interface Repository
{
    /**
     * Returns a pre-filtered modified Builder instance.
     *
     * @return Builder
     */
    public function query(): Builder;

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;

    /**
     * Updates provided model, returns the modified model on success or triggers an exception.
     *
     * @param Model $model
     * @param Request $request
     * @return Model|null
     */
    public function update(Model $model, Request $request): ?Model;

    /**
     * Creates model, returns Model on success or triggers an exception.
     *
     * @param Request $request
     * @return Model|null
     */
    public function create(Request $request): ?Model;

    /**
     * Deletes a model, true on success or triggers an exception.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
