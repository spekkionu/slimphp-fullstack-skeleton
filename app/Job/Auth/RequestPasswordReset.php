<?php
namespace App\Job\Auth;

use App\Exception\UserNotFound;
use App\Mail\Auth\ForgotPasswordEmail;
use App\Repository\UserRepository;
use Carbon\Carbon;
use Framework\Mail\SendMail;
use Illuminate\Database\Capsule\Manager;
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

class RequestPasswordReset implements SelfExecutingCommand
{
    /**
     * @var string
     */
    private $email;

    /**
     * Class Constructor
     *
     * @param string $email
     */
    public function __construct(string $email)
    {
        $this->email = $email;
    }

    /**
     * @param UserRepository $repository
     *
     * @return string
     * @throws \RuntimeException
     * @throws UserNotFound
     */
    public function handle(UserRepository $repository)
    {
        $user = $repository->findUserByEmail($this->email);
        if (!$user) {
            throw new UserNotFound();
        }

        $now   = new \DateTime();
        $token = mb_strtolower(str_random(16));
        Manager::table('password_resets')->insert([
            'email'      => $this->email,
            'token'      => password_hash($token, PASSWORD_DEFAULT),
            'expires_at' => Carbon::parse('+1 hour'),
        ]);
        // Delete expired tokens
        Manager::table('password_resets')->where('expires_at', '<', $now)->delete();

        dispatch(new SendMail(new ForgotPasswordEmail($user, $token)));

        return $token;
    }
}
