<?php
namespace Framework\View;

use Psr\Http\Message\ResponseInterface;
use Illuminate\View\Factory as BladeRenderer;

class Blade
{
    /**
     * @var BladeRenderer
     */
    private $view;

    /**
     * Blade constructor.
     *
     * @param BladeRenderer $view
     */
    public function __construct(BladeRenderer $view)
    {
        $this->view = $view;
    }

    /**
     * Render a template
     *
     * $data cannot contain template as a key
     *
     * throws RuntimeException if $templatePath . $template does not exist
     *
     * @param ResponseInterface $response
     * @param string $template
     * @param array $data
     *
     * @return ResponseInterface
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function render(ResponseInterface $response, $template, array $data = [])
    {
        $output = $this->fetch($template, $data);
        $response->getBody()->write($output);
        return $response;
    }

    /**
     * Renders a template and returns the result as a string
     *
     * cannot contain template as a key
     *
     * throws RuntimeException if $templatePath . $template does not exist
     *
     * @param $template
     * @param array $data
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function fetch($template, array $data = [])
    {
        if (isset($data['template'])) {
            throw new \InvalidArgumentException("Duplicate template key found");
        }
        return $this->view->make($template, $data)->render();
    }

    /**
     * Add a piece of shared data to the environment.
     *
     * @param  array|string  $key
     * @param  mixed  $value
     * @return mixed
     */
    public function share($key, $value = null)
    {
        return $this->view->share($key, $value);
    }

    /**
     * @return BladeRenderer
     */
    public function getRenderer()
    {
        return $this->view;
    }
}
