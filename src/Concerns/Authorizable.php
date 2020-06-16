<?php

namespace HuangYi\Rbac\Concerns;

use HuangYi\Rbac\Permission;
use HuangYi\Rbac\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait Authorizable
{
    use CacheRbac;

    /**
     * The optimized roles.
     *
     * @var array
     */
    protected $optimizedRoles;

    /**
     * The optimized permissions.
     *
     * @var array
     */
    protected $optimizedPermissions;

    /**
     * The roles belonging to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * The permissions belonging to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id');
    }

    /**
     * Attach roles to the user.
     *
     * @param  mixed  $roles
     * @return void
     */
    public function attachRoles($roles)
    {
        $this->roles()->attach($roles);

        $this->forgetOptimizedRoles();
    }

    /**
     * Detach roles from the user.
     *
     * @param  mixed  $roles
     * @return void
     */
    public function detachRoles($roles)
    {
        $this->roles()->detach($roles);

        $this->forgetOptimizedRoles();
    }

    /**
     * Sync the user with a list of roles.
     *
     * @param  mixed  $roles
     * @param  bool  $detaching
     * @return void
     */
    public function syncRoles($roles, $detaching = true)
    {
        $this->roles()->sync($roles, $detaching);

        $this->forgetOptimizedRoles();
    }

    /**
     * Attach permissions to the user.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function attachPermissions($permissions)
    {
        $this->permissions()->attach($permissions);

        $this->forgetOptimizedPermissions();
    }

    /**
     * Detach permissions from the user.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function detachPermissions($permissions)
    {
        $this->permissions()->detach($permissions);

        $this->forgetOptimizedPermissions();
    }

    /**
     * Sync the user with a list of permissions.
     *
     * @param  mixed  $permissions
     * @param  bool  $detaching
     * @return void
     */
    public function syncPermissions($permissions, $detaching = true)
    {
        $this->permissions()->sync($permissions, $detaching);

        $this->forgetOptimizedPermissions();
    }

    /**
     * Determine if the permission has a role.
     *
     * @param  mixed  $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if (is_null($this->optimizedRoles)) {
            $this->optimizeRoles();
        }

        [$role, $key] = $this->parseRbacModel($role);

        return isset($this->optimizedRoles[$key][$role]);
    }

    /**
     * Determine if the permission has roles.
     *
     * @param  iterable  $roles
     * @return bool
     */
    public function hasRoles(iterable $roles): bool
    {
        if (is_null($this->optimizedRoles)) {
            $this->optimizeRoles();
        }

        foreach ($roles as $role) {
            [$role, $key] = $this->parseRbacModel($role);

            if (! isset($this->optimizedRoles[$key][$role])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the permission has any roles.
     *
     * @param  iterable  $roles
     * @return bool
     */
    public function hasAnyRoles(iterable $roles): bool
    {
        if (is_null($this->optimizedRoles)) {
            $this->optimizeRoles();
        }

        foreach ($roles as $role) {
            [$role, $key] = $this->parseRbacModel($role);

            if (isset($this->optimizedRoles[$key][$role])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the role has a permission.
     *
     * @param  mixed  $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if (is_null($this->optimizedPermissions)) {
            $this->optimizePermissions();
        }

        [$permission, $key] = $this->parseRbacModel($permission);

        return isset($this->optimizedPermissions[$key][$permission]);
    }

    /**
     * Determine if the role has permissions.
     *
     * @param  iterable  $permissions
     * @return bool
     */
    public function hasPermissions(iterable $permissions): bool
    {
        if (is_null($this->optimizedPermissions)) {
            $this->optimizePermissions();
        }

        foreach ($permissions as $permission) {
            [$permission, $key] = $this->parseRbacModel($permission);

            if (! isset($this->optimizedPermissions[$key][$permission])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the role has any permissions.
     *
     * @param  iterable  $permissions
     * @return bool
     */
    public function hasAnyPermissions(iterable $permissions): bool
    {
        if (is_null($this->optimizedPermissions)) {
            $this->optimizePermissions();
        }

        foreach ($permissions as $permission) {
            [$permission, $key] = $this->parseRbacModel($permission);

            if (isset($this->optimizedPermissions[$key][$permission])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Optimize the roles.
     *
     * @return void
     */
    protected function optimizeRoles()
    {
        if (config('rbac.cache')) {
            $this->loadRolesFromCache();
        }

        $this->optimizedRoles = [];

        foreach ($this->roles as $role) {
            $this->optimizedRoles['id'][$role->id] = true;
            $this->optimizedRoles['name'][$role->name] = true;
        }
    }

    /**
     * Forget the optimized roles.
     *
     * @return $this
     */
    protected function forgetOptimizedRoles()
    {
        $this->optimizedRoles = null;

        if (config('rbac.cache')) {
            static::forgetRolesCache($this->getKey());
        }

        return $this;
    }

    /**
     * Optimize the permissions.
     *
     * @return void
     */
    protected function optimizePermissions()
    {
        if (config('rbac.cache')) {
            $this->loadRolesFromCache();
            $this->loadPermissionsFromCache();
        }

        $this->optimizedPermissions = [];

        foreach ($this->permissions as $permission) {
            $this->optimizedPermissions['id'][$permission->id] = true;
            $this->optimizedPermissions['name'][$permission->name] = true;
        }

        if ($this->roles->isNotEmpty()) {
            if (! $this->roles->first()->relationLoaded('permissions')) {
                $this->roles->load('permissions');
            }

            foreach ($this->roles as $role) {
                foreach ($role->permissions as $permission) {
                    $this->optimizedPermissions['id'][$permission->id] = true;
                    $this->optimizedPermissions['name'][$permission->name] = true;
                }
            }
        }
    }

    /**
     * Forget the optimized permissions.
     *
     * @return $this
     */
    protected function forgetOptimizedPermissions()
    {
        $this->optimizedPermissions = null;

        if (config('rbac.cache')) {
            static::forgetRolesCache($this->getKey());
            static::forgetPermissionsCache($this->getKey());
        }

        return $this;
    }

    /**
     * Parse the RBAC model.
     *
     * @param  mixed  $model
     * @return array
     */
    protected function parseRbacModel($model)
    {
        if ($model instanceof Model) {
            return [$model->getKey(), 'id'];
        }

        return [$model, 'name'];
    }
}
