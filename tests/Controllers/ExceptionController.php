<?php

namespace Gravure\Api\Tests\Controllers;

use Gravure\Api\Controllers\Controller;
use Gravure\Api\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExceptionController extends Controller
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * ExceptionController constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function validation()
    {
        $this->validate(
            $this->request,
            ['name' => 'required|string']
        );
    }

    public function modelNotFound()
    {
        throw new ModelNotFoundException;
    }
}
