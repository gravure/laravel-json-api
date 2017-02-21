<?php

namespace Gravure\Api\Requests;

use Gravure\Api\Http\Request;

class Includes
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function get(): array
    {
        $include = $this->request->query('include');

        return $include ? explode(',', $include) : [];
    }
}
