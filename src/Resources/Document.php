<?php

namespace Gravure\Api\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Tobscure\JsonApi\Document as RawDocument;

class Document extends RawDocument
{
    /**
     * @var LengthAwarePaginator
     */
    protected $paginator;

    /**
     * @param LengthAwarePaginator $paginator
     * @return $this
     */
    public function setPaginator(LengthAwarePaginator $paginator)
    {
        $this->paginator = $paginator;

        $this->addLink('first', $paginator->url(1));
        $this->addLink('last', $paginator->url($paginator->lastPage()));

        $this->addLink('prev', $paginator->previousPageUrl());
        $this->addLink('next', $paginator->nextPageUrl());

        $this->addLink('current', $paginator->url($paginator->currentPage()));

        return $this;
    }
}
