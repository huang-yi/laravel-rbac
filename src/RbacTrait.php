<?php
/**
 * Copyright
 *
 * (c) Huang Yi <coodeer@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HuangYi\Rbac;

use HuangYi\Rbac\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RbacTrait extends Model
{

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role', 'user_id', 'role_id');
    }

    /**
     * @param $roles
     * @return bool
     */
    public function hasRole($roles)
    {
        if ( ! is_array($roles) ) {
            $roles = explode('|', $roles);
        }

        $userRoles = $this->roles->pluck('slug')->toArray();

        return ! empty(array_intersect($roles, $userRoles));
    }

    /**
     * @param $permissions
     * @return bool
     */
    public function hasPermission($permissions)
    {
        if ( ! is_array($permissions) ) {
            $permissions = explode('|', $permissions);
        }

        $userPermissions = [];

        foreach ( $this->roles as &$role ) {
            $rolePermissions = $role->permissions->pluck('slug')->toArray();
            $userPermissions = array_merge($userPermissions, $rolePermissions);
        }

        return ! empty(array_intersect($permissions, $userPermissions));
    }

}
