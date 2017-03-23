<?php
namespace App\Model;

use Golem\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Zend\Permissions\Acl\Role\RoleInterface;

class User extends Model implements Authenticatable, RoleInterface
{
    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_login'];

    /**
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * @return string|int
     */
    public function getAuthId()
    {
        return $this->getAttribute($this->getKeyName());
    }

    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        return $this->getAttribute('access_level');
    }
}
