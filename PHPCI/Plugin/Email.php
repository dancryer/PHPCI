<?php
/**
* PHPCI - Continuous Integration for PHP
*
* @copyright    Copyright 2013, Block 8 Limited.
* @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
* @link         http://www.phptesting.org/
*/

namespace PHPCI\Plugin;


/**
* Email Plugin - Provides simple email capability to PHPCI.
* @author       Steve Brazier <meadsteve@gmail.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Email implements \PHPCI\Plugin
{
    
    /**
     * @var \PHPCI\Builder
     */
    protected $phpci;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var array
     */
    protected $emailConfig;

    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    public function __construct(\PHPCI\Builder $phpci,
                                array $options = array(),
                                \Swift_Mailer $mailer = null)
    {
        $phpCiSettings      = $phpci->getSystemConfig('phpci');
        $this->phpci        = $phpci;
        $this->options      = $options;
        $this->emailConfig  = isset($phpCiSettings['email_settings']) ? $phpCiSettings['email_settings'] : array();

        // Either a mailer will have been passed in or we load from the
        // config.
        if ($mailer === null) {
            $this->loadSwiftMailerFromConfig();
        }
        else {
            $this->mailer = $mailer;
        }
    }

    /**
    * Connects to MySQL and runs a specified set of queries.
    */
    public function execute()
    {
        $addresses = $this->getEmailAddresses();

        // Without some email addresses in the yml file then we
        // can't do anything.
        if (count($addresses) == 0) {
            return false;
        }

        $sendFailures = array();

        if($this->phpci->getSuccessStatus()) {
            $body = "";
            $sendFailures = $this->sendSeparateEmails(
                $addresses,
                "PASSED",
                $body
            );
        }
        else {
            $body = "";
            $sendFailures = $this->sendSeparateEmails(
                $addresses,
                "FAILED",
                $body
            );
        }

        // This is a success if we've not failed to send anything.
        $this->phpci->log(sprintf(
                "%d emails sent",
                (count($addresses) - count($sendFailures)))
        );
        $this->phpci->log(sprintf(
                "%d emails failed to send",
                count($sendFailures))
        );
        return (count($sendFailures) == 0);
    }

    /**
     * @param array|string $toAddresses   Array or single address to send to
     * @param string       $subject       Email subject
     * @param string       $body          Email body
     * @return array                      Array of failed addresses
     */
    public function sendEmail($toAddresses, $subject, $body)
    {
        $message = \Swift_Message::newInstance($subject)
            ->setFrom($this->getMailConfig('from_address'))
            ->setTo($toAddresses)
            ->setBody($body);
        $failedAddresses = array();
        $this->mailer->send($message, $failedAddresses);

        return $failedAddresses;
    }

    public function sendSeparateEmails(array $toAddresses, $subject, $body)
    {
        $failures = array();
        foreach($toAddresses as $address) {
            $newFailures = $this->sendEmail($address, $subject, $body);
            foreach($newFailures as $failure) {
                $failures[] = $failure;
            }
        }
        return $failures;
    }

    protected function loadSwiftMailerFromConfig()
    {
        /** @var \Swift_SmtpTransport $transport */
        $transport = \Swift_SmtpTransport::newInstance(
            $this->getMailConfig('smtp_address'),
            $this->getMailConfig('smtp_port')
        );
        $transport->setUsername($this->getMailConfig('smtp_username'));
        $transport->setPassword($this->getMailConfig('smtp_password'));

        $this->mailer = \Swift_Mailer::newInstance($transport);
    }

    protected function getMailConfig($configName)
    {
        if (isset($this->emailConfig[$configName])
            && $this->emailConfig[$configName] != "")
        {
            return $this->emailConfig[$configName];
        }
        // Check defaults
        else {
            switch($configName) {
                case 'smtp_address':
                    return "localhost";
                case 'default_mailto_address':
                    return null;
                case 'smtp_port':
                    return '25';
                case 'from_address':
                    return "notifications-ci@phptesting.org";
                default:
                    return "";
            }
        }
    }

    protected function getEmailAddresses()
    {
        $addresses = array();

        if (isset($this->options['addresses'])) {
            foreach ($this->options['addresses'] as $address) {
                $addresses[] = $address;
            }
        }

        if (isset($this->options['default_mailto_address'])) {
            $addresses[] = $this->options['default_mailto_address'];
            return $addresses;
        }
        return $addresses;
    }
}