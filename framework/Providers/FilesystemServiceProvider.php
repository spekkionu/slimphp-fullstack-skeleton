<?php
namespace Framework\Providers;

use Aws\S3\S3Client;
use Illuminate\Config\Repository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Flysystem\AdapterInterface;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
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

                /** @var S3Client $client */
                $client = $this->getContainer()->get('Aws\S3\S3Client');
                $s3adapter = new AwsS3Adapter($client, $config->get('s3.bucket'));

                $cacheStore = new CacheStore();
                $adapter = new CachedAdapter($s3adapter, $cacheStore);

                return new Filesystem($adapter, [
                    'visibility' => AdapterInterface::VISIBILITY_PUBLIC
                ]);
            }
        );
        $this->getContainer()->share(
            'Illuminate\Filesystem\Filesystem',
            function () {
                return new \Illuminate\Filesystem\Filesystem();
            }
        );

        $this->getContainer()->share(
            'League\Flysystem\MountManager',
            function () {
                return new MountManager(
                    [
                        'aws'    => $this->getContainer()->get('League\Flysystem\Filesystem'),
                        'backup' => $this->backupFileSystem(),
                    ]
                );
            }
        );
    }

    /**
     * @return Filesystem
     */
    public function backupFileSystem()
    {
        /** @var S3Client $client */
        $client = $this->getContainer()->get('Aws\S3\S3Client');
        $adapter = new AwsS3Adapter($client, env('AWS_S3_BUCKET_BACKUPS'));

        return new Filesystem($adapter, [
            'visibility' => AdapterInterface::VISIBILITY_PRIVATE
        ]);
    }
}
