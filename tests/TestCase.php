<?php

namespace HuangYi\Rbac\Tests;

use HuangYi\Rbac\RbacServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestBench;

class TestCase extends BaseTestBench
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->withFactories(__DIR__.'/database/factories');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app['config']->set('rbac.user', User::class);
        $this->app['view']->addNamespace('rbac', __DIR__.'/views');
    }

    protected function getPackageProviders($app)
    {
        return [RbacServiceProvider::class];
    }
}
