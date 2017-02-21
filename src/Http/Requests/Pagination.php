<?php

namespace Gravure\Api\Requests;

use Gravure\Api\Http\Request;

class Pagination
{
    /**
     * @var Request
     */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}
