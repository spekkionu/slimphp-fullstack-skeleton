<?php
namespace App\Controller\Account;

use Golem\Auth\Auth;

class AccountController
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * Class Constructor
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function dashboard()
    {
        $user = $this->auth->user();

        return view('account.dashboard', compact('user'));
    }
}
