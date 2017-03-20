<?php
namespace Framework\Providers;

use Illuminate\Config\Repository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Class ConfigServiceProvider
 */
class ConfigServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
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
            'config',
            'Illuminate\Config\Repository',
            'Illuminate\Contracts\Config\Repository'
        ];

    public function boot()
    {
        $this->getContainer()->share(
            'Illuminate\Config\Repository',
            function () {
                return new Repository(require APP_DIR . '/configs/config.php');
            }
        );
    }

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->add(
            'Illuminate\Contracts\Config\Repository',
            function () {
                return $this->getContainer()->get('Illuminate\Config\Repository');
            }
        );

        $this->getContainer()->add(
            'config',
            function () {
                return $this->getContainer()->get('Illuminate\Config\Repository');
            }
        );
    }
}
