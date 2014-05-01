<?php

namespace PHPCI\Helper;


class MailerFactory
{
    /**
     * @var array
     */
    protected $emailConfig;

    public function __construct($phpCiConfig = null)
    {
        $this->emailConfig  = isset($phpCiSettings['email_settings']) ?: array();
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
