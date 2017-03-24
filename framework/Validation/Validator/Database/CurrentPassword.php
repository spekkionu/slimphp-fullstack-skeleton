<?php
namespace Framework\Validation\Validator\Database;

use App\Repository\UserRepository;
use Zend\Validator\AbstractValidator;

class CurrentPassword extends AbstractValidator
{
    const INVALID = 'passwordInvalid';
    const STRING_EMPTY = 'passwordStringEmpty';
    const PASSWORD_MISMATCH = 'passwordDoesNotMatch';

    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $messageTemplates
        = [
            self::PASSWORD_MISMATCH => "Current password does not match.",
            self::STRING_EMPTY      => "The input is an empty string",
            self::INVALID           => "Invalid type given. String, integer or float expected",
        ];

    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var int|string
     */
    protected $id;

    /**
     * @param UserRepository $repository
     */
    public function setRepository(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param string|int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    public function isValid($value)
    {
        if (!($this->repository instanceof UserRepository)) {
            throw new \Zend\Validator\Exception\RuntimeException('User repository not set');
        }
        if (!$this->id) {
            throw new \Zend\Validator\Exception\RuntimeException('User id not set');
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

        if (!$this->repository->passwordHashMatches($this->id, $value)) {
            $this->error(self::PASSWORD_MISMATCH);

            return false;
        }

        return true;
    }
}
