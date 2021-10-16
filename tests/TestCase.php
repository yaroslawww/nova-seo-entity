<?php

namespace NovaSeoEntity\Tests;

use Illuminate\Support\Facades\File;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        array_map('unlink', glob(__DIR__ . '/../vendor/orchestra/testbench-core/laravel/database/migrations/*.php'));
        // $this->artisan( 'vendor:publish', [ '--tag' => 'migrations', '--force' => true ] );
        array_map(function ($f) {
            File::copy($f, __DIR__ . '/../vendor/orchestra/testbench-core/laravel/database/migrations/' . basename($f));
        }, glob(__DIR__ . '/Fixtures/migrations/*.php'));


        $this->artisan('migrate', [ '--database' => 'testbench' ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \NovaSeoEntity\ServiceProvider::class,
        ];
    }

    public function defineEnvironment($app)
    {
    }

    /**
     * Define environment setup.
     *
     * @param \Illuminate\Foundation\Application $app
     *
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        // $app['config']->set('nova-seo-entity.some_key', 'some_value');
    }
}
