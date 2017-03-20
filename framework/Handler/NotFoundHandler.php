<?php
namespace Framework\Handler;

use Framework\View\Blade;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Handlers\NotFound;
use Slim\Http\Response;

class NotFoundHandler extends NotFound
{
    /**
     * @var Blade
     */
    private $view;

    /**
     * NotFoundHandler constructor.
     *
     * @param Blade $view
     */
    public function __construct(Blade $view)
    {
        $this->view = $view;
    }

    /**
     * Return a response for text/html content not found
     *
     * @param  ServerRequestInterface $request The most recent Request object
     *
     * @return ResponseInterface
     */
    protected function renderHtmlNotFoundOutput(ServerRequestInterface $request)
    {
        if ($this->view->getRenderer()->exists('error.404')) {
            $response = new Response();

            return $this->view->render($response->withStatus(404), 'error.404');
        }

        return parent::renderHtmlNotFoundOutput($request);
    }
}
