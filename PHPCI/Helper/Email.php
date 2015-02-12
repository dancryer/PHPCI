<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;

use b8\Config;
use PHPCI\Helper\MailerFactory;

/**
 * Helper class for sending emails using PHPCI's email configuration.
 * @package PHPCI\Helper
 */
class Email
{
    const DEFAULT_FROM = 'PHPCI <no-reply@phptesting.org>';

    protected $emailTo = array();
    protected $emailCc = array();
    protected $subject = 'Email from PHPCI';
    protected $body = '';
    protected $isHtml = false;
    protected $config;

    /**
     * Create a new email object.
     */
    public function __construct()
    {
        $this->config = Config::getInstance();
    }

    /**
     * Set the email's To: header.
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function setEmailTo($email, $name = null)
    {
        $this->emailTo[$email] = $name;

        return $this;
    }

    /**
     * Add an address to the email's CC header.
     * @param string $email
     * @param string|null $name
     * @return $this
     */
    public function addCc($email, $name = null)
    {
        $this->emailCc[$email] = $name;

        return $this;
    }

    /**
     * Set the email subject.
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the email body.
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Set whether or not the email body is HTML.
     * @param bool $isHtml
     * @return $this
     */
    public function setHtml($isHtml = false)
    {
        $this->isHtml = $isHtml;

        return $this;
    }

    /**
     * Send the email.
     * @return bool|int
     */
    public function send()
    {
        $smtpServer = $this->config->get('phpci.email_settings.smtp_address');

        if (empty($smtpServer)) {
            return $this->sendViaMail();
        } else {
            return $this->sendViaSwiftMailer();
        }
    }

    /**
     * Sends the email via the built in PHP mail() function.
     * @return bool
     */
    protected function sendViaMail()
    {
        $headers = '';

        if ($this->isHtml) {
            $headers = 'Content-Type: text/html' . PHP_EOL;
        }

        $headers .= 'From: ' . $this->getFrom() . PHP_EOL;

        $emailTo = array();
        foreach ($this->emailTo as $email => $name) {
            $thisTo = $email;

            if (!is_null($name)) {
                $thisTo = '"' . $name . '" <' . $thisTo . '>';
            }

            $emailTo[] = $thisTo;
        }

        $emailTo = implode(', ', $emailTo);

        return mail($emailTo, $this->subject, $this->body, $headers);
    }

    /**
     * Sends the email using SwiftMailer.
     * @return int
     */
    protected function sendViaSwiftMailer()
    {
        $factory = new MailerFactory($this->config->get('phpci'));
        $mailer = $factory->getSwiftMailerFromConfig();

        $message = \Swift_Message::newInstance($this->subject)
            ->setFrom($this->getFrom())
            ->setTo($this->emailTo)
            ->setBody($this->body);

        if ($this->isHtml) {
            $message->setContentType('text/html');
        }

        if (is_array($this->emailCc) && count($this->emailCc)) {
            $message->setCc($this->emailCc);
        }

        return $mailer->send($message);
    }

    /**
     * Get the from address to use for the email.
     * @return mixed|string
     */
    protected function getFrom()
    {
        $email = $this->config->get('phpci.email_settings.from_address', self::DEFAULT_FROM);

        if (empty($email)) {
            $email = self::DEFAULT_FROM;
        }

        return $email;
    }
}
