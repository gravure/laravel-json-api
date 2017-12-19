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

        $this->assertArrayHasKey('data', $response->json());
        $this->assertArrayHasKey('links', $response->json());
    }

    /**
     * @test
     */
    public function index_paginate()
    {
        $firstDummy = $this->addDummy();
        $firstDummy->name = 'first';
        $firstDummy->save();
        $this->addDummy();
        $thirdDummy = $this->addDummy();
        $thirdDummy->name = 'third';
        $thirdDummy->save();

        // No pagination query, should return all items
        $response = $this->getJson('dummies');
        $response->assertStatus(200);

        $dummies = Arr::get($response->json(), 'data');

        $this->assertCount(3, $dummies);

        $linkFirst = Arr::get($response->json(), 'links.first');
        $linkLast = Arr::get($response->json(), 'links.last');
        $linkPrev = Arr::get($response->json(), 'links.prev');
        $linkNext = Arr::get($response->json(), 'links.next');

        $this->assertNotNull($linkFirst);
        $this->assertNotNull($linkLast);
        $this->assertNull($linkPrev);
        $this->assertNull($linkNext);

        $this->assertUrlQueryString($linkFirst, 'page.number', '1');
        $this->assertUrlQueryString($linkLast, 'page.number', '1');

        // Limit number of results
        $response = $this->getJson('dummies?page[size]=2');
        $response->assertStatus(200);

        $dummies = Arr::get($response->json(), 'data');

        $this->assertCount(2, $dummies);
        $this->assertEquals('first', Arr::get(Arr::first($dummies), 'attributes.name'));

        $linkFirst = Arr::get($response->json(), 'links.first');
        $linkLast = Arr::get($response->json(), 'links.last');
        $linkPrev = Arr::get($response->json(), 'links.prev');
        $linkNext = Arr::get($response->json(), 'links.next');

        $this->assertNotNull($linkFirst);
        $this->assertNotNull($linkLast);
        $this->assertNull($linkPrev);
        $this->assertNotNull($linkNext);

        $this->assertUrlQueryString($linkFirst, 'page.number', '1');
        $this->assertUrlQueryString($linkLast, 'page.number', '2');
        $this->assertUrlQueryString($linkNext, 'page.number', '2');

        // Get another page
        $response = $this->getJson('dummies?page[size]=2&page[number]=2');
        $response->assertStatus(200);

        $dummies = Arr::get($response->json(), 'data');

        $this->assertCount(1, $dummies);
        $this->assertEquals('third', Arr::get(Arr::first($dummies), 'attributes.name'));
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

    protected function assertUrlQueryString(string $url, string $key, string $value)
    {
        parse_str(Arr::last(explode('?', $url)), $parameters);

        $this->assertEquals($value, Arr::get($parameters, $key));
    }
}
