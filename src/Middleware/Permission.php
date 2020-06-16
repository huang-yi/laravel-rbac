<?php

namespace HuangYi\Rbac\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissions
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle($request, Closure $next, $permissions)
    {
        $permissions = explode('|', $permissions);

        if (! auth()->user()->hasAnyPermissions($permissions)) {
            throw new AuthorizationException;
        }

        return $next($request);
    }
}
