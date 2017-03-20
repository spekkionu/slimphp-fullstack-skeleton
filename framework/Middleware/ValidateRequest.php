<?php
namespace Framework\Middleware;

use Illuminate\Support\MessageBag;
use League\Container\Container;
use Slim\Http\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Framework\Validation\ValidationManager;
use Zend\InputFilter\InputFilter;

class ValidateRequest
{
    /**
     * @var InputFilter|string
     */
    private $form;

    /**
     * @var array
     */
    private $excluded;

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
     * ValidateRequest constructor.
     *
     * @param Container          $container
     * @param Session            $session
     * @param ValidationManager  $validation
     * @param InputFilter|string $form
     * @param array              $excluded
     */
    public function __construct(
        Container $container,
        Session $session,
        ValidationManager $validation,
        $form,
        array $excluded = []
    ) {
        $this->form       = $form;
        $this->excluded   = $excluded;
        $this->session    = $session;
        $this->validation = $validation;
        $this->container  = $container;
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
        if (is_string($this->form)) {
            $form = $this->container->get($this->form, ['request' => $request]);
        } else {
            $form = $this->form;
        }
        if (!($form instanceof InputFilter)) {
            throw new \RuntimeException('Form must be an instance of Zend\InputFilter\InputFilter');
        }
        if ($_FILES) {
            $files = $_FILES;
            foreach ($files as $key => $file) {
                if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                    unset($files[$key]);
                }
            }
            $form->setData(
                array_merge($request->getParsedBody(), $files)
            );
        } else {
            $form->setData($request->getParsedBody());
        }
        if (method_exists($form, 'addOptionalValidators')) {
            $this->container->call([$form, 'addOptionalValidators'], ['request' => $request]);
        }
        if (!$form->isValid()) {
            $params = array_filter(
                $request->getParsedBody(), function ($field) {
                return !in_array($field, $this->excluded);
            }, ARRAY_FILTER_USE_KEY
            );

            if ($request->isXhr()) {
                $response = new Response();

                return $response->withJson($form->getMessages(), 422);
            } else {
                // Save input for next request
                $this->session->getFlashBag()->set('_old_input', $params);
                // Save errors for next request
                $this->session->getFlashBag()->set('_validation_errors', $form->getMessages());

                $this->validation->setErrors($form->getMessages());
                $this->validation->setOldInput($params);

                if ($request->hasHeader('referer')) {
                    $url = $request->getHeaderLine('referer');

                    $response = new Response();

                    return $response->withRedirect($url);
                } elseif ($this->session->getFlashBag()->has('_referer')) {
                    $url = $this->session->getFlashBag()->get('_referer');

                    $response = new Response();

                    return $response->withRedirect($url);
                } else {
                    $request = $request->withAttribute('validation_errors', new MessageBag($this->form->getMessages()));
                    $request = $request->withAttribute('old_input', $params);
                }
            }
        }

        return $next($request, $response);
    }
}
