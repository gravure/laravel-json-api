<?php

namespace Gravure\Api\Traits;

use Gravure\Api\Events\Pagination\Filtered;
use Gravure\Api\Events\Pagination\Filtering;
use Gravure\Api\Http\Request;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

trait HandlesPagination
{
    /**
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return LengthAwarePaginator
     */
    protected function mutateQueryForPagination(&$query, Request $request): LengthAwarePaginator
    {
        $pagination = $request->pagination();
        $model = $query->getModel();

        $size = $pagination->size($model->getPerPage());

        $query->limit($size);

        if ($number = $pagination->number()) {
            $query->offset($number * $size);
        }

        if ($sort = $pagination->sort()) {
            $query->orders = [];

            foreach ($sort as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }

        $filter = $pagination->filter();

        app(Dispatcher::class)->dispatch(new Filtering($query, $request, $filter));

        if ($filter->isNotEmpty()) {
            $query->where(function ($q) use ($filter) {
                foreach ($filter as $column => $search) {
                    if (is_int($search) || Str::endsWith($column, '_id')) {
                        $q->where($column, $search);
                    } else {
                        $q->where($column, 'like', "%$search%");
                    }
                }
            });
        }

        app(Dispatcher::class)->dispatch(new Filtered($query, $request));

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate(
            $size,
            ['*'],
            'page[number]',
            $number ?: 1
        );

        if ($sort) {
            $paginator->appends('sort', $this->request->query('sort'));
        }

        if ($filter) {
            $paginator->appends('filter', $this->request->query('filter'));
        }

        return $paginator;
    }
}
