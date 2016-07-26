<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace tests\PHPCI\Service;

use PHPCI\Helper\MailerFactory;

/**
 * Unit tests for the ProjectService class.
 *
 * @author Dan Cryer <dan@block8.co.uk>
 */
class MailerFactoryTest extends \PHPUnit_Framework_TestCase
{
   public function setUp()
    {
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestGetMailConfig()
    {
        $config = [
            'smtp_address'           => 'mail.example.com',
            'smtp_port'              => 225,
            'smtp_encryption'        => true,
            'smtp_username'          => 'example.user',
            'smtp_password'          => 'examplepassword',
            'default_mailto_address' => 'phpci@example.com',
        ];

        $factory = new MailerFactory(['email_settings' => $config]);

        $this->assertSame($config['smtp_address'], $factory->getMailConfig('smtp_address'));
        $this->assertSame($config['smtp_port'], $factory->getMailConfig('smtp_port'));
        $this->assertSame($config['smtp_encryption'], $factory->getMailConfig('smtp_encryption'));
        $this->assertSame($config['smtp_username'], $factory->getMailConfig('smtp_username'));
        $this->assertSame($config['smtp_password'], $factory->getMailConfig('smtp_password'));
        $this->assertSame($config['default_mailto_address'], $factory->getMailConfig('default_mailto_address'));
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_TestMailer()
    {
        $config = [
            'smtp_address'           => 'mail.example.com',
            'smtp_port'              => 225,
            'smtp_encryption'        => true,
            'smtp_username'          => 'example.user',
            'smtp_password'          => 'examplepassword',
            'default_mailto_address' => 'phpci@example.com',
        ];

        $factory = new MailerFactory(['email_settings' => $config]);
        $mailer  = $factory->getSwiftMailerFromConfig();

        $this->assertSame($config['smtp_address'], $mailer->getTransport()->getHost());
        $this->assertSame($config['smtp_port'], $mailer->getTransport()->getPort());
        $this->assertSame('tls', $mailer->getTransport()->getEncryption());
        $this->assertSame($config['smtp_username'], $mailer->getTransport()->getUsername());
        $this->assertSame($config['smtp_password'], $mailer->getTransport()->getPassword());
    }
}
