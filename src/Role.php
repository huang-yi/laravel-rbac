<?php

namespace HuangYi\Rbac;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table('roles');
    }

    /**
     * The users belonging to this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(config('rbac.user'), $this->table('role_user'), 'role_id', 'user_id');
    }

    /**
     * The permissions belonging to this role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, $this->table('permission_role'), 'role_id', 'permission_id');
    }

    /**
     * Attach permissions to the role.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function attachPermissions($permissions)
    {
        $this->permissions()->attach($permissions);

        if (config('rbac.cache')) {
            $this->forgetUsersCache();
        }
    }

    /**
     * Detach permissions from the role.
     *
     * @param  mixed  $permissions
     * @return void
     */
    public function detachPermissions($permissions)
    {
        $this->permissions()->detach($permissions);

        if (config('rbac.cache')) {
            $this->forgetUsersCache();
        }
    }

    /**
     * Sync the role with a list of permissions.
     *
     * @param  mixed  $permissions
     * @param  bool  $detaching
     * @return void
     */
    public function syncPermissions($permissions, $detaching = true)
    {
        $this->permissions()->sync($permissions, $detaching);

        if (config('rbac.cache')) {
            $this->forgetUsersCache();
        }
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
            ->table($this->table('role_user'))
            ->where('role_id', $this->id)
            ->pluck('user_id')
            ->each(function ($id) use ($userClass) {
                $userClass::forgetRolesCache($id);
            });
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
            static::updated(function ($role) {
                $role->forgetUsersCache();
            });

            static::deleted(function ($role) {
                $role->forgetUsersCache();
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
