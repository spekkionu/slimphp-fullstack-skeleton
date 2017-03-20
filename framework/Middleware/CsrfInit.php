<?php
namespace Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Framework\Csrf\CsrfManager;

class CsrfInit
{
    /**
     * @var CsrfManager
     */
    private $csrf;

    /**
     * CsrfInit constructor.
     *
     * @param CsrfManager $csrf
     */
    public function __construct(CsrfManager $csrf)
    {
        $this->csrf = $csrf;
    }

    /**
     * Clears last visited contest from session
     *
     * @param  ServerRequestInterface $request  PSR7 request
     * @param  ResponseInterface      $response PSR7 response
     * @param  callable               $next     Next middleware
     *
     * @return ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if (!$request->isXhr() && !$request->getHeader('X-Sub-Request')) {
            $token = $this->csrf->generateToken();

            $request = $request->withAttribute('csrf_name', $token['name']);
            $request = $request->withAttribute('csrf_value', $token['value']);
        }

        $response = $next($request, $response);

        $this->csrf->clearExpired();

        return $response;
    }

}
