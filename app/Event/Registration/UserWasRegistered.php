<?php
namespace App\Event\Registration;

use App\Model\User;

class UserWasRegistered
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
