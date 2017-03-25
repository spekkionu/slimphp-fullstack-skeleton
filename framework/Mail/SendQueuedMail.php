<?php
namespace Framework\Mail;

use Illuminate\Queue\SerializesModels;
use PMG\Queue\Message;
use PMG\Queue\MessageTrait;
use Spekkionu\Tactician\SelfExecuting\SelfExecutingCommand;

class SendQueuedMail implements SelfExecutingCommand, Message
{
    use MessageTrait, SerializesModels;

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
