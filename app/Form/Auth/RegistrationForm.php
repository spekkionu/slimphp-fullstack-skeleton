<?php
namespace App\Form\Auth;

use App\Repository\UserRepository;
use Framework\Form\BaseForm;
use App\Validator\Database\EmailAddressIsUnique;

class RegistrationForm extends BaseForm
{
    public function __construct(UserRepository $repository)
    {
        $this->add(
            $this->createElement('name', 'text')
                 ->setRequired(true, 'Name is required')
                 ->toArray()
        );
        $this->add(
            $this->createElement('email', 'email')
                 ->setRequired(true, 'Email is required')
                 ->setMaxLength(255)
                 ->addEmailValidator()
                 ->addValidator(new EmailAddressIsUnique(
                     [
                         'repository' => $repository,
                         'exempt'     => null,
                         'break_chain_on_failure' => true,
                         'messages'   => [
                             'emailExists' => 'An account already exists with this email address.',
                         ],
                     ]
                 ))
                 ->toArray()
        );
        $this->add(
            $this->createElement('password', 'password')
                 ->setRequired(true, 'Password is required')
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
