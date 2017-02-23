<?php

namespace Gravure\Api\Events\Pagination;

use Gravure\Api\Http\Request;

abstract class Event
{
    /**
     * @var \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    public $query;

    /**
     * @var Request
     */
    public $request;

    public function __construct(&$query, Request $request)
    {
        $this->query = $query;
        $this->request = $request;
    }
}
