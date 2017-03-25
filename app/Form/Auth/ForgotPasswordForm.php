<?php
namespace App\Form\Auth;

use App\Repository\UserRepository;
use Framework\Form\BaseForm;
use App\Validator\Database\EmailAddressExists;

class ForgotPasswordForm extends BaseForm
{
    /**
     * Class Constructor
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->add(
            $this->createElement('email', 'email')
                 ->setRequired(true, 'Email is required')
                 ->addEmailValidator()
                 ->addValidator(new EmailAddressExists(
                     [
                         'repository' => $repository,
                         'break_chain_on_failure' => true,
                     ]
                 ))
                 ->toArray()
        );
    }
}
