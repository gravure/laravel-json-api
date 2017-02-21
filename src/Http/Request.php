<?php

namespace Gravure\Api\Http;

use Illuminate\Http\Request as Http;

class Request extends Http
{
    /**
     * @return Pagination
     */
    public function pagination()
    {
        return new Requests\Pagination($this);
    }

    public function includes()
    {
        return new Requests\Includes($this);
    }
}
