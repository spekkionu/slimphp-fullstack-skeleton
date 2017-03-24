<?php
namespace App\Job\Account;

use App\Event\Account\UserChangedPassword;
use App\Model\User;
use Illuminate\Database\Capsule\Manager;
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

class ChangePassword implements SelfExecutingCommand
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var string
     */
    private $password;

    /**
     * Class Constructor
     *
     * @param User   $user
     * @param string $password
     */
    public function __construct(User $user, string $password)
    {
        $this->user     = $user;
        $this->password = $password;
    }

    /**
     * @return User
     */
    public function handle()
    {
        $this->user->update(['password' => password_hash($this->password, PASSWORD_DEFAULT)]);
        $now = new \DateTime();
        Manager::table('password_resets')->where('expires_at', '<', $now)->orWhere('email', '=',
            $this->user->email)->delete();
        logger()->info('User changed their password', ['user' => $this->user]);
        event(new UserChangedPassword($this->user));

        return $this->user;
    }
}
