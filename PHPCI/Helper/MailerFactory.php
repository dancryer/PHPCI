<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace PHPCI\Helper;


class MailerFactory
{
    /**
     * @var array
     */
    protected $emailConfig;

    public function __construct($config = null)
    {
        $this->emailConfig  = isset($config['email_settings']) ? $config['email_settings'] : array();
    }

    /**
     * Returns an instance of Swift_Mailer based on the config.s
     * @return \Swift_Mailer
     */
    public function getSwiftMailerFromConfig()
    {
        /** @var \Swift_SmtpTransport $transport */
        $transport = \Swift_SmtpTransport::newInstance(
            $this->getMailConfig('smtp_address'),
            $this->getMailConfig('smtp_port'),
            $this->getMailConfig('smtp_encryption')
        );
        $transport->setUsername($this->getMailConfig('smtp_username'));
        $transport->setPassword($this->getMailConfig('smtp_password'));

        return \Swift_Mailer::newInstance($transport);
    }

    protected function getMailConfig($configName)
    {
        if (isset($this->emailConfig[$configName]) && $this->emailConfig[$configName] != "") {
            return $this->emailConfig[$configName];
        } else {
            // Check defaults

            switch($configName) {
                case 'smtp_address':
                    return "localhost";
                case 'default_mailto_address':
                    return null;
                case 'smtp_port':
                    return '25';
                case 'smtp_encryption':
                    return null;
                default:
                    return "";
            }
        }
    }
}
