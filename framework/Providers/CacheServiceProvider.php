<?php
namespace Framework\Providers;

use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\ChainCache;
use Doctrine\Common\Cache\FilesystemCache;
use Doctrine\Common\Cache\MemcacheCache;
use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\RedisCache;
use Doctrine\Common\Cache\VoidCache;
use Illuminate\Config\Repository;
use League\Container\ServiceProvider\AbstractServiceProvider;

class CacheServiceProvider extends AbstractServiceProvider
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
            'Doctrine\Common\Cache\Cache',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {

        $this->getContainer()->share(
            'Doctrine\Common\Cache\Cache',
            function () {
                $config = $this->getContainer()->get('Illuminate\Config\Repository');

                $driver = $config->get('cache.driver');

                return $this->getCacheDriver($driver, $config);
            }
        );

    }

    /**
     * @param string     $driver
     * @param Repository $config
     *
     * @return Cache
     */
    private function getCacheDriver(string $driver, Repository $config)
    {
        $cache = null;
        if ($driver === 'array') {
            $cache = new ArrayCache();
            $cache->setNamespace($config->get('cache.prefix'));

            return $cache;
        }
        if ($driver === 'test') {
            $cache = new VoidCache();
            $cache->setNamespace($config->get('cache.prefix'));

            return $cache;
        }
        if ($driver === 'file') {
            $cache = $this->getFilesystemDriver();
        }
        if ($driver === 'apc') {
            $cache = $this->getApcDriver();
        }
        if ($driver === 'redis') {
            $cache = $this->getRedisDriver();
        }
        if ($driver === 'memcache') {
            $cache = $this->getMemcacheDriver();
        }
        if ($driver === 'memcached') {
            $cache = $this->getMemcachedDriver();

        }
        if ($this->getContainer()->has($driver)) {
            /** @var Cache $cache */
            $cache = $this->getContainer()->get($driver);
        }
        if (!($cache instanceof Cache)) {
            throw new \RuntimeException('Invalid cache driver');
        }

        $cache = $this->getCompositeCache($cache);
        $cache->setNamespace($config->get('cache.prefix'));

        return $cache;
    }

    /**
     * @param Cache $driver
     *
     * @return ChainCache
     */
    private function getCompositeCache(Cache $driver)
    {
        return new ChainCache([new ArrayCache(), $driver]);
    }

    /**
     * @return FilesystemCache
     */
    private function getFilesystemDriver()
    {
        $directory = STORAGE_DIR . '/cache/app';

        return new FilesystemCache($directory);
    }

    /**
     * @return ApcuCache
     */
    private function getApcDriver()
    {
        return new ApcuCache();
    }

    /**
     * @return RedisCache
     */
    private function getRedisDriver()
    {
        $cache = new RedisCache();
        $cache->setRedis($this->getContainer()->get('Redis'));

        return $cache;
    }

    /**
     * @return MemcachedCache
     */
    private function getMemcachedDriver()
    {
        $cache = new MemcachedCache();
        $cache->setMemcached($this->getContainer()->get('Memcached'));

        return $cache;
    }

    /**
     * @return MemcacheCache
     */
    private function getMemcacheDriver()
    {
        $cache = new MemcacheCache();
        $cache->setMemcache($this->getContainer()->get('Memcache'));

        return $cache;
    }
}
