<?php

namespace Gravure\Api\Tests\Controllers;

use Gravure\Api\Tests\Models\Dummy;
use Gravure\Api\Tests\TestCase;
use Illuminate\Support\Arr;

class ResourceControllerTest extends TestCase
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

        $app['router']->get('dummies/view', DummyController::class . '@view');
        $app['router']->resource('dummies', DummyController::class);
    }

    /**
     * @test
     */
    public function index()
    {
        $response = $this->getJson('dummies');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function store()
    {
        $response = $this->postJson('dummies', [
            'name' => 'foo'
        ]);

        $response->assertStatus(201);

        $dummy = $response->json()['data'];

        $this->assertEquals('dummies', Arr::get($dummy, 'type'));
        $this->assertEquals('foo', Arr::get($dummy, 'attributes.name'));
        $this->assertGreaterThan(0, Arr::get($dummy, 'id'));
    }

    /**
     * @test
     */
    public function update()
    {
        $original = $this->addDummy();

        $response = $this->patchJson("dummies/{$original->id}", [
            'name' => 'bar'
        ]);

        $response->assertStatus(200);

        $dummy = $response->json()['data'];

        $this->assertEquals('bar', Arr::get($dummy, 'attributes.name'));
        $this->assertEquals($original->id, Arr::get($dummy, 'id'));
    }

    /**
     * @test
     */
    public function destroy()
    {
        $original = $this->addDummy();

        $response = $this->deleteJson("dummies/{$original->id}");

        $response->assertStatus(204);

        $this->assertNull(Dummy::find($original->id));
    }

    /**
     * @test
     */
    public function still_allows_html()
    {
        $response = $this->get('dummies/view');

        $response->assertStatus(200);
        $response->assertSee('html');
    }

    protected function addDummy(): Dummy
    {
        $dummy = (new Dummy)->forceFill([
            'name' => 'foo'
        ]);

        $dummy->save();

        return $dummy;
    }
}
