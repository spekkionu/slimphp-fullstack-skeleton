<?php
namespace Framework\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\IntrospectionProcessor;
use Monolog\Processor\WebProcessor;

/**
 * Class LoggerServiceProvider
 * @package App\Providers
 */
class LoggerServiceProvider extends AbstractServiceProvider
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
            'logger', 'Monolog\Logger', 'log.command', 'log.queue'
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'Monolog\Logger',
            function () {
                return $this->buildLogger(
                    'activity',
                    STORAGE_DIR . '/logs/activity.log',
                    STORAGE_DIR . '/logs/error.log'
                );
            }
        );
        $this->getContainer()->share(
            'log.command',
            function () {
                return $this->buildLogger(
                    'command',
                    STORAGE_DIR . '/logs/command.log'
                );
            }
        );
        $this->getContainer()->share(
            'log.queue',
            function () {
                return $this->buildLogger(
                    'queue',
                    STORAGE_DIR . '/logs/queue.log'
                );
            }
        );
        $this->getContainer()->share(
            'logger',
            function () {
                return $this->getContainer()->get('Monolog\Logger');
            }
        );
    }

    /**
     * Builds monolog logger
     *
     * @param string      $name         The log name
     * @param string      $logFile      Path to log file
     * @param string|null $errorLogFile Path to log file for errors
     *
     * @return Logger
     */
    protected function buildLogger($name, $logFile, $errorLogFile = null)
    {
        $monolog = new Logger($name);
        $handler = new StreamHandler($logFile, Logger::DEBUG);
        $formatter = new LineFormatter(null, null, false, true);
        $formatter->includeStacktraces(true);
        $handler->setFormatter($formatter);
        $monolog->pushHandler($handler);

        if ($errorLogFile) {
            $errorHandler = new StreamHandler($errorLogFile, Logger::ERROR, false);
            $errorHandler->setFormatter($formatter);
            $monolog->pushHandler($errorHandler);
        }

        $webprocessor = new WebProcessor();
        $monolog->pushProcessor($webprocessor);

        $introspectionProcessor = new IntrospectionProcessor(Logger::ERROR);
        $monolog->pushProcessor($introspectionProcessor);

        $monolog->pushProcessor(
            function ($record) {

                return $record;
            }
        );

        return $monolog;
    }
}
