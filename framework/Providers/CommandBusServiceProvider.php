<?php
namespace Framework\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Tactician\CommandBus;
use League\Tactician\Container\ContainerLocator;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleClassNameInflector;
use League\Tactician\Logger\Formatter\ClassNameFormatter;
use League\Tactician\Logger\LoggerMiddleware;
use PMG\Queue\Tactician\QueueingMiddleware;
use Spekkionu\Tactician\SelfExecuting\ContainerAwareSelfExecutionMiddleware;

/**
 * Class CommandBusProvider
 * @package App\Providers
 */
class CommandBusServiceProvider extends AbstractServiceProvider
{
    /**
     * This array allows the container to be aware of
     * what your service provider actually provides,
     * this should contain all alias names that
     * you plan to register with the container
     *
     * @var array
     */
    protected $provides
        = [
            'command',
            'League\Tactician\CommandBus',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'League\Tactician\CommandBus',
            function () {
                $commandToHandlerMap = require(APP_DIR . '/configs/commands.php');

                $containerLocator = new ContainerLocator(
                    $this->getContainer(),
                    $commandToHandlerMap
                );

                $handlerMiddleware = new CommandHandlerMiddleware(
                    new ClassNameExtractor(),
                    $containerLocator,
                    new HandleClassNameInflector()
                );

                /* @var \Monolog\Logger $monolog */
                $logger = $this->getContainer()->get('log.command');

                $middlewares = [
                    new LoggerMiddleware(
                        new ClassNameFormatter(),
                        $logger
                    ),
                    new ContainerAwareSelfExecutionMiddleware($this->getContainer()),
                    $handlerMiddleware,
                ];

                $config = $this->getContainer()->get('config');
                if ($config->get('queue.enabled')) {
                    $producer = $this->getContainer()->get('PMG\Queue\Producer');
                    $queueMiddleware = new QueueingMiddleware($producer);

                    array_unshift($middlewares, $queueMiddleware);
                }

                return new CommandBus($middlewares);
            }
        );

        $this->getContainer()->add(
            'command',
            function () {
                return $this->getContainer()->get('League\Tactician\CommandBus');
            }
        );
    }
}
