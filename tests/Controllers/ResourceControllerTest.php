<?php

namespace Gravure\Api\Tests\Controllers;

use Gravure\Api\Tests\TestCase;

class ResourceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index()
    {
        $response = $this->call('GET', 'dummy');
        $response->assertStatus(200);
    }
}
