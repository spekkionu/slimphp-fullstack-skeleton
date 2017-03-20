<?php
namespace Framework\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Zend\Crypt\BlockCipher;
use Zend\Crypt\Symmetric\Openssl;

class CryptServiceProvider extends AbstractServiceProvider
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
            'crypt', 'Zend\Crypt\BlockCipher'
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {

        $this->getContainer()->share(
            'Zend\Crypt\BlockCipher',
            function () {
                $app_config = $this->getContainer()->get('config');


                $blockCipher = new BlockCipher(new Openssl(array(
                    'algo' => $app_config->get('crypt.algo', 'aes'),
                    'mode' => $app_config->get('crypt.mode', 'cbc'),
                )));

                $blockCipher->setHashAlgorithm($app_config->get('crypt.hash', 'sha256'));
                $blockCipher->setKey($app_config->get('crypt.key'));

                return $blockCipher;
            }
        );

        $this->getContainer()->add(
            'crypt',
            function () {
                return $this->getContainer()->get('Zend\Crypt\BlockCipher');
            }
        );
    }
}
