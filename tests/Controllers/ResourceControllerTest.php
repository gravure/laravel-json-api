<?php

namespace Gravure\Api\Tests\Controllers;

use Gravure\Api\Tests\Models\Dummy;
use Gravure\Api\Tests\TestCase;
use Illuminate\Support\Arr;

class ResourceControllerTest extends TestCase
{
    /**
     * @test
     */
    public function index()
    {
        $response = $this->call('GET', 'dummies');
        $response->assertStatus(200);
    }

    /**
     * @test
     * @return array
     */
    public function store()
    {
        $response = $this->json('POST', 'dummies', [
            'name' => 'foo'
        ]);

        $response->assertStatus(201);

        $dummy = $response->json()['data'];

        $this->assertEquals('dummies', Arr::get($dummy, 'type'));
        $this->assertEquals('foo', Arr::get($dummy, 'attributes.name'));
        $this->assertGreaterThan(0, Arr::get($dummy, 'id'));

        return $dummy;
    }

    /**
     * @test
     * @return array
     */
    public function update()
    {
        $original = $this->addDummy();

        $response = $this->json('PATCH', "dummies/{$original->id}", [
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

        $response = $this->call('DELETE', "dummies/{$original->id}");

        $response->assertStatus(204);

        $this->assertNull(Dummy::find($original->id));
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
