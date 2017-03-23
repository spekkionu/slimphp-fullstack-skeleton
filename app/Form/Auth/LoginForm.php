<?php
namespace App\Form\Auth;

use Framework\Form\BaseForm;

class LoginForm extends BaseForm
{
    public function __construct()
    {
        $this->add(
            $this->createElement('email', 'email')
                 ->setRequired(true, 'Email is required')
                 ->addEmailValidator()
                 ->toArray()
        );
        $this->add(
            $this->createElement('password', 'password')
                 ->setRequired(true, 'Password is required')
                 ->toArray()
        );
    }
}
