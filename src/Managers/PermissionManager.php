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

use HuangYi\Rbac\Exceptions\PermissionNotFoundException;
use HuangYi\Rbac\Models\Permission;

class PermissionManager
{
    /**
     * @param array $attributes
     * @return \HuangYi\Rbac\Models\Permission
     */
    public function create(array $attributes)
    {
        return Permission::create($attributes);
    }

    /**
     * @param int $ID
     * @return bool
     * @throws \HuangYi\Rbac\Exceptions\PermissionNotFoundException
     */
    public function delete($ID)
    {
        $permission = $this->find($ID);

        $permission->syncRoles([]);

        return $permission->delete();
    }

    /**
     * @param int $ID
     * @param array $attributes
     * @return bool
     * @throws \HuangYi\Rbac\Exceptions\PermissionNotFoundException
     */
    public function update($ID, array $attributes)
    {
        $permission = $this->find($ID);

        return $permission->update($attributes);
    }

    /**
     * @param int $ID
     * @return \HuangYi\Rbac\Models\Permission
     * @throws \HuangYi\Rbac\Exceptions\PermissionNotFoundException
     */
    public function find($ID)
    {
        $permission = Permission::find($ID);

        if ( is_null($permission) ) {
            throw new PermissionNotFoundException;
        }

        return $permission;
    }

}
