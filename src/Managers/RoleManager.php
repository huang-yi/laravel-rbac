<?php
/**
 * Copyright
 *
 * (c) Huang Yi <coodeer@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HuangYi\Rbac\Managers;

use HuangYi\Rbac\Exceptions\RoleNotFoundException;
use HuangYi\Rbac\Models\Role;

class RoleManager
{
    /**
     * @param array $attributes
     * @return Role
     */
    public function create(array $attributes)
    {
        return Role::create($attributes);
    }

    /**
     * @param $ID
     * @return bool
     * @throws \HuangYi\Rbac\Exceptions\RoleNotFoundException
     */
    public function delete($ID)
    {
        $role = $this->find($ID);

        $role->syncPermissions([]);
        $role->syncUsers([]);

        return $role->delete();
    }

    /**
     * @param $ID
     * @param array $attributes
     * @return bool
     * @throws \HuangYi\Rbac\Exceptions\RoleNotFoundException
     */
    public function update($ID, array $attributes)
    {
        $role = $this->find($ID);
        return $role->update($attributes);
    }

    /**
     * @param $ID
     * @return \HuangYi\Rbac\Models\Role
     * @throws \HuangYi\Rbac\Exceptions\RoleNotFoundException
     */
    public function find($ID)
    {
        $role = Role::find($ID);

        if ( is_null($role) ) {
            throw new RoleNotFoundException;
        }

        return $role;
    }

    /**
     * @param $ID
     * @param $permissionIDs
     * @throws \HuangYi\Rbac\Exceptions\RoleNotFoundException
     */
    public function attachPermissions($ID, $permissionIDs)
    {
        $role = $this->find($ID);
        $role->attachPermissions($permissionIDs);
    }

    /**
     * @param $ID
     * @param $permissionIDs
     * @throws \HuangYi\Rbac\Exceptions\RoleNotFoundException
     */
    public function detachPermissions($ID, $permissionIDs)
    {
        $role = $this->find($ID);
        $role->detachPermissions($permissionIDs);
    }

}
