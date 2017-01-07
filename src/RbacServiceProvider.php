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

use HuangYi\Rbac\Managers\RoleManager;
use HuangYi\Rbac\Models\Permission;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Blade;
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
        $this->loadMigrations();
        $this->setupConfig();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRbac();
        $this->registerAlias();
        $this->registerBladeDirectives();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['rbac', 'rbac.role', 'rbac.permission'];
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
     * Load migrations.
     */
    protected function loadMigrations()
    {
        $path = $this->getMigrationsPath();
        $this->loadMigrationsFrom($path);
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
        return realpath(__DIR__ . '/../database/migrations/');
    }

    /**
     * Register rbac service.
     */
    protected function registerRbac()
    {
        $this->app->singleton(Rbac::class, function ($app) {
            $user = $app->make(Guard::class)->user();
            return new Rbac($user);
        });

        $this->app->singleton(RoleManager::class);
        $this->app->singleton(Permission::class);
    }

    /**
     * Register alias.
     */
    protected function registerAlias()
    {
        $this->app->alias(Rbac::class, 'rbac');
        $this->app->alias(RoleManager::class, 'rbac.role');
        $this->app->alias(Permission::class, 'rbac.permission');
    }

    /**
     * Register blade directives.
     */
    protected function registerBladeDirectives()
    {
        if ( ! class_exists('\Illuminate\Support\Facades\Blade') ) {
            return;
        }

        Blade::directive('ifHasRole', function ($roles) {
            return "<?php if(Auth::check() && Auth::user()->hasRole({$roles})): ?>";
        });

        Blade::directive('endIfHasRole', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('ifHasPermission', function ($permissions) {
            return "<?php if(Auth::check() && Auth::user()->hasPermission({$permissions})): ?>";
        });

        Blade::directive('endIfHasPermission', function () {
            return "<?php endif; ?>";
        });
    }

}
