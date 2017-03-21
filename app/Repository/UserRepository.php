<?php
namespace App\Repository;

use App\Model\User;
use Golem\Auth\UserRepository as AuthRepository;

class UserRepository implements AuthRepository
{
    /**
     * @param int|string $id
     *
     * @return mixed
     */
    public function findUserById($id)
    {
        return User::findOrFail($id);
    }
}
