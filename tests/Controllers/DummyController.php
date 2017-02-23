<?php

namespace Gravure\Api\Tests\Controllers;

use Gravure\Api\Contracts\Repository;
use Gravure\Api\Controllers\ResourceController;
use Gravure\Api\Tests\Models\Dummy;
use Gravure\Api\Tests\Repositories\DummyRepository;
use Gravure\Api\Tests\Serializers\DummySerializer;
use Illuminate\Http\Request;

class DummyController extends ResourceController
{

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->serializer = new DummySerializer();
    }

    public function view()
    {
        return response(200)->setContent('html');
    }

    /**
     * @return Repository
     */
    protected function repository(): Repository
    {
        return new DummyRepository(new Dummy);
    }
}
