<?php
/**
 * File: Model.php
 * Date: 2016/12/27
 * Time: 下午7:14
 */

namespace HuangYi\Rbac\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new Eloquent model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->connection = config('rbac.connection');
    }
}