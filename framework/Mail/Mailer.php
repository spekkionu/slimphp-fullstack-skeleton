<?php
namespace Framework\Mail;

use Framework\View\Blade;
use Illuminate\Contracts\Config\Repository as Config;
use RuntimeException;

class Mailer
{
    /**
     * @var \Swift_Transport
     */
    private $transport;
    /**
     * @var Blade
     */
    private $view;
    /**
     * @var Config
     */
    private $config;

    /**
     * Class Constructor
     *
     * @param \Swift_Transport $transport
     * @param Blade            $view
     * @param Config           $config
     */
    public function __construct(\Swift_Transport $transport, Blade $view, Config $config)
    {
        $this->transport = $transport;
        $this->view      = $view;
        $this->config    = $config;
    }

    /**
     * Builds message
     *
     * @param Mail $mail
     *
     * @return \Swift_Message
     */
    public function getMessage(Mail $mail)
    {
        $message = new \Swift_Message();
        $from    = $mail->getFrom();
        if (!$from) {
            $from = $this->config->get('mail.from');
            if ($from['name']) {
                $from = [$from['address'] => $from['name']];
            } else {
                $from = $from['address'];
            }
        }
        $message->setFrom($from);
        $sender = $mail->getSender();
        if ($sender) {
            $message->setSender($sender);
        }
        $message->setTo($mail->getTo());
        $message->setReplyTo($mail->getReply());
        $return = $mail->getReturn();
        if ($return) {
            $message->setReturnPath($return);
        }
        $message->setBcc($mail->getBcc());
        $message->setCc($mail->getCc());
        $message->setSubject($mail->getSubject());
        $template = $mail->getTemplate();
        if ($template) {
            $body = $this->view->fetch($template, $mail->getParams());
            $message->setBody($body, 'text/html');
        }
        $plain = $mail->getText();
        if ($plain) {
            $body = $this->view->fetch($plain, $mail->getParams());
            $message->addPart($body, 'text/plain');
        }
        $attachments = $mail->getAttachments();
        foreach ($attachments as $file) {
            if ($file['type'] === 'data') {
                $attachment = \Swift_Attachment::newInstance($file['content']);
            } else {
                $attachment = \Swift_Attachment::fromPath($file['content']);
            }
            $attachment->setContentType($file['mime']);
            $attachment->setFilename($file['name']);
            $message->attach($attachment);
        }

        return $message;
    }

    /**
     * Sends mail
     *
     * @param Mail       $mail
     * @param array|null $failed An array of failures by-reference
     *
     * @return int The number of recipients who were accepted for delivery.
     * @throws \RuntimeException
     */
    public function send(Mail $mail, array &$failed = null)
    {
        $message = $this->getMessage($mail);

        return $this->sendMessage($message, $failed);
    }

    /**
     * Sends message
     *
     * @param \Swift_Message $message
     * @param array|null     $failed
     *
     * @return int The number of recipients who were accepted for delivery.
     * @throws \RuntimeException
     */
    public function sendMessage(\Swift_Message $message, array &$failed = null)
    {
        $sent = $this->transport->send($message, $failed);
        if ($failed) {
            throw new RuntimeException('Failed to send email');
        }

        return $sent;
    }
}
