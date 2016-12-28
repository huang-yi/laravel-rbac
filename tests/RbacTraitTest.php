<?php
/**
 * File: RbacTraitTest.php
 * Date: 2016/12/28
 * Time: 上午11:48
 */

namespace HuangYi\Rbac\Tests;

use HuangYi\Rbac\Models\Permission;
use HuangYi\Rbac\Models\Role;

class RbacTraitTest extends AbstractTestCase
{
    public function testAttachRoles()
    {
        /** @var User $user */
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user->attachRoles([$role1->id, $role2->id]);

        $this->seeInDatabase('user_role', ['user_id' => $user->id, 'role_id' => $role1->id]);
        $this->seeInDatabase('user_role', ['user_id' => $user->id, 'role_id' => $role2->id]);
    }

    public function testDetachRoles()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user->attachRoles([$role1->id, $role2->id]);

        $user->detachRoles([$role1->id]);

        $this->notSeeInDatabase('user_role', ['user_id' => $user->id, 'role_id' => $role1->id]);
        $this->seeInDatabase('user_role', ['user_id' => $user->id, 'role_id' => $role2->id]);
    }

    public function testHasRole()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();
        $role3 = factory(Role::class)->create();

        $user->attachRoles([$role1->id, $role2->id]);

        $hasRole1 = $user->hasRole($role1->slug);
        $hasRole2 = $user->hasRole($role2->slug);
        $hasRole3 = $user->hasRole($role3->slug);
        $hasRole1OrRole3 = $user->hasRole($role1->slug.'|'.$role3->slug);

        $this->assertTrue($hasRole1);
        $this->assertTrue($hasRole2);
        $this->assertFalse($hasRole3);
        $this->assertTrue($hasRole1OrRole3);
    }

    public function testHasPermission()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();
        $permission1 = factory(Permission::class)->create();
        $permission2 = factory(Permission::class)->create();
        $permission3 = factory(Permission::class)->create();
        $permission4 = factory(Permission::class)->create();

        $role1->attachPermissions([$permission1->id, $permission2->id]);
        $role2->attachPermissions([$permission3->id, $permission4->id]);

        $user->attachRoles($role1->id);

        $hasPermission1 = $user->hasPermission($permission1->slug);
        $hasPermission2 = $user->hasPermission($permission2->slug);
        $hasPermission3 = $user->hasPermission($permission3->slug);
        $hasPermission4 = $user->hasPermission($permission4->slug);
        $hasPermission1OrPermission3 = $user->hasPermission($permission1->slug.'|'.$permission3->slug);
        $hasPermission3OrPermission4 = $user->hasPermission($permission3->slug.'|'.$permission4->slug);

        $this->assertTrue($hasPermission1);
        $this->assertTrue($hasPermission2);
        $this->assertFalse($hasPermission3);
        $this->assertFalse($hasPermission4);
        $this->assertTrue($hasPermission1OrPermission3);
        $this->assertFalse($hasPermission3OrPermission4);
    }
}