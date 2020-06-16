<?php

namespace HuangYi\Rbac\Contracts;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

interface Authorizable
{
    /**
     * The roles belonging to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany;

    /**
     * The permissions belonging to this user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany;

    /**
     * Attach roles to the user.
     *
     * @param  mixed  $roles
     * @return void
     */
    public function attachRoles($roles);

    /**
     * Detach roles from the user.
     *
     * @param  mixed  $roles
     * @return void
     */
    public function detachRoles($roles);

    /**
     * Sync the user with a list of roles.
     *
     * @param  mixed  $roles
     * @param  bool  $detaching
     * @return void
     */
    public function syncRoles($roles, $detaching = true);

    /**
     * Attach permissions to the user.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function attachPermissions($permissions);

    /**
     * Detach permissions from the user.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function detachPermissions($permissions);

    /**
     * Sync the user with a list of permissions.
     *
     * @param  mixed  $permissions
     * @param  bool  $detaching
     * @return void
     */
    public function syncPermissions($permissions, $detaching = true);

    /**
     * Determine if the permission has a role.
     *
     * @param  mixed  $role
     * @return bool
     */
    public function hasRole($role): bool;

    /**
     * Determine if the permission has roles.
     *
     * @param  iterable  $roles
     * @return bool
     */
    public function hasRoles(iterable $roles): bool;

    /**
     * Determine if the permission has any roles.
     *
     * @param  iterable  $roles
     * @return bool
     */
    public function hasAnyRoles(iterable $roles): bool;

    /**
     * Determine if the role has a permission.
     *
     * @param  mixed  $permission
     * @return bool
     */
    public function hasPermission($permission): bool;

    /**
     * Determine if the role has permissions.
     *
     * @param  iterable  $permissions
     * @return bool
     */
    public function hasPermissions(iterable $permissions): bool;

    /**
     * Determine if the role has any permissions.
     *
     * @param  iterable  $permissions
     * @return bool
     */
    public function hasAnyPermissions(iterable $permissions): bool;
}
