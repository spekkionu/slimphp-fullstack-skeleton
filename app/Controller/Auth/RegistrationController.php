<?php
namespace App\Controller\Auth;

use App\Form\Auth\RegistrationForm;
use App\Job\Registration\RegisterUser;
use Golem\Auth\Auth;
use Slim\Http\Request;

class RegistrationController
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
    public function index()
    {
        return view('register.register');
    }

    /**
     * @param Request          $request
     * @param RegistrationForm $form
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function register(Request $request, RegistrationForm $form)
    {
        $form->setRequest($request);
        $user = dispatch(new RegisterUser($form));
        $this->auth->login($user);
        return redirect(route('account'));
    }
}
