<?php
namespace Framework\Container;

use Illuminate\Container\Container;
use Interop\Container\Exception\ContainerException;
use Interop\Container\Exception\NotFoundException;
use League\Container\ContainerInterface;

class LaravelContainer extends Container implements \Interop\Container\ContainerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Class Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Resolve the given type from the container.
     *
     * @param  string $abstract
     *
     * @return mixed
     */
    public function make($abstract)
    {
        if (!$this->bound($abstract)) {
            return $this->container->get($abstract);
        }

        return parent::make($abstract);
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        return $this->make($id);
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($id)
    {
        if ($this->bound($id)) {
            return true;
        }

        return $this->container->has($id);
    }
}
