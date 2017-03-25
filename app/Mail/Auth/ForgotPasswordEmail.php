<?php
namespace App\Mail\Auth;

use App\Model\User;
use Framework\Mail\Mail;

class ForgotPasswordEmail extends Mail
{
    /**
     * Class Constructor
     *
     * @param User   $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        $this->setTo([$user->email => $user->name]);
        $this->setSubject(config('app.name') .  ' - Password Reset');
        $this->setTemplate('email.auth.password');
        $this->setText('email.auth.password-plain');

        $this->setParams([
            'user' => $user,
            'token' => $token
        ]);
    }
}
