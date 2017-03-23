<?php
namespace App\Controller\Auth;

use App\Form\Auth\ForgotPasswordForm;
use App\Form\Auth\ResetPasswordForm;
use App\Job\Auth\RequestPasswordReset;
use App\Job\Auth\ResetPassword;
use Slim\Http\Request;

class PasswordController
{
    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function index()
    {
        return view('auth.password');
    }

    public function send(Request $request, ForgotPasswordForm $form)
    {
        $form->setRequest($request);
        $token = dispatch(new RequestPasswordReset($form->getValue('email')));
        echo $token;
        exit;
        // ydfutlx1mhzzors2

        return redirect(route('login.password.submitted'));
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function submitted()
    {
        return view('auth.password-submitted');
    }

    /**
     * @param Request $request
     * @param string  $token
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function confirm(Request $request, string $token)
    {
        $email = $request->getQueryParam('email');

        return view('auth.password-confirm', compact('email', 'token'));
    }

    /**
     * @param Request           $request
     * @param ResetPasswordForm $form
     * @param string            $token
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function reset(Request $request, ResetPasswordForm $form, string $token)
    {
        $form->setRequest($request);
        try {
            dispatch(new ResetPassword($token, $form));
            return redirect(route('account'));
        } catch (\Exception $e) {
            session()->getFlashBag()->add('error', 'Invalid password reset.');
            return redirect(route('login.password.confirm', ['token' => $token], ['email' => $form->getValue('email')]));
        }

    }
}
