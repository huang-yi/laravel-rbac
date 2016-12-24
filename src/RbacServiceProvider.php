<?php
/**
 * Copyright
 *
 * (c) Huang Yi <coodeer@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HuangYi\Rbac;

use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
        $this->publishMigrations();
        $this->setupConfig();
        $this->setupDatabase();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('rbac', function ($app) {
            // TODO
        });
    }

    /**
     * Publish config.
     */
    protected function publishConfig()
    {
        $path = $this->getConfigPath();
        $this->publishes([$path => config_path('rbac.php')], 'config');
    }

    /**
     * Publish migrations.
     */
    protected function publishMigrations()
    {
        $path = $this->getMigrationsPath();
        $this->publishes([$path => base_path('/database/migrations')], 'migrations');
    }

    /**
     * Setup config.
     */
    protected function setupConfig()
    {
        $path = $this->getConfigPath();
        $this->mergeConfigFrom($path, 'rbac');
    }

    /**
     * Setup database connection.
     *
     * @return void
     */
    protected function setupDatabase()
    {
        $connection = $this->app['config']->get('rbac.connection');
        $database = $this->app['config']->get('database.connections.' . $connection);

        $this->app['config']->set('database.connections.rbac', $database);
    }

    /**
     * @return string
     */
    protected function getConfigPath()
    {
        return realpath(__DIR__ . '/../config/rbac.php');
    }

    /**
     * @return string
     */
    protected function getMigrationsPath()
    {
        return realpath(__DIR__ . '/..//migrations/');
    }

}
