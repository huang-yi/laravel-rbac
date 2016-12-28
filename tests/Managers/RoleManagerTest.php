<?php
/**
 * File: RoleManagerTest.php
 * Date: 2016/12/27
 * Time: 下午2:06
 */

namespace HuangYi\Rbac\Tests\Managers;

use HuangYi\Rbac\Exceptions\RoleNotFoundException;
use HuangYi\Rbac\Managers\RoleManager;
use HuangYi\Rbac\Models\Permission;
use HuangYi\Rbac\Models\Role;
use HuangYi\Rbac\Tests\AbstractTestCase;

class RoleManagerTest extends AbstractTestCase
{
    protected function getRoleManager()
    {
        return new RoleManager();
    }

    public function testCreate()
    {
        $data = [
            'name' => 'Administrator1',
            'slug' => 'admin',
            'description' => 'Administrator1 has the highest authority, can do anything.'
        ];

        $role = $this->getRoleManager()->create($data);

        $this->assertInstanceOf(Role::class, $role);
        $this->seeInDatabase('roles', $data);
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'Author1',
            'slug' => 'author',
            'description' => 'Author1 can post articles.'
        ];

        $update = [
            'description' => 'Author1 updated.'
        ];

        $role = $this->getRoleManager()->create($data);
        $saved = $this->getRoleManager()->update($role->getKey(), $update);

        $this->assertTrue($saved);
        $this->seeInDatabase('roles', array_merge($data, $update));
    }

    public function testFind()
    {
        $role = factory(Role::class)->create();

        $model = $this->getRoleManager()->find($role->id);

        $this->assertInstanceOf(Role::class, $model);
    }

    public function testNotFound()
    {
        $this->expectException(RoleNotFoundException::class);
        $this->getRoleManager()->find(time());
    }

    public function testAttachPermission()
    {
        $role = factory(Role::class)->create();
        $permission1 = factory(Permission::class)->create();
        $permission2 = factory(Permission::class)->create();

        $this->getRoleManager()->attachPermissions($role->id, [$permission1->id, $permission2->id]);

        $this->seeInDatabase('role_permission', ['role_id' => $role->id, 'permission_id' => $permission1->id]);
        $this->seeInDatabase('role_permission', ['role_id' => $role->id, 'permission_id' => $permission2->id]);
    }

    public function testDetachPermission()
    {
        $role = factory(Role::class)->create();
        $permission1 = factory(Permission::class)->create();
        $permission2 = factory(Permission::class)->create();

        $this->getRoleManager()->attachPermissions($role->id, [$permission1->id, $permission2->id]);

        $this->getRoleManager()->detachPermissions($role->id, [$permission1->id]);

        $this->notSeeInDatabase('role_permission', ['role_id' => $role->id, 'permission_id' => $permission1->id]);
        $this->seeInDatabase('role_permission', ['role_id' => $role->id, 'permission_id' => $permission2->id]);
    }

//    public function testDelete()
//    {
//        $role = factory(Role::class)->create();
//        $permission1 = factory(Permission::class)->create();
//        $permission2 = factory(Permission::class)->create();
//
//        $this->getRoleManager()->attachPermissions($role->id, [$permission1->id, $permission2->id]);
//
//        $this->getRoleManager()->delete($role->id);
//
//        $this->notSeeInDatabase('role_permission', ['role_id' => $role->id, 'permission_id' => $permission1->id]);
//        $this->notSeeInDatabase('role_permission', ['role_id' => $role->id, 'permission_id' => $permission2->id]);
//        $this->notSeeInDatabase('roles', ['id' => $role->id]);
//    }

}