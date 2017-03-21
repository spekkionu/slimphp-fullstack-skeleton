<?php

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Response;

if (!function_exists('env')) {
    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    function env($name, $default = null)
    {
        return Env::get($name) ?: $default;
    }
}
if (!function_exists('app_path')) {
    /**
     * @param string $path
     *
     * @return string
     */
    function app_path($path = '')
    {
        return APP_ROOT . DIRECTORY_SEPARATOR . ltrim($path, '/');
    }
}
if (!function_exists('public_path')) {
    /**
     * @param string $path
     *
     * @return string
     */
    function public_path($path = '')
    {
        return WEBROOT . DIRECTORY_SEPARATOR . ltrim($path, '/');
    }
}
if (!function_exists('storage_path')) {
    /**
     * @param string $path
     *
     * @return string
     */
    function storage_path($path = '')
    {
        return STORAGE_DIR . DIRECTORY_SEPARATOR . ltrim($path, '/');
    }
}
if (!function_exists('current_url')) {
    /**
     * Returns current url
     *
     * @param bool $absolute
     *
     * @param bool $secure
     *
     * @return string
     */
    function current_url($absolute = false, $secure = false)
    {
        global $container;
        /** @var Slim\Http\Request $request */
        $request = $container->get('Slim\Http\Request');
        $uri     = Zend\Uri\UriFactory::factory($request->getUri()->__toString());
        if ($secure) {
            $uri = $uri->setScheme('https');
        }

        if ($absolute || $request->getUri()->getScheme() != $uri->getScheme()) {
            return $uri->toString();
        }

        return $uri->normalize()->setHost(null)->setPort(null)->setScheme(null)->toString();
    }
}
if (!function_exists('route')) {
    /**
     * Build the path for a named route including the base path
     *
     * @param string $name        Route name
     * @param array  $data        Named argument replacement data
     * @param array  $queryParams Optional query string parameters
     * @param bool   $absolute
     *
     * @return string
     */
    function route($name, array $data = [], array $queryParams = [], $absolute = false)
    {
        global $container;
        /** @var Slim\Interfaces\RouterInterface $router */
        $router = $container->get('Slim\Interfaces\RouterInterface');

        if ($absolute) {
            return $router->pathFor($name, $data, $queryParams);
        } else {
            return $router->relativePathFor($name, $data, $queryParams);
        }
    }
}
if (!function_exists('redirect')) {
    /**
     * Returns a redirect response
     *
     * @param string $url
     * @param int    $status
     *
     * @return ResponseInterface
     */
    function redirect(string $url, int $status = 302)
    {
        $response = new Response();

        return $response->withRedirect($url, $status);
    }
}
if (!function_exists('view')) {
    /**
     * Renders template and returns response
     *
     * @param string $template
     * @param array  $params
     * @param int    $status
     *
     * @return ResponseInterface
     */
    function view($template, array $params = [], $status = 200)
    {
        global $container;
        /** @var \Framework\View\Blade $view */
        $view = $container->get('view');
        /** @var \Slim\Http\Response $response */
        $response = $container->get('response');

        return $view->render($response->withStatus($status), $template, $params);
    }
}
if (!function_exists('send_404')) {
    /**
     * Renders template and returns response
     *
     * @param array $params
     *
     * @return ResponseInterface|Response
     */
    function send404(array $params = [])
    {
        global $container;
        /** @var \Framework\View\Blade $view */
        $view = $container->get('view');
        /** @var \Slim\Http\Response $response */
        $response = $container->get('response');
        /** @var \Slim\Http\Request $request */
        $request = $container->get('request');

        if ($request->isXhr()) {
            if (empty($params)) {
                $params = 'Page Not Found';
            }

            return $response->withJson($params, 404);
        }

        return $view->render($response->withStatus(404), 'error.404', $params);
    }
}
if (!function_exists('send_json')) {
    /**
     * Returns a redirect response
     *
     * @param array $data
     * @param int   $status
     *
     * @return Response
     */
    function sendJson($data = [], int $status = 200)
    {
        global $container;
        /** @var Response $response */
        $response = $container->get('response');

        return $response->withJson($data, $status);
    }
}
if (!function_exists('render')) {
    /**
     * Renders results of a subrequest
     *
     * @param string $callable Controller:action or route name
     * @param array $params Extra params to pass to action
     * @param array|null $query Query string variables
     *
     * @return string
     */
    function render($callable, array $params = [], array $query = [])
    {
        global $container;
        if (preg_match('/^(.*)::(.*)$/', $callable)) {
            $params['request'] = $container->get('request')->withQueryParams($query);
            $params['response'] = $container->get('response');

            $callable = 'App\\Controller\\' . $callable;
            if (is_string($callable) && strpos($callable, '::') !== false) {
                $callable = explode('::', $callable);
            }
            $callable[0] = $container->get($callable[0]);

            $response = $container->call($callable, $params);
            if ($response instanceof ResponseInterface) {
                $stream = $response->getBody();
                $stream->rewind();
                return $stream->getContents();
            }
            return $response;
        }
        return route_request($callable, $params, $query);
    }
}
if (!function_exists('route_request')) {
    /**
     * @param string $route
     * @param array $params
     * @param array $query
     *
     * @return string
     */
    function route_request($route, array $params = [], array $query = null)
    {
        $path = route($route, $params);
        return sub_request($path, $query);
    }
}
if (!function_exists('sub_request')) {
    /**
     * Perform a sub-request from within an application route
     *
     * This method allows you to prepare and initiate a sub-request, run within
     * the context of the current request. This WILL NOT issue a remote HTTP
     * request. Instead, it will route the provided URL, method, headers,
     * cookies, body, and server variables against the set of registered
     * application routes. The result response object is returned.
     *
     * @param  string $path The request URI path
     * @param  array $query The request URI query string
     *
     * @return string
     */
    function sub_request($path, array $query = null)
    {
        global $container;
        $method = 'GET';
        $headers = [
            'X-Sub-Request' => true,
        ];
        $cookies = [];
        $bodyContent = '';
        $response = null;
        $query = $query ? http_build_query($query) : '';
        /** @var ResponseInterface $response */
        $response = $container->get('Slim\App')->subRequest(
            $method, $path, $query, $headers, $cookies, $bodyContent, $response
        );

        $stream = $response->getBody();
        $stream->rewind();
        return $stream->getContents();
    }
}
if (!function_exists('config')) {
    /**
     * Returns config variable
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    function config($key, $default = null)
    {
        global $container;
        /** @var Illuminate\Contracts\Config\Repository $config */
        $config = $container->get('Illuminate\Contracts\Config\Repository');

        return $config->get($key, $default);
    }
}
if (!function_exists('logger')) {
    /**
     * Returns logger instance
     *
     * @param string|null $name
     *
     * @return \Monolog\Logger
     */
    function logger($name = null)
    {
        global $container;

        if ($name) {
            return $container->get("log.{$name}");
        }

        return $container->get('Monolog\Logger');
    }
}
if (!function_exists('session')) {
    /**
     * Returns session instance
     *
     * @return \Symfony\Component\HttpFoundation\Session\Session
     */
    function session()
    {
        global $container;

        return $container->get('Symfony\Component\HttpFoundation\Session\Session');
    }
}
if (!function_exists('auth')) {
    /**
     * Executes the given command and optionally returns a value
     *
     * @return Golem\Auth\Auth
     */
    function auth()
    {
        global $container;

        return $container->get('Golem\Auth\Auth');
    }
}
if (!function_exists('has_identity')) {
    /**
     * Check if user is logged in
     *
     * @return bool
     */
    function has_identity()
    {
        global $container;
        /** @var Golem\Auth\Auth $auth */
        $auth = $container->get('Golem\Auth\Auth');

        return $auth->loggedIn();
    }
}
if (!function_exists('acl')) {
    /**
     * Returns acl instance
     *
     * @return \Zend\Permissions\Acl\Acl
     */
    function acl()
    {
        global $container;

        /** @var \Zend\Permissions\Acl\Acl $acl */
        return $container->get('Zend\Permissions\Acl\Acl');
    }
}
if (!function_exists('has_access')) {
    /**
     * Check if currently logged in user has access to requested resource
     * Acl instance should be in Zend_Registry with key acl
     * Access level should be stored in identity with key access_level
     *
     * @param string $resource  Application resource
     * @param string $privilege Resource privilege
     *
     * @return bool True if has access, false otherwise
     */
    function has_access($resource = null, $privilege = null)
    {
        global $container;

        /** @var Golem\Auth\Auth $auth */
        $auth = $container->get('Golem\Auth\Auth');

        if ($auth->loggedIn()) {
            $role = $auth->user();
        } else {
            $role = 'guest';
        }
        /** @var \Zend\Permissions\Acl\Acl $acl */
        $acl = $container->get('Zend\Permissions\Acl\Acl');

        return $acl->isAllowed($role, $resource, $privilege);
    }
}
if (!function_exists('event')) {
    /**
     * Fire an event and call the listeners.
     *
     * @param  string|object $event
     * @param  mixed         $payload
     * @param  bool          $halt
     *
     * @return array|null
     */
    function event($event, $payload = [], $halt = false)
    {
        global $container;
        /** @var \Illuminate\Events\Dispatcher $dispatcher */
        $dispatcher = $container->get('event');

        return $dispatcher->dispatch($event, $payload, $halt);
    }
}
if (!function_exists('dispatch')) {
    /**
     * Executes the given command and optionally returns a value
     *
     * @param object $command
     *
     * @return mixed
     */
    function dispatch($command)
    {
        global $container;
        /** @var \League\Tactician\CommandBus $commandBus */
        $commandBus = $container->get('command');

        return $commandBus->handle($command);
    }
}
if (!function_exists('app')) {
    /**
     * Returns DI container or pulls object from container
     *
     * @param null|string $key
     * @param array       $args
     *
     * @return mixed
     */
    function app($key = null, array $args = [])
    {
        global $container;
        if (is_null($key)) {
            return $container;
        }

        return $container->get($key, $args);
    }
}
if (!function_exists('format_date')) {
    /**
     * Returns DI container or pulls object from container
     *
     * @param mixed               $date
     * @param string              $format
     * @param string|DateTimeZone $timezone
     *
     * @return string|null
     */
    function format_date($date, $format = 'm/d/Y h:i:s A', $timezone = null)
    {
        if (empty($date)) {
            return null;
        }
        if (is_string($date)) {
            $date = new DateTime($date, $timezone);

            return $date->format($format);
        }
        if (!($date instanceof \DateTime)) {
            return null;
        }
        if ($timezone) {
            $date = clone $date;
            $date->setTimezone($timezone);
        }

        return $date->format($format);
    }
}
