<?php
namespace App\Event\Auth;

use App\Model\User;

class UserHasLoggedIn
{
    /**
     * @var User
     */
    public $user;

    /**
     * Class Constructor
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}
