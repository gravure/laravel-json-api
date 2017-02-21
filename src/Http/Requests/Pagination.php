<?php

namespace Gravure\Api\Http\Requests;

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

    /**
     * @return array|null
     */
    public function sort(): ?array
    {
        $sort = $this->request->query('sort');

        if (!$sort) {
            return null;
        }

        $sort = explode(',', $sort);

        $output = [];

        foreach ($sort as $field) {
            if (substr($field, 0, 1) === '-') {
                $field = substr($field, 1);
                $output[$field] = 'desc';
            } else {
                $output[$field] = 'asc';
            }
        }

        return $output;
    }

    /**
     * @return string
     */
    public function filter(): ?string
    {
        return $this->request->filter('filter');
    }

    /**
     * @return int|null
     */
    public function number(): ?int
    {
        return $this->request->query('page.number');
    }

    /**
     * @param int $default
     * @return int|null
     */
    public function size(int $default = null): ?int
    {
        return $this->request->query('page.size', $default);
    }
}
