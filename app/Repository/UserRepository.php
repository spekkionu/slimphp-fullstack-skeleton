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

    /**
     * @param string $email
     * @param null   $exempt
     *
     * @return bool
     */
    public function emailAddressExists(string $email, $exempt = null)
    {
        $query = User::where('email', 'LIKE', $email);
        if ($exempt) {
            $query->where('id', '!=', $exempt);
        }

        return $query->count() > 0;
    }

    /**
     * @param string $email
     *
     * @return User|null
     */
    public function findUserByEmail(string $email)
    {
        $query = User::where('email', 'LIKE', $email);

        return $query->first();
    }

    /**
     * @param string|int $id
     * @param string     $password
     *
     * @return bool
     */
    public function passwordHashMatches($id, string $password)
    {
        $user = User::find($id);
        if (!$user) {
            return false;
        }

        return password_verify($password, $user->password);
    }
}
