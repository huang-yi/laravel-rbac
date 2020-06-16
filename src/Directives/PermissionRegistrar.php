<?php

namespace HuangYi\Rbac\Directives;

class PermissionRegistrar extends DirectivesRegistrar
{
    /**
     * The list of directives.
     *
     * @var array
     */
    protected $directives = [
        'permission', 'elsepermission', 'endpermission',
        'notpermission', 'elsenotpermission', 'endnotpermission',
        'permissions', 'elsepermissions', 'endpermissions',
        'anypermissions', 'elseanypermissions', 'endanypermissions',
    ];

    /**
     * The "permission" directive.
     *
     * @param  string  $permission
     * @return string
     */
    public function permission($permission)
    {
        return "<?php if(auth()->user()->hasPermission({$permission})): ?>";
    }

    /**
     * The "elsepermission" directive.
     *
     * @param  string  $permission
     * @return string
     */
    public function elsepermission($permission)
    {
        return "<?php elseif(auth()->user()->hasPermission({$permission})): ?>";
    }

    /**
     * The "endpermission" directive.
     *
     * @return string
     */
    public function endpermission()
    {
        return "<?php endif; ?>";
    }

    /**
     * The "notpermission" directive.
     *
     * @param  string  $permission
     * @return string
     */
    public function notpermission($permission)
    {
        return "<?php if(! auth()->user()->hasPermission({$permission})): ?>";
    }

    /**
     * The "elsenotpermission" directive.
     *
     * @param  string  $permission
     * @return string
     */
    public function elsenotpermission($permission)
    {
        return "<?php elseif(! auth()->user()->hasPermission({$permission})): ?>";
    }

    /**
     * The "endnotpermission" directive.
     *
     * @return string
     */
    public function endnotpermission()
    {
        return "<?php endif; ?>";
    }

    /**
     * The "permissions" directive.
     *
     * @param  string  $permissions
     * @return string
     */
    public function permissions($permissions)
    {
        return "<?php if(auth()->user()->hasPermissions(explode('&', {$permissions}))): ?>";
    }

    /**
     * The "elsepermissions" directive.
     *
     * @param  string  $permissions
     * @return string
     */
    public function elsepermissions($permissions)
    {
        return "<?php elseif(auth()->user()->hasPermissions(explode('&', {$permissions}))): ?>";
    }

    /**
     * The "endpermissions" directive.
     *
     * @return string
     */
    public function endpermissions()
    {
        return "<?php endif; ?>";
    }

    /**
     * The "anypermissions" directive.
     *
     * @param  string  $permissions
     * @return string
     */
    public function anypermissions($permissions)
    {
        return "<?php if(auth()->user()->hasAnyPermissions(explode('|', {$permissions}))): ?>";
    }

    /**
     * The "elseanypermissions" directive.
     *
     * @param  string  $permissions
     * @return string
     */
    public function elseanypermissions($permissions)
    {
        return "<?php elseif(auth()->user()->hasAnyPermissions(explode('|', {$permissions}))): ?>";
    }

    /**
     * The "endanypermissions" directive.
     *
     * @return string
     */
    public function endanypermissions()
    {
        return "<?php endif; ?>";
    }
}
