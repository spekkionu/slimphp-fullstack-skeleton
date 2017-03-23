<?php
namespace App\Job\Registration;

use App\Event\Registration\UserWasRegistered;
use App\Form\Auth\RegistrationForm;
use App\Model\User;
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

class RegisterUser implements SelfExecutingCommand
{
    /**
     * @var RegistrationForm
     */
    private $form;

    /**
     * Class Constructor
     *
     * @param RegistrationForm $form
     */
    public function __construct(RegistrationForm $form)
    {
        $this->form = $form;
    }

    /**
     * @return User
     */
    public function handle()
    {
        $values = $this->form->getValues();
        $user = new User();
        $user->name = $values['name'];
        $user->email = $values['email'];
        $user->password = password_hash($values['password'], PASSWORD_DEFAULT);
        $user->last_login = new \DateTime();
        $user->save();

        logger()->info('User has registered', compact('user'));

        event(new UserWasRegistered($user));

        return $user;
    }
}
