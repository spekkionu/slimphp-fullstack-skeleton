<?php
namespace Framework\Mail;

use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

class SendMail implements SelfExecutingCommand
{
    /**
     * @var Mail
     */
    private $mail;

    /**
     * Class Constructor
     *
     * @param Mail $mail
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * @param Mailer $mailer
     *
     * @return bool
     */
    public function handle(Mailer $mailer)
    {
        $mailer->send($this->mail);

        return true;
    }
}
