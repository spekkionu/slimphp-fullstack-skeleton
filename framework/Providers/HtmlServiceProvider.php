<?php
namespace Framework\Providers;

use Aura\Html\EscaperFactory;
use Aura\Html\HelperLocatorFactory;
use League\Container\ServiceProvider\AbstractServiceProvider;

class HtmlServiceProvider extends AbstractServiceProvider
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
            'Aura\Html\Escaper',
            'Aura\Html\HelperLocator',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'Aura\Html\Escaper',
            function () {
                $factory = new EscaperFactory();
                return $factory->newInstance();
            }
        );
        $this->getContainer()->share(
            'Aura\Html\HelperLocator',
            function () {
                $factory = new HelperLocatorFactory();
                return $factory->newInstance();
            }
        );

    }
}
