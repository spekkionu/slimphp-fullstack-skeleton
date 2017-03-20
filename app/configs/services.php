<?php

use Framework\Container\LaravelContainer;
use League\Container\Argument\RawArgument;

$container = new League\Container\Container();

$container->delegate(
    new \League\Container\ReflectionContainer
);

// Add container itself
$container->share('League\Container\Container', $container);

// Add framework service providers
$container->addServiceProvider(Framework\Providers\ContainerServiceProvider::class);
$container->addServiceProvider(Framework\Providers\ConfigServiceProvider::class);
$container->addServiceProvider(Framework\Providers\DatabaseServiceProvider::class);
$container->addServiceProvider(Framework\Providers\LoggerServiceProvider::class);
$container->addServiceProvider(Framework\Providers\SessionServiceProvider::class);
$container->addServiceProvider(Framework\Providers\AuthServiceProvider::class);
$container->addServiceProvider(Framework\Providers\AclServiceProvider::class);
$container->addServiceProvider(Framework\Providers\CacheServiceProvider::class);
$container->addServiceProvider(Framework\Providers\CryptServiceProvider::class);
$container->addServiceProvider(Framework\Providers\MailServiceProvider::class);
$container->addServiceProvider(Framework\Providers\EventServiceProvider::class);
$container->addServiceProvider(Framework\Providers\CommandBusServiceProvider::class);
$container->addServiceProvider(Framework\Providers\QueueServiceProvider::class);
$container->addServiceProvider(Framework\Providers\ViewServiceProvider::class);
$container->addServiceProvider(Framework\Providers\SlimServiceProvider::class);

// Add application service providers

return $container;
