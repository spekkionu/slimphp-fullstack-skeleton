<?php
namespace Framework\Middleware\Authorization;

use Golem\Auth\Auth;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class Authorized
{
    /**
     * @var Auth
     */
    private $auth;

    /**
     * Authorized constructor.
     *
     * @param Auth $auth
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Makes sure user is logged in
     *
     * @param  Request  $request  PSR7 request
     * @param  Response $response PSR7 response
     * @param  callable $next     Next middleware
     *
     * @return ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if (!$this->auth->loggedIn()) {
            if ($request->isXhr()) {
                return $response->withStatus(403, 'Access Denied');
            }
            $url = route('login');

            return $response->withRedirect($url);
        }

        return $next($request, $response);
    }
}
