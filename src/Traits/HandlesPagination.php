<?php

namespace Gravure\Api\Traits;

use Gravure\Api\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
            foreach ($sort as $column => $direction) {
                $query->orderBy($column, $direction);
            }
        }

        return $query->paginate(
            $size,
            ['*'],
            'page[number]',
            $number ?: 1
        );
    }
}
