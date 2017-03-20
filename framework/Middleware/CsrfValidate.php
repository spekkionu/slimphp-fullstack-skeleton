<?php
namespace Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Http\Request;
use Slim\Http\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Framework\Csrf\CsrfManager;

class CsrfValidate
{
    /**
     * @var CsrfManager
     */
    private $csrf;
    /**
     * @var Session
     */
    private $session;

    /**
     * CsrfInit constructor.
     *
     * @param CsrfManager $csrf
     * @param Session     $session
     */
    public function __construct(CsrfManager $csrf, Session $session)
    {
        $this->csrf = $csrf;
        $this->session = $session;
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
        if (in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $token = $this->getToken($request);
            if (!isset($token['csrf_name'], $token['csrf_value'])) {
                return $this->sendFailedResponse($request, $response);
            }
            if(!$this->csrf->validateToken($token['csrf_name'], $token['csrf_value'])){
                return $this->sendFailedResponse($request, $response);
            }
            //$this->csrf->expireToken($token['csrf_name']);
        }

        return $next($request, $response);
    }

    /**
     * @param Request $request
     * @param Response      $response
     *
     * @return ResponseInterface
     */
    protected function sendFailedResponse(ServerRequestInterface $request, ResponseInterface $response)
    {
        if($request->isXhr()){
            return $response->withJson(['message' => 'CSRF check failed'], 403);
        }

        $this->session->getFlashBag()->set('_old_input', $request->getParsedBody());

        if ($request->hasHeader('referer')) {
            $url = $request->getHeaderLine('referer');
            return $response->withRedirect($url);
        } elseif ($this->session->getFlashBag()->has('_referer')) {
            $url = $this->session->getFlashBag()->get('_referer');
            return $response->withRedirect($url);
        } else {
            return view($response->withStatus(403), 'error.error');
        }


    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return array
     */
    protected function getToken(ServerRequestInterface $request){
        $params = $request->getParsedBody();
        if (isset($params['csrf_name'], $params['csrf_value'])) {
            return [
                'csrf_name' => $params['csrf_name'],
                'csrf_value' => $params['csrf_value'],
            ];
        }
        if($request->hasHeader('X-CSRF-NAME') && $request->hasHeader('X-CSRF-VALUE')){
            return [
                'csrf_name' => $request->getHeaderLine('X-CSRF-NAME'),
                'csrf_value' => $request->getHeaderLine('X-CSRF-VALUE'),
            ];
        }
        return [];
    }
}
