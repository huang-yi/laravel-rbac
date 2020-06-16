<?php

namespace HuangYi\Rbac\Middleware;

use Closure;
use Illuminate\Auth\Access\AuthorizationException;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle($request, Closure $next, $roles)
    {
        $roles = explode('|', $roles);

        if (! auth()->user()->hasAnyRoles($roles)) {
            throw new AuthorizationException;
        }

        return $next($request);
    }
}
