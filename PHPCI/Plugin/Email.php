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
        $this->phpci        = $phpci;
        $this->options      = $options;
        $this->emailConfig  = $phpci->getConfig('email_settings');

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

        return true;
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
        if (isset($this->emailConfig[$configName])) {
            return $this->emailConfig[$configName];
        }
        // Check defaults
        else {
            switch($configName) {
                case 'smtp_port':
                    return '25';
                case 'from_address':
                    return "notifications-ci@phptesting.org";
                default:
                    return "";
            }
        }
    }
}