<?php
namespace Framework\Form;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\InputFilter\InputFilter;

class BaseForm extends InputFilter
{
    /**
     * @param string $name
     * @param string $type
     *
     * @return $this
     */
    public function addField($name, $type = 'text')
    {
        $element = $this->createElement($name, $type);
        if ($type === 'email') {
            $element->addEmailValidator();
        }
        if ($type === 'date') {
            $element->addDateValidator();
        }
        $this->add($element->toArray());
        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $message
     *
     * @return InputFilter
     */
    public function addRequiredField(string $name, string $type = 'text', string $message = null)
    {
        $element = $this->createElement($name, $type);
        $element->setRequired(true, $message);
        if ($type === 'email') {
            $element->addEmailValidator();
        }
        if ($type === 'date') {
            $element->addDateValidator();
        }
        $this->add($element->toArray());
        return $this;
    }

    /**
     * @param string $name
     * @param string $type
     * @param string $class
     *
     * @return FormElement
     */
    public function createElement(string $name, string $type = 'text', $class = 'Zend\InputFilter\Input')
    {
        return new FormElement($name, $type, $class);
    }

    /**
     * @return array
     */
    public function getFormErrors()
    {
        $errors = [];
        $messages = $this->getMessages();
        foreach ($messages as $field => $errors) {
            $errors[$field] = array_shift($errors);
        }
        return $errors;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return $this
     */
    public function setRequest(ServerRequestInterface $request)
    {
        $this->setData(array_merge($request->getParsedBody(), $request->getUploadedFiles()));

        return $this;
    }
}
