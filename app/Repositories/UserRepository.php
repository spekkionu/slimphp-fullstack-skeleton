<?php
namespace App\Repositories;

use App\Models\User;
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
        return User::findOrFail(1);
    }
}
