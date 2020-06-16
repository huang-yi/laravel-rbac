<?php

namespace HuangYi\Rbac\Concerns;

use DateInterval;
use Illuminate\Support\Facades\Cache;

trait CacheRbac
{
    /**
     * The RBAC cache ttl.
     *
     * @var \DateTimeInterface|\DateInterval|int
     */
    protected static $rbacCacheTtl;

    /**
     * Load roles from cache.
     *
     * @return void
     */
    public function loadRolesFromCache()
    {
        if (! $this->relationLoaded('roles')) {
            Cache::remember(static::rbacCacheKey($this->getKey(), 'roles'), static::rbacCacheTtl(), function () {
                $roles = $this->roles;

                $roles->load('permissions');

                return $roles;
            });
        }
    }

    /**
     * Forget the roles cache.
     *
     * @param  int  $id
     * @return void
     */
    public static function forgetRolesCache($id)
    {
        Cache::forget(static::rbacCacheKey($id, 'roles'));
    }

    /**
     * Load permissions from cache.
     *
     * @return void
     */
    public function loadPermissionsFromCache()
    {
        if (! $this->relationLoaded('permissions')) {
            Cache::remember(static::rbacCacheKey($this->getKey(), 'permissions'), static::rbacCacheTtl(), function () {
                return $this->permissions;
            });
        }
    }

    /**
     * Forget the permissions cache.
     *
     * @param  int  $id
     * @return void
     */
    public static function forgetPermissionsCache($id)
    {
        Cache::forget(static::rbacCacheKey($id, 'permissions'));
    }

    /**
     * Wrap the cache key.
     *
     * @param  int  $id
     * @param  string  $type
     * @return string
     */
    public static function rbacCacheKey($id, $type)
    {
        $user = new static;

        return $user->getTable().':'.$id.':'.$type;
    }

    /**
     * Get the cache ttl.
     *
     * @return \DateTimeInterface|\DateInterval|int
     */
    public static function rbacCacheTtl()
    {
        return static::$rbacCacheTtl ?? DateInterval::createFromDateString('1 hour');
    }
}
