<?php

namespace PHPCI\Helper;

use b8\Config;
use PHPCI\Helper\MailerFactory;

class Email
{
    const DEFAULT_FROM = 'PHPCI <no-reply@phptesting.org>';

    protected $to = array();
    protected $cc = array();
    protected $subject = 'Email from PHPCI';
    protected $body = '';
    protected $isHtml = false;
    protected $config;

    public function __construct()
    {
        $this->config = Config::getInstance();
    }

    public function setTo($email, $name = null)
    {
        $this->to[$email] = $name;

        return $this;
    }

    public function addCc($email, $name = null)
    {
        $this->cc[$email] = $name;

        return $this;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    public function setIsHtml($isHtml = false)
    {
        $this->isHtml = $isHtml;

        return $this;
    }

    public function send()
    {
        $smtpServer = $this->config->get('phpci.email_settings.smtp_address');

        if (empty($smtpServer)) {
            return $this->sendViaMail();
        } else {
            return $this->sendViaSwiftMailer();
        }
    }

    protected function sendViaMail()
    {
        $headers = '';

        if ($this->isHtml) {
            $headers = 'Content-Type: text/html' . PHP_EOL;
        }

        $headers .= 'From: ' . $this->getFrom() . PHP_EOL;

        $to = array();
        foreach ($this->to as $email => $name) {
            $thisTo = $email;

            if (!is_null($name)) {
                $thisTo = '"' . $name . '" <' . $thisTo . '>';
            }

            $to[] = $thisTo;
        }

        $to = implode(', ', $to);

        return mail($to, $this->subject, $this->body, $headers);
    }

    protected function sendViaSwiftMailer()
    {
        $factory = new MailerFactory($this->config->get('phpci'));
        $mailer = $factory->getSwiftMailerFromConfig();

        $message = \Swift_Message::newInstance($this->subject)
            ->setFrom($this->getFrom())
            ->setTo($this->to)
            ->setBody($this->body);

        if ($this->isHtml) {
            $message->setContentType('text/html');
        }

        if (is_array($this->cc) && count($this->cc)) {
            $message->setCc($this->cc);
        }

        return $mailer->send($message);
    }

    protected function getFrom()
    {
        $email = $this->config->get('phpci.email_settings.from_address', self::DEFAULT_FROM);

        if (empty($email)) {
            $email = self::DEFAULT_FROM;
        }

        return $email;
    }
}