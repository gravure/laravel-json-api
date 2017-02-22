<?php

namespace Gravure\Api\Tests\Exceptions;

use Gravure\Api\Exceptions\Handlers\NotFoundExceptionHandler;
use Gravure\Api\Exceptions\Handlers\ValidationExceptionHandler;
use Gravure\Api\Tests\Controllers\ExceptionController;
use Gravure\Api\Tests\TestCase;
use Illuminate\Support\Arr;

class ExceptionHandlerTest extends TestCase
{
    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['router']->post('validation', ExceptionController::class . '@validation');
        $app['router']->get('model-not-found', ExceptionController::class . '@modelNotFound');
    }

    /**
     * @test
     */
    public function validationException()
    {
        $handler = new ValidationExceptionHandler();

        $response = $this->call('POST', 'validation');

        $response->assertStatus($handler->getStatusCode());

        $this->assertTrue(Arr::has($response->json(), 'errors'));
    }

    public function notFoundException()
    {
        $handler = new NotFoundExceptionHandler;

        $response = $this->call('GET', 'model-not-found');

        $response->assertStatus($handler->getStatusCode());
    }
}
