<?php
namespace App\Form\Auth;

use Framework\Form\BaseForm;

class ResetPasswordForm extends BaseForm
{
    /**
     * Class Constructor
     */
    public function __construct()
    {
        $this->add(
            $this->createElement('email', 'email')
                 ->setRequired(true, 'Email is required')
                 ->setMaxLength(255)
                 ->addEmailValidator()
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
