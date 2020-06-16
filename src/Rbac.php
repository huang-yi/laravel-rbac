<?php

namespace HuangYi\Rbac;

use Closure;
use HuangYi\Rbac\Contracts\Authorizable;

class Rbac
{
    /**
     * The super admin checker.
     *
     * @var \Closure
     */
    protected static $checkSuperAdminUsing;

    /**
     * Set the super admin checker.
     *
     * @param  \Closure  $callback
     * @return void
     */
    public static function checkSuperAdminUsing(Closure $callback)
    {
        static::$checkSuperAdminUsing = $callback;
    }

    /**
     * Determine if the user is a super admin.
     *
     * @param  \HuangYi\Rbac\Contracts\Authorizable  $user
     * @return bool
     */
    public static function isSuperAdmin(Authorizable $user)
    {
        if (static::$checkSuperAdminUsing) {
            return call_user_func(static::$checkSuperAdminUsing, $user);
        }

        return false;
    }
}
