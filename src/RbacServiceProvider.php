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
     * Setup database connection.
     *
     * @return void
     */
    protected function setupDatabase()
    {
        $connection = $this->app['config']->get('database.connections.rbac');

        if ( ! $connection ) {
            $connectionName = $this->app['config']->get('database.default');
            $connection = $this->app['config']->get('database.connections.'.$connectionName);

            $this->app['config']->set('database.connections.rbac', $connection);
        }
    }

}
