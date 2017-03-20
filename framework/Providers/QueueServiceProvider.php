<?php
namespace Framework\Providers;

use Illuminate\Config\Repository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Pheanstalk\Pheanstalk;
use PMG\Queue\DefaultProducer;
use PMG\Queue\Driver;
use PMG\Queue\Driver\PheanstalkDriver;
use PMG\Queue\Handler\TacticianHandler;
use PMG\Queue\Retry\LimitedSpec;
use PMG\Queue\Retry\NeverSpec;
use PMG\Queue\Router\SimpleRouter;
use PMG\Queue\Serializer\NativeSerializer;
use Framework\Queue\Consumer;
use PMG\Queue\Serializer\Serializer;
use Spekkionu\PMG\Queue\Iron\Driver\IronDriver;
use Spekkionu\PMG\Queue\Sqs\Driver\SqsDriver;

/**
 * Class QueueServiceProvider
 * @package DigitalCanvas\ServiceProvider
 */
class QueueServiceProvider extends AbstractServiceProvider
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
            'PMG\Queue\Consumer',
            'PMG\Queue\Producer',
            'PMG\Queue\Driver',
            'PMG\Queue\Router',
            'PMG\Queue\Serializer\Serializer',
            'PMG\Queue\MessageHandler',
            'PMG\Queue\RetrySpec',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $config = $this->getContainer()->get('config');

        $this->getContainer()->share(
            'PMG\Queue\Producer',
            function () {

                $driver = $this->getContainer()->get('PMG\Queue\Driver');
                $router = $this->getContainer()->get('PMG\Queue\Router');

                return new DefaultProducer($driver, $router);
            }
        );


        $this->getContainer()->share(
            'PMG\Queue\Router',
            function () use ($config) {

                return new SimpleRouter($config->get('queue.name'));
            }
        );

        $this->getContainer()->share(
            'PMG\Queue\Serializer\Serializer',
            function () use ($config){
                return new NativeSerializer($config->get('queue.key'));
            }
        );

        $this->getContainer()->share(
            'PMG\Queue\Driver',
            function () use ($config) {
                return $this->getDriver($config, $this->getContainer()->get('PMG\Queue\Serializer\Serializer'));
            }
        );

        $this->getContainer()->share(
            'PMG\Queue\MessageHandler',
            function () {
                return new TacticianHandler($this->getContainer()->get('League\Tactician\CommandBus'));
            }
        );

        $this->getContainer()->share(
            'PMG\Queue\RetrySpec',
            function () use ($config) {
                if ($config->queue->retry) {
                    return new LimitedSpec($config->queue->retry);
                } else {
                    new NeverSpec();
                }
            }
        );

        $this->getContainer()->share(
            'PMG\Queue\Consumer',
            function () {
                return new Consumer(
                    $this->getContainer()->get('PMG\Queue\Driver'),
                    $this->getContainer()->get('PMG\Queue\MessageHandler'),
                    $this->getContainer()->get('PMG\Queue\RetrySpec'),
                    $this->getContainer()->get('log.queue')
                );
            }
        );
    }

    /**
     * @param Repository $config
     * @param Serializer $serializer
     *
     * @return Driver
     */
    protected function getDriver(Repository $config, $serializer)
    {
        if ($config->get('queue.driver') === 'beanstalkd') {
            return $this->setupBeanstalkd($config, $serializer);
        }
        if ($config->get('queue.driver') === 'iron') {
            return $this->setupIron($config, $serializer);
        }
        if ($config->get('queue.driver') === 'sqs') {
            return $this->setupAwsSQS($config, $serializer);
        }
    }

    /**
     * @param Repository $config
     * @param Serializer $serializer
     *
     * @return PheanstalkDriver
     */
    protected function setupBeanstalkd(Repository $config, $serializer)
    {
        $beanstalked = new Pheanstalk(
            $config->get('queue.beanstalkd.server'),
            $config->get('queue.beanstalkd.port')
        );
        $driver = new PheanstalkDriver(
            $beanstalked,
            $serializer,
            [
                // how long easy message has to execute in seconds
                'ttr'             => 100,

                // the "priority" of the message. High priority messages are
                // consumed first.
                'priority'        => 1024,

                // The delay between inserting the message and when it
                // becomes available for consumption
                'delay'           => 0,

                // The ttr for retries jobs
                'retry-ttr'       => 100,

                // the priority for retried jobs
                'retry-priority'  => 1024,

                // the delay for retried jobs
                'retry-delay'     => 0,

                // When jobs fail, they are "burieds" in beanstalkd with this priority
                'fail-priority'   => 1024,

                // A call to `dequeue` blocks for this number of seconds. A zero or
                // falsy value will block until a job becomes available
                'reserve-timeout' => 10,
            ]
        );

        return $driver;
    }

    /**
     * @param Repository $config
     * @param Serializer $serializer
     *
     * @return IronDriver
     */
    protected function setupIron(Repository $config, $serializer)
    {
        $iron = $this->getContainer()->get('IronMQ\IronMQ');
        return new IronDriver($iron, $serializer);
    }

    /**
     * @param Repository $config
     * @param Serializer $serializer
     *
     * @return SqsDriver
     */
    protected function setupAwsSQS(Repository $config, $serializer)
    {
        $sqsClient = $this->getContainer()->get('Aws\Sqs\SqsClient');
        return new SqsDriver($sqsClient, $serializer);
    }
}
