<?php
namespace App\Event\Account;

use App\Model\User;

class UserChangedPassword
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
