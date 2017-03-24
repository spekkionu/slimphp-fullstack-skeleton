<?php
namespace App\Controller\Account;

use App\Form\Account\ProfileForm;
use App\Job\Account\UpdateProfile;
use Golem\Auth\Auth;
use Slim\Http\Request;

class ProfileController
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
    public function profile()
    {
        $user = $this->auth->user();

        return view('account.profile', compact('user'));
    }

    /**
     * @param Request     $request
     * @param ProfileForm $form
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function save(Request $request, ProfileForm $form)
    {
        $form->setRequest($request);
        $user = $this->auth->user();
        dispatch(new UpdateProfile($user, $form));
        session()->getFlashBag()->add('success', 'Profile has been updated');
        return redirect(route('account'));
    }
}
