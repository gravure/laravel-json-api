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
}
