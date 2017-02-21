<?php

namespace Gravure\Api\Http;

use Illuminate\Http\Request as Http;

class Request extends Http
{
    /**
     * @return Requests\Pagination
     */
    public function pagination()
    {
        return new Requests\Pagination($this);
    }

    /**
     * @return Requests\Includes
     */
    public function includes()
    {
        return new Requests\Includes($this);
    }
}
