<?php

namespace Framework\Providers;

use Illuminate\Config\Repository;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Database\Eloquent\Factory;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;
use League\Container\ServiceProvider\BootableServiceProviderInterface;

/**
 * Class DatabaseServiceProvider
 */
class DatabaseServiceProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    /**
     * @var Manager
     */
    protected $capsule;

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
            'database',
            'PDO',
            'Illuminate\Database\Capsule\Manager',
            'Illuminate\Database\Eloquent\Factory',
        ];

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        $this->getCapsule();
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->getContainer()->share(
            'Illuminate\Database\Capsule\Manager',
            function () {
                return $this->getCapsule();
            }
        );
        $this->getContainer()->add(
            'database',
            function () {
                return $this->getContainer()->get('Illuminate\Database\Capsule\Manager');
            }
        );
        $this->getContainer()->share(
            'PDO',
            function () {
                return $this->getContainer()->get('Illuminate\Database\Capsule\Manager')->connection()->getPdo();
            }
        );
        $this->getContainer()->share(
            'Illuminate\Database\Eloquent\Factory',
            function () {
                $faker = \Faker\Factory::create();

                return Factory::construct($faker, app_path('database/factories'));
            }
        );
    }

    /**
     * @return Capsule
     */
    protected function getCapsule()
    {
        if ($this->capsule) {
            return $this->capsule;
        }

        /** @var Repository $config */
        $config = $this->getContainer()->get('Illuminate\Config\Repository');

        $capsule  = new Capsule;
        $settings = $config->get('database');
        foreach ($settings as $name => $connection) {
            $driver = $config->get("database.{$name}.driver", 'mysql');
            if ($driver === 'sqlite') {
                $dbname = $config->get("database.{$name}.dbname");
                if (in_array($dbname, ['memory', ':memory:'])) {
                    $database = ':memory:';
                } else {
                    $filename = pathinfo($dbname, PATHINFO_FILENAME) . '.sqlite';
                    $database = app_path('storage/database/' . $filename);
                }

                $capsule->addConnection(
                    [
                        'driver'    => $driver,
                        'database'  => $database,
                        'charset'   => $config->get("database.{$name}.charset", 'utf8mb4'),
                        'collation' => $config->get("database.{$name}.collation", 'utf8mb4_unicode_520_ci'),
                        'prefix'    => $config->get("database.{$name}.prefix", ""),
                    ], $name
                );
            } else {
                $capsule->addConnection(
                    [
                        'driver'    => $config->get("database.{$name}.driver", 'mysql'),
                        'host'      => $config->get("database.{$name}.host"),
                        'database'  => $config->get("database.{$name}.dbname"),
                        'username'  => $config->get("database.{$name}.username"),
                        'password'  => $config->get("database.{$name}.password"),
                        'charset'   => $config->get("database.{$name}.charset", 'utf8mb4'),
                        'collation' => $config->get("database.{$name}.collation", 'utf8mb4_unicode_520_ci'),
                        'prefix'    => $config->get("database.{$name}.prefix", ""),
                        'strict'    => $config->get("database.{$name}.strict", true),
                    ], $name
                );
            }
        }


        // Register Database Event Listeners
        $capsule->setEventDispatcher($this->getContainer()->get('Illuminate\Events\Dispatcher'));

        // Make this Capsule instance available globally via static methods... (optional)
        $capsule->setAsGlobal();

        // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
        $capsule->bootEloquent();

        $this->capsule = $capsule;

        return $this->capsule;
    }
}
