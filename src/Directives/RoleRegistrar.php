<?php

namespace HuangYi\Rbac\Directives;

class RoleRegistrar extends DirectivesRegistrar
{
    /**
     * The list of directives.
     *
     * @var array
     */
    protected $directives = [
        'role', 'elserole', 'endrole',
        'notrole', 'elsenotrole', 'endnotrole',
        'roles', 'elseroles', 'endroles',
        'anyroles', 'elseanyroles', 'endanyroles',
    ];

    /**
     * The "role" directive.
     *
     * @param  string  $role
     * @return string
     */
    public function role($role)
    {
        return "<?php if(auth()->user()->hasRole({$role})): ?>";
    }

    /**
     * The "elserole" directive.
     *
     * @param  string  $role
     * @return string
     */
    public function elserole($role)
    {
        return "<?php elseif(auth()->user()->hasRole({$role})): ?>";
    }

    /**
     * The "endrole" directive.
     *
     * @return string
     */
    public function endrole()
    {
        return "<?php endif; ?>";
    }

    /**
     * The "notrole" directive.
     *
     * @param  string  $role
     * @return string
     */
    public function notrole($role)
    {
        return "<?php if(! auth()->user()->hasRole({$role})): ?>";
    }

    /**
     * The "elsenotrole" directive.
     *
     * @param  string  $role
     * @return string
     */
    public function elsenotrole($role)
    {
        return "<?php elseif(! auth()->user()->hasRole({$role})): ?>";
    }

    /**
     * The "endnotrole" directive.
     *
     * @return string
     */
    public function endnotrole()
    {
        return "<?php endif; ?>";
    }

    /**
     * The "roles" directive.
     *
     * @param  string  $roles
     * @return string
     */
    public function roles($roles)
    {
        return "<?php if(auth()->user()->hasRoles(explode('&', {$roles}))): ?>";
    }

    /**
     * The "elseroles" directive.
     *
     * @param  string  $roles
     * @return string
     */
    public function elseroles($roles)
    {
        return "<?php elseif(auth()->user()->hasRoles(explode('&', {$roles}))): ?>";
    }

    /**
     * The "endroles" directive.
     *
     * @return string
     */
    public function endroles()
    {
        return "<?php endif; ?>";
    }

    /**
     * The "anyroles" directive.
     *
     * @param  string  $roles
     * @return string
     */
    public function anyroles($roles)
    {
        return "<?php if(auth()->user()->hasAnyRoles(explode('|', {$roles}))): ?>";
    }

    /**
     * The "elseanyroles" directive.
     *
     * @param  string  $roles
     * @return string
     */
    public function elseanyroles($roles)
    {
        return "<?php elseif(auth()->user()->hasAnyRoles(explode('|', {$roles}))): ?>";
    }

    /**
     * The "endanyroles" directive.
     *
     * @return string
     */
    public function endanyroles()
    {
        return "<?php endif; ?>";
    }
}
