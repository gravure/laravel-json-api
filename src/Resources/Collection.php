<?php

namespace Gravure\Api\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tobscure\JsonApi\Collection as RawCollection;

class Collection extends RawCollection
{
    /**
     * @var LengthAwarePaginator
     */
    protected $paginator;

    /**
     * @param LengthAwarePaginator $paginator
     * @return $this
     */
    public function setPaginator(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;

        return $this;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getPaginator(): LengthAwarePaginator
    {
        return $this->paginator;
    }

    /**
     * @return bool
     */
    public function hasPaginator(): bool
    {
        return $this->paginator !== null;
    }

}
