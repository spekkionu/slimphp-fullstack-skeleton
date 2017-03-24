<?php
namespace Framework\Middleware\Authorization;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HasAccess
 *
 * @package App\Middleware\Authorization
 */
class HasAccess
{
    /**
     * @var string|null
     */
    private $resource;
    /**
     * @var string|null
     */
    private $privilege;

    /**
     * Class Constructor
     *
     * @param string|null $resource
     * @param string|null $privilege
     */
    public function __construct($resource = null, $privilege = null)
    {
        $this->resource  = $resource;
        $this->privilege = $privilege;
    }

    /**
     * Factory builder
     *
     * @param string|null $resource
     * @param string|null $privilege
     *
     * @return static
     */
    public static function getInstance($resource = null, $privilege = null)
    {
        return new static($resource, $privilege);
    }

    /**
     * Makes sure user has access
     *
     * @param  Request  $request  PSR7 request
     * @param  Response $response PSR7 response
     * @param  callable $next     Next middleware
     *
     * @return ResponseInterface
     */
    public function __invoke($request, $response, $next)
    {
        if (!has_access($this->resource, $this->privilege)) {
            if ($request->isXhr()) {
                return $response->withStatus(403, 'Access Denied');
            }
            if (has_identity()) {
                return view('error.access-denied', [], 403);
            }

            return redirect(route('login'));
        }

        return $next($request, $response);
    }
}
