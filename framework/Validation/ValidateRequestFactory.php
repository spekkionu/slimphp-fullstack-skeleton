<?php
namespace Framework\Validation;

use Framework\Middleware\ValidateRequest;
use League\Container\Container;
use Symfony\Component\HttpFoundation\Session\Session;
use Zend\InputFilter\InputFilter;

class ValidateRequestFactory
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var ValidationManager
     */
    private $validation;
    /**
     * @var Container
     */
    private $container;

    /**
     * ValidateRequestFactory constructor.
     *
     * @param Container         $container
     * @param Session           $session
     * @param ValidationManager $validation
     */
    public function __construct(Container $container, Session $session, ValidationManager $validation)
    {
        $this->session = $session;
        $this->validation = $validation;
        $this->container = $container;
    }

    /**
     * @param InputFilter|string $form
     * @param array              $excluded
     *
     * @return ValidateRequest
     */
    public function __invoke($form, array $excluded = [])
    {
        return new ValidateRequest($this->container, $this->session, $this->validation, $form, $excluded);
    }
}
