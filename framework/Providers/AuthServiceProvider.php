<?php
namespace Framework\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;

class AuthServiceProvider extends AbstractServiceProvider
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
            'auth', 'Golem\Auth', 'Golem\Auth\Storage\StorageInterface'
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {

        $this->getContainer()->share(
            'Golem\Auth',
            function () {
                $userRepository = $this->getContainer()->get('App\Repositories\UserRepository');
                $storage = $this->getContainer()->get('Golem\Auth\Storage\StorageInterface');
                return new \Golem\Auth\Auth($storage, $userRepository);
            }
        );
        $this->getContainer()->share(
            'Golem\Auth\Storage\StorageInterface',
            function () {
                $session = $this->getContainer()->get('Symfony\Component\HttpFoundation\Session\Session');
                return new \Golem\Auth\Storage\SymfonySessionStorage($session);
            }
        );

        $this->getContainer()->add(
            'auth',
            function () {
                return $this->getContainer()->get('Golem\Auth');
            }
        );
    }
}
