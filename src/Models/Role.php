<?php
/**
 * Copyright
 *
 * (c) Huang Yi <coodeer@163.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HuangYi\Rbac\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'rbac';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('rbac.user'), 'user_role', 'user_id', 'role_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permission', 'permission_id', 'role_id');
    }

    /**
     * @param $permissionIDs
     */
    public function attachPermissions($permissionIDs)
    {
        $this->permissions()->attach($permissionIDs);
    }

    /**
     * @param $permissionIDs
     */
    public function detachPermissions($permissionIDs)
    {
        $this->permissions()->detach($permissionIDs);
    }

    /**
     * @param $permissionIDs
     * @return array
     */
    public function syncPermissions($permissionIDs)
    {
        return $this->permissions()->sync($permissionIDs);
    }

    /**
     * @param $permissionIDs
     * @return array
     */
    public function syncWithoutDetachingRoles($permissionIDs)
    {
        return $this->permissions()->syncWithoutDetaching($permissionIDs);
    }

    /**
     * @param $userIDs
     * @return array
     */
    public function syncUsers($userIDs)
    {
        return $this->users()->sync($userIDs);
    }

    /**
     * @param $userIDs
     * @return array
     */
    public function syncWithoutDetachingUsers($userIDs)
    {
        return $this->users()->syncWithoutDetaching($userIDs);
    }

}
