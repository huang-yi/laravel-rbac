<?php

namespace HuangYi\Rbac;

use HuangYi\Rbac\Contracts\Authorizable;
use HuangYi\Rbac\Directives\PermissionRegistrar;
use HuangYi\Rbac\Directives\RoleRegistrar;
use HuangYi\Rbac\Middleware\Permission;
use HuangYi\Rbac\Middleware\Role;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class RbacServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/rbac.php', 'rbac');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerGate();

        $this->registerDirectives();

        $this->publishResources();
    }

    /**
     * Register laravel gate.
     *
     * @return void
     */
    protected function registerGate()
    {
        if ($this->app->bound('router')) {
            $this->app['router']->aliasMiddleware('role', Role::class);
            $this->app['router']->aliasMiddleware('permission', Permission::class);
        }

        if ($this->app->bound(Gate::class)) {
            $this->app[Gate::class]->before(function ($user, $permission) {
                if ($user instanceof Authorizable) {
                    if ($user->hasAnyPermissions(explode('|', $permission))) {
                        return true;
                    }
                }
            });
        }
    }

    /**
     * Register view directives.
     *
     * @return void
     */
    protected function registerDirectives()
    {
        if ($this->app->bound('blade.compiler')) {
            (new PermissionRegistrar($this->app['blade.compiler']))->register();

            (new RoleRegistrar($this->app['blade.compiler']))->register();
        }
    }

    /**
     * Publish the RBAC resources.
     *
     * @return void
     */
    protected function publishResources()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/rbac.php' => config_path('rbac.php'),
            ], 'rbac-config');

            $this->publishes([
                __DIR__.'/../database/migrations' => database_path('migrations'),
            ], 'rbac-config');

            $this->loadFactoriesFrom(__DIR__.'/../database/factories');
        }
    }
}
