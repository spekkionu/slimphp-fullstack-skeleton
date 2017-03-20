<?php
namespace Framework\Validation;

use Illuminate\Support\MessageBag;

class ValidationManager
{
    /**
     * @var MessageBag
     */
    private $errors;

    /**
     * @var array
     */
    private $old;

    /**
     * ValidationManager constructor.
     *
     * @param array $errors
     * @param array $old
     */
    public function __construct(array $errors = [], array $old = [])
    {
        $this->setErrors($errors);
        $this->setOldInput($old);
    }

    /**
     * @param array $errors
     */
    public function setErrors(array $errors = [])
    {
        $this->errors = new MessageBag();
        foreach ($errors as $field => $messages) {
            if (is_string($messages)) {
                $messages = [$messages];
            }
            $messages = array_values(array_unique($messages));
            foreach ($messages as $message) {
                $this->errors->add($field, $message);
            }
        }
    }

    /**
     * @return MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $old
     */
    public function setOldInput(array $old = [])
    {
        $this->old = $old;
    }

    /**
     * @return array
     */
    public function getOldInput()
    {
        return $this->old;
    }
}
