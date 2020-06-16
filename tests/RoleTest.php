<?php

namespace HuangYi\Rbac\Tests;

use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Role;

class RoleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['config']->set('rbac.cache', true);
    }

    /** @test */
    public function it_should_be_attached_permissions()
    {
        $role = factory(Role::class)->create();
        $permissions = factory(Permission::class, 2)->create();

        $role->attachPermissions($permissions);

        $this->assertTrue($role->permissions->contains($permissions[0]));
        $this->assertTrue($role->permissions->contains($permissions[1]));
    }

    /** @test */
    public function it_should_be_detached_permissions()
    {
        $role = factory(Role::class)->create();
        $permissions = factory(Permission::class, 2)->create();

        $role->permissions()->attach($permissions);

        $role->detachPermissions($permissions[0]);

        $this->assertFalse($role->permissions->contains($permissions[0]));
        $this->assertTrue($role->permissions->contains($permissions[1]));
    }

    /** @test */
    public function it_should_be_sync_permissions()
    {
        $role = factory(Role::class)->create();
        $permissions = factory(Permission::class, 2)->create();
        $newPermission = factory(Permission::class)->create();

        $role->permissions()->attach($permissions);

        $role->syncPermissions([$permissions[0]->id, $newPermission->id]);

        $this->assertTrue($role->permissions->contains($permissions[0]));
        $this->assertFalse($role->permissions->contains($permissions[1]));
        $this->assertTrue($role->permissions->contains($newPermission));
    }
}
