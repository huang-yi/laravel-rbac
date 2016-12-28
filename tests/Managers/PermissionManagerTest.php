<?php
/**
 * File: PermissionManagerTest.php
 * Date: 2016/12/28
 * Time: 上午9:28
 */

namespace HuangYi\Rbac\Tests\Managers;

use HuangYi\Rbac\Exceptions\PermissionNotFoundException;
use HuangYi\Rbac\Managers\PermissionManager;
use HuangYi\Rbac\Models\Permission;
use HuangYi\Rbac\Models\Role;
use HuangYi\Rbac\Tests\AbstractTestCase;

class PermissionManagerTest extends AbstractTestCase
{
    protected function getPermissionManager()
    {
        return new PermissionManager();
    }

    public function createTest()
    {
        $data = [
            'name' => 'Create Product',
            'slug' => 'product.create',
            'description' => 'Create a product.'
        ];

        $permission = $this->getPermissionManager()->create($data);

        $this->assertInstanceOf(Permission::class, $permission);
        $this->seeInDatabase('permissions', $data);
    }

    public function deleteTest()
    {
        $permission = factory(Permission::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $permission->roles()->attach([$role1->id, $role2->id]);

        $this->getPermissionManager()->delete($permission->id);

        $this->notSeeInDatabase('permissions', ['id' => $permission->id]);
        $this->notSeeInDatabase('role_permission', ['role_id' => $role1->id, 'permission_id' => $permission->id]);
        $this->notSeeInDatabase('role_permission', ['role_id' => $role2->id, 'permission_id' => $permission->id]);
    }

    public function updateTest()
    {
        $data = [
            'name' => 'Update Product',
            'slug' => 'product.update',
            'description' => 'Update a product.'
        ];

        $update = [
            'description' => 'Product Updated.'
        ];

        $permission = $this->getPermissionManager()->create($data);
        $saved = $this->getPermissionManager()->update($permission->getKey(), $update);

        $this->assertTrue($saved);
        $this->seeInDatabase('roles', array_merge($data, $update));
    }

    public function testFind()
    {
        $permission = factory(Permission::class)->create();

        $model = $this->getPermissionManager()->find($permission->id);

        $this->assertInstanceOf(Permission::class, $model);
    }

    public function testNotFound()
    {
        $this->expectException(PermissionNotFoundException::class);
        $this->getPermissionManager()->find(time());
    }

}
