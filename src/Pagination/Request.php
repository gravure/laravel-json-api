<?php

namespace Gravure\Pagination;

use Illuminate\Http\Request as Http;

class Request
{
    /**
     * @var Http
     */
    protected $http;

    public function __construct(Http $http)
    {
        $this->http = $http;
    }

    /**
     * @param int|null $default
     * @return int|null
     */
    public function getPageNumber(int $default = null): ?int
    {
        return $this->http->query('page.number', $default);
    }

    /**
     * @param int|null $default
     * @return int|null
     */
    public function getPageSize(int $default = null): ?int
    {
        return $this->http->query('page.size', $default);
    }

    /**
     * @param null $default
     * @return null|string
     */
    public function getFilter($default = null): ?string
    {
        return $this->http->query('filter', $default);
    }
}
