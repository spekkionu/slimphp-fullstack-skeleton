<?php
namespace Framework\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use HTMLPurifier;
use HTMLPurifier_Config;

class HtmlPurifierServiceProvider extends AbstractServiceProvider
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
            'HTMLPurifier'
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'HTMLPurifier',
            function () {
                $config = HTMLPurifier_Config::createDefault();

                $config->set('HTML.DefinitionID', 'default');
                $config->set('HTML.DefinitionRev', 1);
                $config->set('HTML.Proprietary', true);
                $config->set('HTML.SafeScripting', []);
                $config->set('HTML.SafeIframe', false);
                $config->set('Attr.AllowedFrameTargets', ['_blank', '_self', '_target', '_top']);
                $config->set('Attr.ForbiddenClasses', []);
                $config->set('AutoFormat.RemoveSpansWithoutAttributes', true);
                //$config->set('HTML.Allowed', "");
                $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
                $config->set('Output.Newline', "\n");
                $config->set(
                    'URI.AllowedSchemes',
                    [
                        'http'   => true,
                        'https'  => true,
                        'mailto' => true,
                        'tel' => true,
                    ]
                );
                $config->set('Cache.SerializerPath', STORAGE_DIR . '/cache/htmlpurifier');
                return new HTMLPurifier($config);
            }
        );
    }
}
