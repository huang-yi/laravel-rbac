<?php
/**
 * File: User.php
 * Date: 2016/12/28
 * Time: 上午11:35
 */

namespace HuangYi\Rbac\Tests;

use HuangYi\Rbac\Models\Model;
use HuangYi\Rbac\RbacTrait;

class User extends Model
{
    use RbacTrait;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rbac_test_users';

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
    protected $fillable = ['name'];

}