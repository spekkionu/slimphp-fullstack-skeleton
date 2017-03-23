<?php
namespace App\Job\Auth;

use App\Event\Auth\UserHasLoggedIn;
use App\Exception\LoginCredentialsIncorrect;
use App\Exception\UserNotFound;
use App\Form\Auth\LoginForm;
use App\Repository\UserRepository;
use Golem\Auth\Auth;
use Illuminate\Database\Capsule\Manager;
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

class AttemptUserLogin implements SelfExecutingCommand
{
    /**
     * @var string
     */
    private $email;
    /**
     * @var string
     */
    private $password;

    /**
     * Class Constructor
     *
     * @param string $email
     * @param string $password
     */
    public function __construct(string $email, string $password)
    {
        $this->email    = $email;
        $this->password = $password;
    }

    /**
     * @param UserRepository $repository
     * @param Auth           $auth
     *
     * @return \App\Model\User
     * @throws LoginCredentialsIncorrect
     * @throws UserNotFound
     */
    public function handle(UserRepository $repository, Auth $auth)
    {
        $now = new \DateTime();
        $user = $repository->findUserByEmail($this->email);
        if (!$user) {
            logger()->info('Failed user login', [
                'reason' => 'Account not found',
                'email' => $this->email,
            ]);
            throw new UserNotFound();
        }
        if (!password_verify($this->password, $user->password)) {
            logger()->info('Failed user login', [
                'reason' => 'Bad password',
                'email' => $this->email,
            ]);
            throw new LoginCredentialsIncorrect();
        }
        if (password_needs_rehash($user->password, PASSWORD_DEFAULT)) {
            $user->password = password_hash($this->password, PASSWORD_DEFAULT);
        }

        $user->last_login = $now;
        $user->save();

        $auth->login($user);
        // Delete expired tokens
        Manager::table('password_resets')->where('expires_at', '<', $now)->orWhere('email', '=', $user->email)->delete();

        logger()->info('User has logged in', compact('user'));
        event(new UserHasLoggedIn($user));

        return $user;
    }
}
