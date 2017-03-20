<?php
namespace Framework\Middleware;

use Symfony\Component\HttpFoundation\Session\Session;

class StartSession
{
    /**
     * @var Session
     */
    private $session;

    /**
     * RepopulateForm constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param  \Psr\Http\Message\ServerRequestInterface $request  PSR7 request
     * @param  \Psr\Http\Message\ResponseInterface      $response PSR7 response
     * @param  callable                                 $next     Next middleware
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        $response = $next($request, $response);

        if ($request->getMethod() === 'GET' && !$request->isXhr()) {
            $this->session->getFlashBag()->set('_referer', (string)$request->getUri());
        }

        return $response;
    }
}
