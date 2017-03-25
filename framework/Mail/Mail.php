<?php
namespace Framework\Mail;

abstract class Mail
{
    /**
     * @var string
     */
    protected $template;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var array|string
     */
    protected $to;

    /**
     * @var array
     */
    protected $cc = [];

    /**
     * @var array
     */
    protected $bcc = [];

    /**
     * @var array|string
     */
    protected $reply;

    /**
     * @var string
     */
    protected $return;

    /**
     * @var array|string
     */
    protected $from;

    /**
     * @var array|string
     */
    protected $sender;

    /**
     * @var string
     */
    protected $subject;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var array
     */
    protected $attachments = [];

    /**
     * @return array|string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets to addresses
     *
     * @param array|string $to
     */
    public function setTo($to = null)
    {
        $this->to = $to;
    }

    /**
     * Adds a file attachment
     *
     * @param string      $file Path to file
     * @param string|null $name Filename to attach as
     * @param string|null $mime Mimetype of file
     */
    public function attachFile(string $file, string $name = null, string $mime = null)
    {
        $this->attachments[] = [
            'type'    => 'path',
            'content' => $file,
            'name'    => $name,
            'mime'    => $mime,
        ];
    }

    /**
     * Attaches file by content
     *
     * @param string      $content Binary content of file
     * @param string|null $name Filename to attach as
     * @param string|null $mime Mimetype of file
     */
    public function attachFileData($content, string $name = null, string $mime = null)
    {
        $this->attachments[] = [
            'type'    => 'data',
            'content' => $content,
            'name'    => $name,
            'mime'    => $mime,
        ];
    }

    /**
     * Clears out any set attachments
     */
    public function clearAttachments()
    {
        $this->attachments = [];
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Sets email html template
     *
     * @param string $template
     */
    public function setTemplate(string $template = null)
    {
        $this->template = $template;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Sets email subject
     *
     * @param string $subject
     */
    public function setSubject(string $subject = null)
    {
        $this->subject = $subject;
    }

    /**
     * @return array|string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets from address
     *
     * @param array|string $from
     */
    public function setFrom($from = null)
    {
        $this->from = $from;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return array_merge(['__subject' => $this->getSubject()], $this->params);
    }

    /**
     * Sets email template variables
     *
     * @param array $params
     */
    public function setParams(array $params = [])
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets template for plain text version of email
     *
     * @param string $text
     */
    public function setText(string $text = null)
    {
        $this->text = $text;
    }

    /**
     * @return array
     */
    public function getCc()
    {
        return $this->cc;
    }

    /**
     * Sets cc addresses
     *
     * @param array $cc
     */
    public function setCc(array $cc = [])
    {
        $this->cc = $cc;
    }

    /**
     * @return array
     */
    public function getBcc()
    {
        return $this->bcc;
    }

    /**
     * Sets bcc addresses
     *
     * @param array $bcc
     */
    public function setBcc(array $bcc = [])
    {
        $this->bcc = $bcc;
    }

    /**
     * @return array|string
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * @param array|string $reply
     */
    public function setReply($reply = null)
    {
        $this->reply = $reply;
    }

    /**
     * @return array
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @return array|string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param array|string $sender
     */
    public function setSender($sender = null)
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param string $return
     */
    public function setReturn(string $return = null)
    {
        $this->return = $return;
    }
}
