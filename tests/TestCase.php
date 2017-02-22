<?php

namespace Gravure\Api\Tests;

use Gravure\Api\Providers\ApiProvider;
use Gravure\Api\Tests\Controllers\DummyController;
use Orchestra\Database\ConsoleServiceProvider;

abstract class TestCase extends \Orchestra\Testbench\TestCase
{
    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    public function getPackageProviders($app)
    {
        return [
            ApiProvider::class,
            ConsoleServiceProvider::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testing');
        $app['config']->set('app.debug', true);

        $app['router']->resource('dummies', DummyController::class);
    }

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(realpath(__DIR__ . '/migrations'));
    }
}
