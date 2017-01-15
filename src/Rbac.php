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

class Rbac
{
    /**
     * @var \HuangYi\Rbac\RbacTrait
     */
    protected $user;

    /**
     * Rbac constructor.
     * @param \HuangYi\Rbac\RbacTrait $user
     */
    public function __construct(RbacTrait $user = null)
    {
        $this->user = $user;
    }

    /**
     * @param \HuangYi\Rbac\RbacTrait $user
     */
    public function setUser(RbacTrait $user)
    {
        $this->user = $user;
    }

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->user, $name], $arguments);
    }

}
