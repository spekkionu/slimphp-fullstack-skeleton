<?php
namespace App\Controller\Account;

use App\Form\Account\ChangePasswordForm;
use App\Job\Account\ChangePassword;
use Golem\Auth\Auth;
use Slim\Http\Request;

class PasswordController
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
    public function password()
    {
        return view('account.password');
    }

    /**
     * @param Request            $request
     * @param ChangePasswordForm $form
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function save(Request $request, ChangePasswordForm $form)
    {
        $form->setRequest($request);
        $user = $this->auth->user();
        dispatch(new ChangePassword($user, $form->getValue('password')));
        session()->getFlashBag()->add('success', 'Password has been updated');
        return redirect(route('account'));
    }
}
