<?php

namespace HuangYi\Rbac\Tests;

use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Rbac;
use HuangYi\Rbac\Role;

class MiddlewareTest extends TestCase
{
    protected $permission;
    protected $role;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('rbac.cache', false);

        $this->permission = factory(Permission::class)->create();
        $this->role = factory(Role::class)->create();

        $this->app['router']->get('permission', function () {})->middleware('permission:'.$this->permission->name);
        $this->app['router']->get('role', function () {})->middleware('role:'.$this->role->name);
    }

    /** @test */
    public function it_should_access_successful_when_user_has_permission()
    {
        $user = factory(User::class)->create();

        $user->permissions()->attach($this->permission);

        $this->actingAs($user);

        $response = $this->get('permission');

        $response->assertOk();
    }

    /** @test */
    public function it_should_access_forbidden_when_user_has_no_permission()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $response = $this->get('permission');

        $response->assertForbidden();
    }

    /** @test */
    public function it_should_access_successful_when_user_has_role()
    {
        $user = factory(User::class)->create();

        $user->roles()->attach($this->role);

        $this->actingAs($user);

        $response = $this->get('role');

        $response->assertOk();
    }

    /** @test */
    public function it_should_access_forbidden_when_user_has_no_role()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user);

        $response = $this->get('role');

        $response->assertForbidden();
    }

    /** @test */
    public function it_should_access_successful_when_user_is_super_admin()
    {
        $user = factory(User::class)->create();

        Rbac::checkSuperAdminUsing(function ($model) use ($user) {
            return $model === $user;
        });

        $this->actingAs($user);

        $this->get('permission')->assertOk();
        $this->get('role')->assertOk();
    }
}
