<?php

namespace Gravure\Api\Events\Pagination;

use Gravure\Api\Http\Request;

abstract class Filter
{
    /**
     * @var \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public $query;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var \Illuminate\Support\Collection
     */
    public $filter;

    public function __construct(&$query, Request $request, &$filter)
    {
        $this->query = $query;
        $this->request = $request;
        $this->filter = $filter;
    }
}
