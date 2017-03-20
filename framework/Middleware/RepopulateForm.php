<?php
namespace Framework\Middleware;

use Illuminate\Support\MessageBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Framework\Validation\ValidationManager;

class RepopulateForm
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
     * RepopulateForm constructor.
     *
     * @param Session           $session
     * @param ValidationManager $validation
     */
    public function __construct(Session $session, ValidationManager $validation)
    {
        $this->session = $session;
        $this->validation = $validation;
    }
    /**
     * Clears last visited contest from session
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        $errors = $this->session->getFlashBag()->get('_validation_errors');
        $this->validation->setErrors($errors);
        $request = $request->withAttribute('validation_errors', $this->validation->getErrors());


        $params = $this->session->getFlashBag()->get('_old_input');
        $this->validation->setOldInput($params);
        $request = $request->withAttribute('old_input', $params);

        $response = $next($request, $response);

        $this->session->getFlashBag()->set('_referer', (string) $request->getUri());

        return $response;
    }
}
