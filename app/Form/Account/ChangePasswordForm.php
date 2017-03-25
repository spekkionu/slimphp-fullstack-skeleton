<?php
namespace App\Form\Account;

use App\Repository\UserRepository;
use Framework\Form\BaseForm;
use App\Validator\Database\CurrentPassword;
use Golem\Auth\Auth;

class ChangePasswordForm extends BaseForm
{
    /**
     * Class Constructor
     */
    public function __construct(UserRepository $repository, Auth $auth)
    {
        $this->add(
            $this->createElement('current', 'password')
                 ->setRequired(true, 'Current password is required')
                 ->setMaxLength(255)
                 ->addValidator(new CurrentPassword(
                     [
                         'repository'             => $repository,
                         'id'                     => $auth->getUserId(),
                         'break_chain_on_failure' => true,
                     ]
                 ))
                 ->toArray()
        );
        $this->add(
            $this->createElement('password', 'password')
                 ->setRequired(true, 'New password is required')
                 ->setMaxLength(255)
                 ->toArray()
        );
        $this->add(
            $this->createElement('password_confirm', 'password')
                 ->setRequired(true, 'Password confirmation is required')
                 ->addConfirmationValidator('password', 'Password does not match')
                 ->toArray()
        );
    }
}
