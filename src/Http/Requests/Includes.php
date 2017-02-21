<?php

namespace Gravure\Api\Http\Requests;

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

    /**
     * @return array
     */
    public function get(): array
    {
        $include = $this->request->query('include');

        return $include ? explode(',', $include) : [];
    }
}
