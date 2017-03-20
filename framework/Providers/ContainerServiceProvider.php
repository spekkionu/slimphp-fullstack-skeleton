<?php
namespace Framework\Providers;

use Framework\Container\LaravelContainer;
use League\Container\ServiceProvider\AbstractServiceProvider;

class ContainerServiceProvider extends AbstractServiceProvider
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
            'Illuminate\Container\Container',
            'Illuminate\Contracts\Container\Container',
        ];

    public function register()
    {
        $this->getContainer()->share(
            'Illuminate\Container\Container',
            function () {
                return new LaravelContainer($this->getContainer());
            }
        );
        $this->getContainer()->add(
            'Illuminate\Contracts\Container\Container',
            function () {
                return $this->getContainer()->get('Illuminate\Container\Container');
            }
        );
    }
}
