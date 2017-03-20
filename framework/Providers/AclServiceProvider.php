<?php
namespace Framework\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Zend\Permissions\Acl\Acl;


class AclServiceProvider extends AbstractServiceProvider
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
            'acl',
            'Zend\Permissions\Acl\Acl',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {

        $this->getContainer()->share(
            'Zend\Permissions\Acl\Acl',
            function () {
                $acl = new Acl();
                require_once APP_DIR . '/configs/acl.php';

                return $acl;
            }
        );

        $this->getContainer()->add(
            'acl',
            function () {
                return $this->getContainer()->get('Zend\Permissions\Acl\Acl');
            }
        );
    }
}
