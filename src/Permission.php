<?php

namespace HuangYi\Rbac;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table('permissions');
    }

    /**
     * The users belonging to this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('rbac.user'), $this->table('permission_user'), 'permission_id', 'user_id');
    }

    /**
     * The roles belonging to this permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, $this->table('permission_role'), 'permission_id', 'role_id');
    }

    /**
     * Forget cache of the user belonging to.
     *
     * @return void
     */
    public function forgetUsersCache()
    {
        $userClass = config('rbac.user');

        $this->getConnection()
            ->table($this->table('permission_user'))
            ->where('permission_id', $this->id)
            ->pluck('user_id')
            ->each(function ($id) use ($userClass) {
                $userClass::forgetPermissionsCache($id);
            });

        $roles = $this->getConnection()
            ->table($this->table('permission_role'))
            ->where('permission_id', $this->id)
            ->pluck('role_id');

        if ($roles->isNotEmpty()) {
            $this->getConnection()
                ->table($this->table('role_user'))
                ->where('role_id', $this->id)
                ->pluck('user_id')
                ->each(function ($id) use ($userClass) {
                    $userClass::forgetRolesCache($id);
                });
        }
    }

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        if (config('rbac.cache')) {
            static::updated(function ($permission) {
                $permission->forgetUsersCache();
            });

            static::deleted(function ($permission) {
                $permission->forgetUsersCache();
            });
        }
    }

    /**
     * Make a new permission.
     *
     * @param  string  $name
     * @param  array  $attributes
     * @return static
     */
    public static function make(string $name, array $attributes = [])
    {
        $attributes['name'] = $name;

        return static::create($attributes);
    }
}
