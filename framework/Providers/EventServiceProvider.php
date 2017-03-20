<?php
namespace Framework\Providers;

use Illuminate\Events\Dispatcher;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends AbstractServiceProvider
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
            'event',
            'Illuminate\Events\Dispatcher',
            'Illuminate\Contracts\Events\Dispatcher'
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'Illuminate\Events\Dispatcher',
            function () {
                $container  = $this->getContainer()->get('Illuminate\Container\Container');
                $dispatcher = new Dispatcher($container);

                require_once APP_DIR . '/configs/events.php';

                return $dispatcher;
            }
        );

        $this->getContainer()->add(
            'Illuminate\Contracts\Events\Dispatcher',
            function () {
                return $this->getContainer()->get('Illuminate\Events\Dispatcher');
            }
        );

        $this->getContainer()->add(
            'event',
            function () {
                return $this->getContainer()->get('Illuminate\Events\Dispatcher');
            }
        );
    }
}
