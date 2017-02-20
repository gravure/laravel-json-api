<?php

namespace Gravure\Traits;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

trait ParsesPaginationRequests
{
    /**
     * @param Builder $builder
     * @param Request $request
     * @return void
     */
    protected function readPaginationRequest(Builder &$builder, Request $request)
    {

    }

    protected function identifyFilter()
    {
        
    }
}
