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

use Closure;
use Illuminate\Contracts\Auth\Guard;

class RbacMiddleware
{
    /**
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * RbacMiddleware constructor.
     * @param \Illuminate\Contracts\Auth\Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @param $request
     * @param \Closure $next
     * @param $method
     * @param $slugs
     * @return mixed
     */
    public function handle($request, Closure $next, $method, $slugs)
    {
        if ( ! in_array($method, ['permission', 'role']) ) {
            return $next($request);
        }

        if ( $method == 'permission' ) {
            return $this->permission($request, $next, $slugs);
        }

        if ( $method == 'role' ) {
            return $this->role($request, $next, $slugs);
        }
    }

    /**
     * @param $request
     * @param \Closure $next
     * @param $slugs
     * @return mixed
     */
    protected function permission($request, Closure $next, $slugs)
    {
        if ( $this->auth->check() && $this->auth->user()->hasPermission($slugs) ) {
            return $next($request);
        }

        abort(403);
    }

    /**
     * @param $request
     * @param \Closure $next
     * @param $slugs
     * @return mixed
     */
    protected function role($request, Closure $next, $slugs)
    {
        if ( $this->auth->check() && $this->auth->user()->hasRole($slugs) ) {
            return $next($request);
        }

        abort(403);
    }

}
