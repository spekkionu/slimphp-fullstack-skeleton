<?php
namespace Framework\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\InvocationStrategyInterface;
use League\Container\ContainerInterface;

class ContainerDispatched implements InvocationStrategyInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * ContainerDispatched constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param callable               $callable
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     * @param array                  $routeArguments
     *
     * @return mixed
     */
    public function __invoke(
        callable $callable,
        ServerRequestInterface $request,
        ResponseInterface $response,
        array $routeArguments
    )
    {
        $routeArguments['request'] = $request;
        $routeArguments['response'] = $response;
        return $this->container->call($callable, $routeArguments);
    }
}
