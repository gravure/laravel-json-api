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

        $json = $this->postJson('validation');

        $json->assertStatus($handler->getStatusCode());
        $json->isInvalid();
        $json->assertHeader('content-type', 'application/json');

        $this->assertTrue(Arr::has($json->json(), 'errors'),
            'Errors root property not found in payload: ' . $json->content());
    }

    /**
     * @test
     */
    public function htmlValidationException()
    {
        $html = $this->post('validation');

        // Redirects back.
        $html->assertStatus(302);
        $html->assertHeader('content-type', 'text/html; charset=UTF-8');
    }

    /**
     * @test
     */
    public function notFoundException()
    {
        $handler = new NotFoundExceptionHandler;

        $json = $this->getJson('model-not-found');

        $json->assertStatus($handler->getStatusCode());
        $json->isNotFound();
        $json->assertHeader('content-type', 'application/json');
    }

    /**
     * @test
     */
    public function htmlNotFoundException()
    {
        $handler = new NotFoundExceptionHandler;

        $html = $this->get('model-not-found');

        $html->assertStatus($handler->getStatusCode());
        $html->isNotFound();
        $html->assertHeader('content-type', 'text/html; charset=UTF-8');
    }
}
