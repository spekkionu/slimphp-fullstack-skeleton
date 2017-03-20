<?php
namespace Framework\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\NativeFileSessionHandler;
use Framework\Validation\ValidateRequestFactory;
use Framework\Validation\ValidationManager;

class SessionServiceProvider extends AbstractServiceProvider
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
            'Symfony\Component\HttpFoundation\Session\Session',
            'session',
            'Framework\Csrf\CsrfManager',
            'csrf',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'Symfony\Component\HttpFoundation\Session\Session', function () {
            $session = new Session(new NativeSessionStorage(
                [],
                new NativeFileSessionHandler(STORAGE_DIR . DIRECTORY_SEPARATOR . 'session')
            ));

            return $session;
        }
        );

        $this->getContainer()->add(
            'session', function () {
            return $this->getContainer()->get('Symfony\Component\HttpFoundation\Session\Session');
        }
        );

        $this->getContainer()->share(
            'Framework\Csrf\CsrfManager',
            function () {
                return new \Framework\Csrf\CsrfManager(
                    $this->getContainer()->get('session'),
                    '_csrf',
                    600
                );
            }
        );

        $this->getContainer()->add(
            'csrf',
            function () {
                return $this->getContainer()->get('Framework\Csrf\CsrfManager');
            }
        );

        $this->getContainer()->share(
            'Framework\Validation\ValidationManager', function () {
            return new ValidationManager();
        }
        );

        $this->getContainer()->share(
            'Framework\Validation\ValidateRequestFactory', function () {
            return new ValidateRequestFactory(
                $this->getContainer(),
                $this->getContainer()->get('session'),
                $this->getContainer()->get('Framework\Validation\ValidationManager')
            );
        }
        );
    }
}
