<?php
namespace App\Models;

use Golem\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Zend\Permissions\Acl\Role\RoleInterface;

class User extends Model implements Authenticatable, RoleInterface
{

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
