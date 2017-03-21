<?php
namespace Framework\Handler;

use Exception;
use League\Container\ContainerAwareTrait;
use League\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Slim\Handlers\Error;

/**
 * Class ErrorHandler
 *
 * @package TalentWatch\Slim\Handler
 */
class ErrorHandler extends Error
{
    use LoggerAwareTrait;
    use ContainerAwareTrait;

    public function __construct($displayErrorDetails, LoggerInterface $logger, ContainerInterface $container)
    {
        parent::__construct($displayErrorDetails);
        $this->setLogger($logger);
        $this->setContainer($container);
    }


    /**
     * Invoke error handler
     *
     * @param ServerRequestInterface $request   The most recent Request object
     * @param ResponseInterface      $response  The most recent Response object
     * @param Exception              $exception The caught Exception object
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, Exception $exception)
    {
        $this->logger->error("Uncaught exception was thrown", ['exception' => $exception]);
        return parent::__invoke($request, $response, $exception);
    }

    /**
     * Render HTML error page
     *
     * @param  Exception $exception
     *
     * @return string
     */
    protected function renderHtmlErrorMessage(Exception $exception)
    {
        if ($this->displayErrorDetails) {
            return parent::renderHtmlErrorMessage($exception);
        }
        return $this->container->get('view')->render(
            $this->container->get('response'), 'error.error', [
                'message' => 'Application error',
                'exception' => $exception
            ]
        )->getBody()->__toString();
    }
}
