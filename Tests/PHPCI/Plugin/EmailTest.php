<?php
/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license        https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link            http://www.phptesting.org/
 */

namespace PHPCI\Plugin\Tests;

use PHPCI\Plugin\Email as EmailPlugin;


/**
 * Unit test for the PHPUnit plugin.
 * @author meadsteve
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var EmailPlugin $testedPhpUnit
     */
    protected $testedEmailPlugin;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockCiBuilder
     */
    protected $mockCiBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockMailer
     */
    protected $mockMailer;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockMailer
     */
    protected $mockBuild;

    public function setUp()
    {
        $this->mockBuild = $this->getMock(
            '\PHPCI\Model\Build',
            array('getLog', 'getStatus'),
            array(),
            "mockBuild",
            false
        );

        $this->mockBuild->expects($this->any())
            ->method('getLog')
            ->will($this->returnValue("Build Log"));

        $this->mockBuild->expects($this->any())
            ->method('getStatus')
            ->will($this->returnValue(\PHPCI\Model\Build::STATUS_SUCCESS));

        $this->mockCiBuilder = $this->getMock(
            '\PHPCI\Builder',
            array(
                'getSystemConfig',
                'getBuildProjectTitle',
                'getBuild',
                'log'
            ),
            array(),
            "mockBuilder_email",
            false
        );

        $this->mockCiBuilder->buildPath = "/";

        $this->mockCiBuilder->expects($this->any())
            ->method('getSystemConfig')
            ->with('phpci')
            ->will(
                $this->returnValue(
                    array(
                        'email_settings' => array(
                            'from_address' => "test-from-address@example.com"
                        )
                    )
                )
            );
        $this->mockCiBuilder->expects($this->any())
            ->method('getBuildProjectTitle')
            ->will($this->returnValue('Test-Project'));
        $this->mockCiBuilder->expects($this->any())
            ->method('getBuild')
            ->will($this->returnValue($this->mockBuild));

        $this->mockMailer = $this->getMock(
            '\Swift_Mailer',
            array('send'),
            array(),
            "mockMailer",
            false
        );

        $this->loadEmailPluginWithOptions();
    }

    protected function loadEmailPluginWithOptions($arrOptions = array())
    {
        $this->testedEmailPlugin = new EmailPlugin(
            $this->mockCiBuilder,
            $this->mockBuild,
            $this->mockMailer,
            $arrOptions
        );
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_ReturnsFalseWithoutArgs()
    {
        $returnValue = $this->testedEmailPlugin->execute();
        // As no addresses will have been mailed as non are configured.
        $expectedReturn = false;

        $this->assertEquals($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_BuildsBasicEmails()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com')
            )
        );

        /** @var \Swift_Message $actualMail */
        $actualMail = null;
        $this->catchMailPassedToSend($actualMail);

        $returnValue = $this->testedEmailPlugin->execute();
        $expectedReturn = true;

        $this->assertSystemMail(
            'test-receiver@example.com',
            'test-from-address@example.com',
            "Log Output: <br><pre>Build Log</pre>",
            "PHPCI - Test-Project - Passing Build",
            $actualMail
        );

        $this->assertEquals($expectedReturn, $returnValue);


    }

    /**
     * @covers PHPUnit::sendEmail
     */
    public function testSendEmail_CallsMailerSend()
    {
        $this->mockMailer->expects($this->once())
            ->method('send');
        $this->testedEmailPlugin->sendEmail("test@email.com", "hello", "body");
    }

    /**
     * @covers PHPUnit::sendEmail
     */
    public function testSendEmail_BuildsAMessageObject()
    {
        $subject = "Test mail";
        $body = "Message Body";
        $toAddress = "test@example.com";

        $this->mockMailer->expects($this->once())
            ->method('send')
            ->with($this->isInstanceOf('\Swift_Message'), $this->anything());
        $this->testedEmailPlugin->sendEmail($toAddress, $subject, $body);
    }

    /**
     * @covers PHPUnit::sendEmail
     */
    public function testSendEmail_BuildsExpectedMessage()
    {
        $subject = "Test mail";
        $body = "Message Body";
        $toAddress = "test@example.com";
        $expectedMessage = \Swift_Message::newInstance($subject)
            ->setFrom('test-from-address@example.com')
            ->setTo($toAddress)
            ->setBody($body);

        /** @var \Swift_Message $actualMail */
        $actualMail = null;
        $this->catchMailPassedToSend($actualMail);

        $this->testedEmailPlugin->sendEmail($toAddress, $subject, $body);

        $this->assertSystemMail(
            $toAddress,
            'test-from-address@example.com',
            $body,
            $subject,
            $actualMail
        );
    }

    /**
     * @param \Swift_Message $actualMail passed by ref and populated with
     *                                     the message object the mock mailer
     *                                     receives.
     */
    protected function catchMailPassedToSend(&$actualMail)
    {
        $this->mockMailer->expects($this->once())
            ->method('send')
            ->will(
                $this->returnCallback(
                    function ($passedMail) use (&$actualMail) {
                        $actualMail = $passedMail;
                        return array();
                    }
                )
            );
    }

    /**
     * Asserts that the actual mail object is populated as expected.
     *
     * @param string $expectedToAddress
     * @param $expectedFromAddress
     * @param string $expectedBody
     * @param string $expectedSubject
     * @param \Swift_Message $actualMail
     */
    protected function assertSystemMail(
        $expectedToAddress,
        $expectedFromAddress,
        $expectedBody,
        $expectedSubject,
        $actualMail
    ) {
        if (!($actualMail instanceof \Swift_Message)) {
            $type = is_object($actualMail) ? get_class($actualMail) : gettype(
                $actualMail
            );
            throw new \Exception("Expected Swift_Message got " . $type);
        }
        $this->assertEquals(
            array($expectedFromAddress => null),
            $actualMail->getFrom()
        );

        $this->assertEquals(
            array($expectedToAddress => null),
            $actualMail->getTo()
        );

        $this->assertEquals(
            $expectedBody,
            $actualMail->getBody()
        );

        $this->assertEquals(
            $expectedSubject,
            $actualMail->getSubject()
        );
    }
}