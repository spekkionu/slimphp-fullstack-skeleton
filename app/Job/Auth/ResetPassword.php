<?php
namespace App\Job\Auth;

use App\Exception\PasswordResetNotFound;
use App\Exception\UserNotFound;
use App\Form\Auth\ResetPasswordForm;
use App\Repository\UserRepository;
use Golem\Auth\Auth;
use Illuminate\Database\Capsule\Manager;
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

class ResetPassword implements SelfExecutingCommand
{
    /**
     * @var string
     */
    private $token;
    /**
     * @var ResetPasswordForm
     */
    private $form;

    /**
     * Class Constructor
     *
     * @param string            $token
     * @param ResetPasswordForm $form
     */
    public function __construct(string $token, ResetPasswordForm $form)
    {
        $this->token = $token;
        $this->form  = $form;
    }

    /**
     * @param UserRepository $repository
     * @param Auth           $auth
     *
     * @return \App\Model\User|null
     * @throws PasswordResetNotFound
     * @throws UserNotFound
     */
    public function handle(UserRepository $repository, Auth $auth)
    {
        $now    = new \DateTime();
        $values = $this->form->getValues();
        $resets = Manager::table('password_resets')
                         ->where('email', '=', $values['email'])
                         ->where('expires_at', '>=', $now)
                         ->get();
        $reset  = $resets->first(function ($reset) {
            return password_verify($this->token, $reset->token);
        });
        if (!$reset) {
            throw new PasswordResetNotFound();
        }
        $user = $repository->findUserByEmail($values['email']);
        if (!$user) {
            Manager::table('password_resets')->where('expires_at', '<', $now)->orWhere('email', '=', $values['email'])->delete();
            throw new UserNotFound();
        }
        $user->password = password_hash($values['password'], PASSWORD_DEFAULT);
        $user->last_login = $now;
        $user->save();
        Manager::table('password_resets')->where('expires_at', '<', $now)->orWhere('email', '=', $user->email)->delete();
        $auth->login($user);

        return $user;
    }
}
