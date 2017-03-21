<?php
namespace Framework\Providers;

use Framework\View\Blade;
use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\Argument\RawArgument;
use Slim\CallableResolver;
use Slim\Handlers\Error;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;

/**
 * Class HttpServiceProvider
 *
 * @package TalentWatch\ServiceProvider
 */
class SlimServiceProvider extends AbstractServiceProvider
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
            'settings',
            'request',
            'response',
            'router',
            'foundHandler',
            'errorHandler',
            'notFoundHandler',
            'notAllowedHandler',
            'callableResolver',
            'view',
            'Slim\Http\Request',
            'Slim\Http\Response',
            'Slim\App',
            'csrf',
            'Slim\Csrf\Guard',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {

        $this->getContainer()->share(
            'settings',
            function () {
                $settings = [
                    'httpVersion'                       => '1.1',
                    'responseChunkSize'                 => 4096,
                    'outputBuffering'                   => 'append',
                    'determineRouteBeforeAppMiddleware' => true,
                    'displayErrorDetails'               => env('APP_DEBUG', false) ? true : false,
                ];

                return $settings;
            }
        );

        $this->getContainer()->share(
            'Slim\App',
            function () {
                $app = new \Slim\App($this->getContainer());
                require(APP_DIR . '/configs/routes.php');

                return $app;
            }
        );

        $this->getContainer()->share('environment', 'Slim\Http\Environment')->withArgument(new RawArgument($_SERVER));

        $this->getContainer()->add(
            'request',
            function () {
                return $this->getContainer()->get('Slim\Http\Request');
            }
        );

        $this->getContainer()->add(
            'Slim\Http\Request',
            function () {
                return Request::createFromEnvironment($this->getContainer()->get('environment'));
            }
        );
        $this->getContainer()->add(
            'Psr\Http\Message\ServerRequestInterface',
            function () {
                $this->getContainer()->get('Slim\Http\Request');
            }
        );

        $this->getContainer()->add(
            'response',
            function () {
                return $this->getContainer()->get('Slim\Http\Response');

            }
        );

        $this->getContainer()->add(
            'Psr\Http\Message\ResponseInterface',
            function () {
                return Request::createFromEnvironment($this->getContainer()->get('response'));
            }
        );

        $this->getContainer()->add(
            'Slim\Http\Response',
            function () {
                $headers  = new Headers(['Content-Type' => 'text/html']);
                $response = new Response(200, $headers);
                $settings = $this->getContainer()->get('settings');

                return $response->withProtocolVersion($settings['httpVersion']);

            }
        );

        $this->getContainer()->share('router', 'Slim\Router');
        $this->getContainer()->share(
            'foundHandler', function () {
            return new \Framework\Handler\ContainerDispatched($this->getContainer());
        }
        );
        $this->getContainer()->share(
            'errorHandler', function () {

            $settings = $this->getContainer()->get('settings');
            $handler  = new \Framework\Handler\ErrorHandler(
                $settings['displayErrorDetails'],
                $this->getContainer()->get('logger'),
                $this->getContainer()
            );

            return $handler;
        }
        );
        $this->getContainer()->share(
            'notFoundHandler', function () {
            $handler = new \Framework\Handler\NotFoundHandler($this->getContainer()->get('view'));

            return $handler;
        }
        );
        $this->getContainer()->share('notAllowedHandler', 'Slim\Handlers\NotAllowed');

        $this->getContainer()->share(
            'callableResolver',
            function () {
                return new CallableResolver($this->getContainer());
            }
        );
        $this->getContainer()->add(
            'Framework\View\Blade', function () {
            return $this->getContainer()->get('view');
        }
        );

        // Register component on container
        $this->getContainer()->share(
            'view',
            function () {
                return new Blade($this->getContainer()->get('Illuminate\View\Factory'));
            }
        );
    }
}
