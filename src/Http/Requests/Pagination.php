<?php

namespace Gravure\Api\Http\Requests;

use Gravure\Api\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

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
     * @return Collection
     */
    public function sort(): Collection
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

        return new Collection($output);
    }

    /**
     * @return Collection
     */
    public function filter(): Collection
    {
        return new Collection($this->request->query('filter', []));
    }

    /**
     * @return int|null
     */
    public function number(): ?int
    {
        $number = $this->pageParam('number');

        return !empty($number) ? intval($number) : null;
    }

    /**
     * @param int $default
     * @return int|null
     */
    public function size(int $default = null): ?int
    {
        $size = $this->pageParam('size', $default);

        return !empty($size) ? intval($size) : null;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public function pageParam($key, $default = null)
    {
        return Arr::get($this->request->query('page'), $key, $default);
    }

    /**
     * @param array $except
     * @return array
     */
    public function appendParams(array $except = []): array
    {
        return Arr::except($this->request->query(), $except);
    }
}
