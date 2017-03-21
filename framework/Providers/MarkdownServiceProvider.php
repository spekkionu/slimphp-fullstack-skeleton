<?php
namespace Framework\Providers;

use League\CommonMark\CommonMarkConverter;
use League\Container\ServiceProvider\AbstractServiceProvider;

class MarkdownServiceProvider extends AbstractServiceProvider
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
            'League\CommonMark\CommonMarkConverter'
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'League\CommonMark\CommonMarkConverter',
            function () {
                return new CommonMarkConverter([
                    'renderer' => [
                        'block_separator' => "\n",
                        'inner_separator' => "\n",
                        'soft_break'      => "\n",
                    ],
                    'enable_emphasis' => true,
                    'enable_strong' => true,
                    'use_asterisk' => true,
                    'use_underscore' => true,
                    'html_input' => 'escape',
                    'allow_unsafe_links' => true,
                ]);
            }
        );

    }
}
