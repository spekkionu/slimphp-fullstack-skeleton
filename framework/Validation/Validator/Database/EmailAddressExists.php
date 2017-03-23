<?php
namespace Framework\Validation\Validator\Database;

use App\Repository\UserRepository;
use Zend\Validator\AbstractValidator;

class EmailAddressExists extends AbstractValidator
{
    const INVALID = 'emailInvalid';
    const STRING_EMPTY = 'emailStringEmpty';
    const DOES_NOT_EXIST = 'emailDoesNotExist';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates
        = [
            self::DOES_NOT_EXIST => "Account not found with this email address.",
            self::STRING_EMPTY   => "The input is an empty string",
            self::INVALID        => "Invalid type given. String, integer or float expected",
        ];

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var int|null
     */
    protected $exempt = null;

    /**
     * @param UserRepository $repository
     */
    public function setRepository(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param int|null $id
     */
    public function setExempt($id = null)
    {
        $this->exempt = $id;
    }

    /**
     * @inheritDoc
     */
    public function isValid($value)
    {
        if (!($this->repository instanceof UserRepository)) {
            throw new \Zend\Validator\Exception\RuntimeException('User repository not set');
        }
        if (!is_string($value)) {
            $this->error(self::INVALID);

            return false;
        }

        $this->setValue((string)$value);

        if ('' === $this->getValue()) {
            $this->error(self::STRING_EMPTY);

            return false;
        }

        if (!$this->repository->emailAddressExists($value, $this->exempt)) {
            $this->error(self::DOES_NOT_EXIST);

            return false;
        }

        return true;
    }
}
