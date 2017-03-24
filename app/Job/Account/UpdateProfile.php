<?php
namespace App\Job\Account;

use App\Event\Account\UserProfileUpdated;
use App\Form\Account\ProfileForm;
use App\Model\User;
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

class UpdateProfile implements SelfExecutingCommand
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var ProfileForm
     */
    private $form;

    /**
     * Class Constructor
     *
     * @param User        $user
     * @param ProfileForm $form
     */
    public function __construct(User $user, ProfileForm $form)
    {
        $this->user = $user;
        $this->form = $form;
    }

    /**
     * @return User
     */
    public function handle()
    {
        $values = $this->form->getValues();
        $this->user->update($values);
        logger()->info('User profile updated', ['user' => $this->user]);
        event(new UserProfileUpdated($this->user));

        return $this->user;
    }
}
