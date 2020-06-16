<?php

namespace HuangYi\Rbac\Tests;

use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Role;

class AuthorizableTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('rbac.cache', false);
    }

    /** @test */
    public function it_should_be_attached_roles()
    {
        $user = factory(User::class)->create();
        $roles = factory(Role::class, 2)->create();

        $user->attachRoles($roles);

        $this->assertTrue($user->roles->contains($roles[0]));
        $this->assertTrue($user->roles->contains($roles[1]));
    }

    /** @test */
    public function it_should_be_detached_roles()
    {
        $user = factory(User::class)->create();
        $roles = factory(Role::class, 2)->create();

        $user->roles()->attach($roles);

        $user->detachRoles($roles[0]);

        $this->assertFalse($user->roles->contains($roles[0]));
        $this->assertTrue($user->roles->contains($roles[1]));
    }

    /** @test */
    public function it_should_be_sync_roles()
    {
        $user = factory(User::class)->create();
        $roles = factory(Role::class, 2)->create();
        $newRole = factory(Role::class)->create();

        $user->roles()->attach($roles);

        $user->syncRoles([$roles[0]->id, $newRole->id]);

        $this->assertTrue($user->roles->contains($roles[0]));
        $this->assertFalse($user->roles->contains($roles[1]));
        $this->assertTrue($user->roles->contains($newRole));
    }

    /** @test */
    public function it_should_be_attached_permissions()
    {
        $user = factory(User::class)->create();
        $permissions = factory(Permission::class, 2)->create();

        $user->attachPermissions($permissions);

        $this->assertTrue($user->permissions->contains($permissions[0]));
        $this->assertTrue($user->permissions->contains($permissions[1]));
    }

    /** @test */
    public function it_should_be_detached_permissions()
    {
        $user = factory(User::class)->create();
        $permissions = factory(Permission::class, 2)->create();

        $user->permissions()->attach($permissions);

        $user->detachPermissions($permissions[0]);

        $this->assertFalse($user->permissions->contains($permissions[0]));
        $this->assertTrue($user->permissions->contains($permissions[1]));
    }

    /** @test */
    public function it_should_be_sync_permissions()
    {
        $user = factory(User::class)->create();
        $permissions = factory(Permission::class, 2)->create();
        $newPermission = factory(Permission::class)->create();

        $user->permissions()->attach($permissions);

        $user->syncPermissions([$permissions[0]->id, $newPermission->id]);

        $this->assertTrue($user->permissions->contains($permissions[0]));
        $this->assertFalse($user->permissions->contains($permissions[1]));
        $this->assertTrue($user->permissions->contains($newPermission));
    }

    /** @test */
    public function it_should_has_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->roles()->attach($role);

        $this->assertTrue($user->hasRole($role));
        $this->assertTrue($user->hasRole($role->name));
    }

    /** @test */
    public function it_should_has_no_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertFalse($user->hasRole($role));
    }

    /** @test */
    public function it_should_has_roles()
    {
        $user = factory(User::class)->create();
        $roles = factory(Role::class, 5)->create();

        $user->roles()->attach($roles);

        $this->assertTrue($user->hasRoles($roles));
    }

    /** @test */
    public function it_should_has_no_roles()
    {
        $user = factory(User::class)->create();
        $roles = factory(Role::class, 5)->create();

        $user->roles()->attach($roles);

        $roles->add(factory(Role::class)->create());

        $this->assertFalse($user->hasRoles($roles));
    }

    /** @test */
    public function it_should_has_any_roles()
    {
        $user = factory(User::class)->create();
        $roles = factory(Role::class, 2)->create();

        $user->roles()->attach($roles);

        $roles->add(factory(Role::class)->create());

        $this->assertTrue($user->hasAnyRoles($roles));
    }

    /** @test */
    public function it_should_has_no_any_roles()
    {
        $user = factory(User::class)->create();
        $roles = factory(Role::class, 5)->create();

        $this->assertFalse($user->hasAnyRoles($roles));
    }

    /** @test */
    public function it_should_has_permission_by_role()
    {
        $permission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();

        $role->permissions()->attach($permission);
        $user->roles()->attach($role);

        $this->assertTrue($user->hasPermission($permission));
        $this->assertTrue($user->hasPermission($permission->name));
    }

    /** @test */
    public function it_should_has_permission_directly()
    {
        $permission = factory(Permission::class)->create();
        $user = factory(User::class)->create();

        $user->permissions()->attach($permission);

        $this->assertTrue($user->hasPermission($permission));
        $this->assertTrue($user->hasPermission($permission->name));
    }

    /** @test */
    public function it_should_has_no_permission()
    {
        $permission = factory(Permission::class)->create();
        $user = factory(User::class)->create();

        $this->assertFalse($user->hasPermission($permission));
    }

    /** @test */
    public function it_should_has_permissions()
    {
        $directPermissions = factory(Permission::class, 5)->create();
        $rolePermissions = factory(Permission::class, 5)->create();
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();

        $role->permissions()->attach($rolePermissions);
        $user->roles()->attach($role);
        $user->permissions()->attach($directPermissions);

        $this->assertTrue($user->hasPermissions($directPermissions->merge($rolePermissions)));
    }

    /** @test */
    public function it_should_has_no_permissions()
    {
        $directPermissions = factory(Permission::class, 5)->create();
        $rolePermissions = factory(Permission::class, 5)->create();
        $noPermission = factory(Permission::class)->create();
        $role = factory(Role::class)->create();
        $user = factory(User::class)->create();

        $role->permissions()->attach($rolePermissions);
        $user->roles()->attach($role);
        $user->permissions()->attach($directPermissions);

        $this->assertFalse($user->hasPermissions($directPermissions->merge($rolePermissions)->add($noPermission)));
    }

    /** @test */
    public function it_should_has_any_permissions()
    {
        $directPermissions = factory(Permission::class, 5)->create();
        $noPermissions = factory(Permission::class, 5)->create();
        $user = factory(User::class)->create();

        $user->permissions()->attach($directPermissions);

        $this->assertTrue($user->hasAnyPermissions($directPermissions->merge($noPermissions)));
    }

    /** @test */
    public function it_should_has_no_any_permissions()
    {
        $noPermissions = factory(Permission::class, 5)->create();
        $user = factory(User::class)->create();

        $this->assertFalse($user->hasAnyPermissions($noPermissions));
    }
}
