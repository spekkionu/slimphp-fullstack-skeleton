<?php
namespace Framework\Providers;

use Illuminate\Contracts\Config\Repository;
use League\Container\ServiceProvider\AbstractServiceProvider;

class AwsServiceProvider extends AbstractServiceProvider
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
            'Aws\S3\S3Client',
            'Aws\Sns\SnsClient',
            'Aws\Sqs\SqsClient',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {

        $this->getContainer()->share(
            'Aws\S3\S3Client',
            function () {
                /** @var Repository $config */
                $config = $this->getContainer()->get('config');

                return new \Aws\S3\S3Client([
                    'credentials' => [
                        'key'    => $config->get('aws.key'),
                        'secret' => $config->get('aws.secret'),
                    ],
                    'region'      => $config->get('aws.region'),
                    'version'     => '2006-03-01',
                ]);
            }
        );
        $this->getContainer()->share(
            'Aws\Sns\SnsClient',
            function () {
                /** @var Repository $config */
                $config = $this->getContainer()->get('config');

                return new \Aws\Sns\SnsClient([
                    'version'     => '2010-03-31',
                    'region'      => $config->get('aws.region'),
                    'credentials' => [
                        'key'    => $config->get('aws.key'),
                        'secret' => $config->get('aws.secret'),
                    ],
                ]);
            }
        );
        $this->getContainer()->share(
            'Aws\Sqs\SqsClient',
            function () {
                /** @var Repository $config */
                $config = $this->getContainer()->get('config');

                return new \Aws\Sqs\SqsClient([
                    'credentials' => [
                        'key'    => $config->get('aws.key'),
                        'secret' => $config->get('aws.secret'),
                    ],
                    'region'      => $config->get('aws.region'),
                    'retries'     => 3,
                    'version'     => '2012-11-05',
                ]);
            }
        );
    }
}
