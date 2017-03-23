<?php
namespace App\Controller\Auth;

use App\Form\Auth\LoginForm;
use App\Job\Auth\AttemptUserLogin;
use Golem\Auth\Auth;
use Slim\Http\Request;

class LoginController
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
        return view('auth.login');
    }

    /**
     * @param Request   $request
     * @param LoginForm $form
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function login(Request $request, LoginForm $form)
    {
        $form->setRequest($request);
        try {
            dispatch(new AttemptUserLogin($form->getValue('email'), $form->getValue('password')));

            return redirect(route('account'));
        } catch (\Exception $e) {
            session()->getFlashBag()->add('error', 'Account not found with matching credentials.');
            session()->getFlashBag()->set('_old_input', ['email' => $form->getValue('email')]);

            return redirect(route('login'));
        }
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function logout()
    {
        $this->auth->logout();

        return redirect(route('login'));
    }
}
