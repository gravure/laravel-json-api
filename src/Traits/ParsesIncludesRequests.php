<?php

namespace Gravure\Traits;

use Illuminate\Http\Request;

trait ParsesIncludesRequests
{
    /**
     * @param Request $request
     * @return array|null
     */
    public function readIncludes(Request $request): ?array
    {
        return $request->get('include') ? explode(',', $request->include('include')) : null;
    }
}
