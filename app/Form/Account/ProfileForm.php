<?php
namespace App\Form\Account;

use App\Repository\UserRepository;
use Framework\Form\BaseForm;
use Framework\Validation\Validator\Database\EmailAddressIsUnique;
use Golem\Auth\Auth;

class ProfileForm extends BaseForm
{
    /**
     * Class Constructor
     *
     * @param UserRepository $repository
     * @param Auth           $auth
     */
    public function __construct(UserRepository $repository, Auth $auth)
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
                         'exempt'     => $auth->getUserId(),
                         'break_chain_on_failure' => true
                     ]
                 ))
                 ->toArray()
        );
    }
}
