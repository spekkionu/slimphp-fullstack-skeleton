<?php
namespace Framework\Providers;

use Illuminate\Config\Repository;
use League\Container\ServiceProvider\AbstractServiceProvider;
use Swift_MailTransport;
use Swift_NullTransport;
use Swift_SendmailTransport;
use Swift_SmtpTransport;


class MailServiceProvider extends AbstractServiceProvider
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
            'Swift_Transport',
        ];

    /**
     * This is where the magic happens, within the method you can
     * access the container and register or retrieve anything
     * that you need to
     */
    public function register()
    {
        $this->getContainer()->share(
            'Swift_Transport',
            function () {
                /** @var Repository $config */
                $config = $this->getContainer()->get('config');
                if ($config->get('mail.driver') === 'smtp') {
                    return $this->getSmtpTransport($config);
                } elseif ($config->get('mail.driver') === 'sendmail') {
                    return $this->getSendmailTransport($config);
                } elseif ($config->get('mail.driver') === 'fake') {
                    return $this->getNullTransport($config);
                } else {
                    return $this->getMailTransport($config);
                }
            }
        );

        $this->getContainer()->add(
            'mail.transport',
            function () {
                return $this->getContainer()->get('Swift_Transport');
            }
        );
    }

    /**
     * @param Repository $config
     *
     * @return Swift_SmtpTransport
     */
    protected function getSmtpTransport(Repository $config)
    {
        $transport = Swift_SmtpTransport::newInstance(
            $config->get('mail.smtp.server'),
            $config->get('mail.smtp.port'),
            $config->get('mail.smtp.encrypt')
        );
        $transport->setUsername($config->get('mail.smtp.user'));
        $transport->setPassword($config->get('mail.smtp.password'));
        $transport->setAuthMode($config->get('mail.smtp.auth'));
        //$transport->setLocalDomain('[127.0.0.1]');

        return $transport;
    }

    /**
     * @param Repository $config
     *
     * @return Swift_SendmailTransport
     */
    protected function getSendmailTransport(Repository $config)
    {
        return Swift_SendmailTransport::newInstance($config->get('mail.sendmail', '/usr/sbin/sendmail -bs'));
    }

    /**
     * @param Repository $config
     *
     * @return Swift_MailTransport
     */
    protected function getMailTransport(Repository $config)
    {
        return Swift_MailTransport::newInstance($config->get('mail.mailoptions', '-f%s'));
    }

    /**
     * @param Repository $config
     *
     * @return Swift_NullTransport
     */
    protected function getNullTransport(Repository $config)
    {
        return Swift_NullTransport::newInstance();
    }
}
