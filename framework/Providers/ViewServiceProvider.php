<?php
namespace Framework\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class ViewServiceProvider extends AbstractServiceProvider
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
            'Illuminate\View\Factory',
            'Illuminate\View\Engines\EngineResolver',
            'Illuminate\View\FileViewFinder',
        ];

    public function register()
    {
        $this->getContainer()->share(
            'Illuminate\View\Factory',
            function () {

                $resolver   = $this->getContainer()->get('Illuminate\View\Engines\EngineResolver');
                $finder     = $this->getContainer()->get('Illuminate\View\FileViewFinder');
                $dispatcher = $this->getContainer()->get('Illuminate\Events\Dispatcher');
                $container  = $this->getContainer()->get('Illuminate\Container\Container');

                $factory = new Factory($resolver, $finder, $dispatcher);
                $factory->setContainer($container);

                return $factory;
            }
        );
        $this->getContainer()->share(
            'Illuminate\View\Engines\EngineResolver',
            function () {
                $pathToCompiledTemplates = STORAGE_DIR . '/view';

                $resolver      = new EngineResolver;
                $filesystem    = $this->getContainer()->get('Illuminate\Filesystem\Filesystem');
                $bladeCompiler = new BladeCompiler($filesystem, $pathToCompiledTemplates);

                $resolver->register('blade', function () use ($bladeCompiler, $filesystem) {
                    return new CompilerEngine($bladeCompiler, $filesystem);
                });
                $resolver->register('php', function () {
                    return new PhpEngine;
                });

                return $resolver;
            }
        );
        $this->getContainer()->share(
            'Illuminate\View\FileViewFinder',
            function () {
                $pathsToTemplates = [APP_ROOT . '/resources/views'];
                $filesystem       = $this->getContainer()->get('Illuminate\Filesystem\Filesystem');

                return new FileViewFinder($filesystem, $pathsToTemplates);
            }
        );
    }
}
