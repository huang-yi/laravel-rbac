<?php

namespace HuangYi\Rbac\Tests;

use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Role;
use Illuminate\Support\Facades\Cache;

class CacheRbacTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('rbac.cache', true);

        Cache::clear();
    }

    /** @test */
    public function it_should_load_user_roles_from_cache()
    {
        $user = factory(User::class)->create();

        $user->loadRolesFromCache();

        $this->assertTrue(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_attach_user_roles()
    {
        $user = factory(User::class)->create();

        $user->loadRolesFromCache();

        $user->attachRoles(
            factory(Role::class, 2)->create()
        );

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_detach_user_roles()
    {
        $user = factory(User::class)->create();

        $user->roles()->attach(
            $roles = factory(Role::class, 2)->create()
        );

        $user->loadRolesFromCache();

        $user->detachRoles($roles[0]);

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_sync_user_roles()
    {
        $user = factory(User::class)->create();

        $user->roles()->attach(
            $roles = factory(Role::class, 2)->create()
        );

        $user->loadRolesFromCache();

        $roles = $roles->random(1)->add(factory(Role::class)->create());

        $user->syncRoles($roles);

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_load_permissions_from_cache()
    {
        $user = factory(User::class)->create();

        $user->loadPermissionsFromCache();

        $this->assertTrue(Cache::has(User::rbacCacheKey($user->id, 'permissions')));
    }

    /** @test */
    public function it_should_forget_cache_after_attach_user_permissions()
    {
        $user = factory(User::class)->create();

        $user->loadPermissionsFromCache();

        $user->attachPermissions(
            factory(Permission::class, 2)->create()
        );

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'permissions')));
    }

    /** @test */
    public function it_should_forget_cache_after_detach_user_permissions()
    {
        $user = factory(User::class)->create();

        $user->permissions()->attach(
            $permissions = factory(Permission::class, 2)->create()
        );

        $user->loadPermissionsFromCache();

        $user->detachPermissions($permissions[0]);

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'permissions')));
    }

    /** @test */
    public function it_should_forget_cache_after_sync_user_permissions()
    {
        $user = factory(User::class)->create();

        $user->permissions()->attach(
            $permissions = factory(Permission::class, 2)->create()
        );

        $user->loadPermissionsFromCache();

        $permissions = $permissions->random(1)->add(factory(Permission::class)->create());

        $user->syncPermissions($permissions);

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'permissions')));
    }

    /** @test */
    public function it_should_forget_cache_after_update_role_belonging_to_user()
    {
        $user = factory(User::class)->create();

        $user->roles()->attach(
            $role = factory(Role::class)->create()
        );

        $user->loadRolesFromCache();

        $role->update(['name' => 'new role']);

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_delete_role_belonging_to_user()
    {
        $user = factory(User::class)->create();

        $user->roles()->attach(
            $role = factory(Role::class)->create()
        );

        $user->loadRolesFromCache();

        $role->delete();

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_update_permission_belonging_to_user()
    {
        $user = factory(User::class)->create();

        $user->permissions()->attach(
            $permission = factory(Permission::class)->create()
        );

        $user->loadPermissionsFromCache();

        $permission->update(['name' => 'new permission']);

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'permissions')));
    }

    /** @test */
    public function it_should_forget_cache_after_delete_permission_belonging_to_user()
    {
        $user = factory(User::class)->create();

        $user->permissions()->attach(
            $permission = factory(Permission::class)->create()
        );

        $user->loadPermissionsFromCache();

        $permission->delete();

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'permissions')));
    }

    /** @test */
    public function it_should_forget_cache_after_update_role_permission_belonging_to_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $role->permissions()->attach(
            $permission = factory(Permission::class)->create()
        );

        $user->roles()->attach($role);

        $user->loadRolesFromCache();

        $permission->update(['name' => 'new permission']);

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_delete_role_permission_belonging_to_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $role->permissions()->attach(
            $permission = factory(Permission::class)->create()
        );

        $user->roles()->attach($role);

        $user->loadRolesFromCache();

        $permission->delete();

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_attach_role_permissions()
    {
        $user = factory(User::class)->create();

        $user->roles()->attach(
            $role = factory(Role::class)->create()
        );

        $user->loadRolesFromCache();

        $role->attachPermissions(
            factory(Permission::class, 2)->create()
        );

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_detach_role_permissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $role->permissions()->attach(
            $permissions = factory(Permission::class, 2)->create()
        );

        $user->roles()->attach($role);

        $user->loadRolesFromCache();

        $role->detachPermissions($permissions[0]);

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }

    /** @test */
    public function it_should_forget_cache_after_sync_role_permissions()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $role->permissions()->attach(
            $permissions = factory(Permission::class, 2)->create()
        );

        $user->roles()->attach($role);

        $user->loadRolesFromCache();

        $role->detachPermissions($permissions->random(1)->add(factory(Permission::class)->create()));

        $this->assertFalse(Cache::has(User::rbacCacheKey($user->id, 'roles')));
    }
}
