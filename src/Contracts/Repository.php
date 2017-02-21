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
     * Updates provides model, returns the modified model on success or null on fail.
     *
     * @param Model $model
     * @param array $input
     * @return Model|null
     */
    public function update(Model $model, array $input = []): ?Model;
}
