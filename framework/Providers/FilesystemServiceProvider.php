<?php
namespace Framework\Providers;

use Aws\S3\S3Client;
use Illuminate\Config\Repository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\AdapterInterface;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Rackspace\RackspaceAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;

/**
 * Class FilesystemServiceProvider
 *
 * @package TalentWatch\ServiceProvider
 */
class FilesystemServiceProvider extends AbstractServiceProvider
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
            'League\Flysystem\MountManager',
            'League\Flysystem\Filesystem',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'League\Flysystem\Filesystem',
            function () {
                /** @var Repository $config */
                $config = $this->getContainer()->get('config');

                $default = $config->get('filesystem.default');

                return $this->getFilesystemAdapter($config->get("filesystem.disks.{$default}"));
            }
        );

        $this->getContainer()->share(
            'League\Flysystem\MountManager',
            function () {
                /** @var Repository $config */
                $config = $this->getContainer()->get('config');

                $disks = [];
                foreach ($config->get('filesystem.disks') as $name => $disk) {
                    $disks[$name] = $this->getFilesystemAdapter($disk);
                }

                return new MountManager($disks);
            }
        );
    }

    /**
     * @param array $config
     *
     * @return Filesystem
     */
    private function getFilesystemAdapter(array $config)
    {
        $adapter = null;
        if ($config['driver'] === 'local') {
            $adapter = $this->getLocalAdapter($config);
        } elseif ($config['driver'] === 'null') {
            $adapter = $this->getNullAdapter($config);
        } elseif ($config['driver'] === 's3') {
            $adapter = $this->getAwsAdapter($config);
        } elseif ($config['driver'] === 'rackspace') {
            $adapter = $this->getRackspaceAdapter($config);
        } elseif ($this->getContainer()->has($config['driver'])) {
            $adapter = $this->getContainer()->get($config['driver']);
        }

        if (!($adapter instanceof AdapterInterface)) {
            throw new \RuntimeException('Invalid filesystem adapter');
        }

        return new Filesystem($adapter, [
            'visibility' => $config['visibility'],
        ]);
    }

    /**
     * @param array $config
     *
     * @return Local
     */
    private function getLocalAdapter(array $config)
    {
        return new Local($config['root']);
    }

    /**
     * @param array $config
     *
     * @return CachedAdapter
     */
    private function getAwsAdapter(array $config)
    {
        /** @var S3Client $client */
        $client     = $this->getContainer()->get('Aws\S3\S3Client');
        $s3adapter  = new AwsS3Adapter($client, $config['bucket'], $config['prefix']);
        $cacheStore = new CacheStore();

        return new CachedAdapter($s3adapter, $cacheStore);
    }

    /**
     * @param array $config
     *
     * @return CachedAdapter
     */
    private function getRackspaceAdapter(array $config)
    {
        $client     = $this->getContainer()->get('OpenCloud\OpenStack');
        $store      = $client->objectStoreService('cloudFiles', $config['region']);
        $container  = $store->getContainer($config['container']);
        $adapter  = new RackspaceAdapter($container, $config['prefix']);
        $cacheStore = new CacheStore();

        return new CachedAdapter($adapter, $cacheStore);
    }

    /**
     * @param array $config
     *
     * @return NullAdapter
     */
    private function getNullAdapter(array $config)
    {
        return new NullAdapter();
    }
}
