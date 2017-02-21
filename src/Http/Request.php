<?php

namespace Gravure\Api\Http;

use Gravure\Api\Requests\Includes;
use Gravure\Api\Requests\Pagination;
use Illuminate\Http\Request as Http;

class Request extends Http
{
    /**
     * @return Pagination
     */
    public function pagination()
    {
        return new Pagination($this);
    }

    public function includes()
    {
        return new Includes($this);
    }
}
