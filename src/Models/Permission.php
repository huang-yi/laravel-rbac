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

class Permission extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

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
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission', 'permission_id', 'role_id');
    }

    /**
     * @param $roleIDs
     * @return array
     */
    public function syncRoles($roleIDs)
    {
        return $this->roles()->sync($roleIDs);
    }

    /**
     * @param $roleIDs
     * @return array
     */
    public function syncWithoutDetachingRoles($roleIDs)
    {
        return $this->roles()->syncWithoutDetaching($roleIDs);
    }

}
