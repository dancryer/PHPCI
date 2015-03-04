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
use PHPCI\Model\Build;

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
     * @var \PHPUnit_Framework_MockObject_MockObject $mockBuild
     */
    protected $mockBuild;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $mockProject
     */
    protected $mockProject;

    /**
     * @var int buildStatus
     */
    protected $buildStatus;

    /**
     * @var array $message;
     */
    protected $message;

    /**
     * @var bool $mailDelivered
     */
    protected $mailDelivered;

    public function setUp()
    {
        $this->message = array();
        $this->mailDelivered = true;

        $this->mockProject = $this->getMock(
            '\PHPCI\Model\Project',
            array('getTitle'),
            array(),
            "mockProject",
            false
        );

        $this->mockProject->expects($this->any())
            ->method('getTitle')
            ->will($this->returnValue("Test-Project"));

        $this->mockBuild = $this->getMock(
            '\PHPCI\Model\Build',
            array('getLog', 'getStatus', 'getProject', 'getCommitterEmail'),
            array(),
            "mockBuild",
            false
        );

        $this->mockBuild->expects($this->any())
            ->method('getLog')
            ->will($this->returnValue("Build Log"));

        $this->mockBuild->expects($this->any())
            ->method('getStatus')
            ->will($this->returnCallback(function () {
                return $this->buildStatus;
            }));

        $this->mockBuild->expects($this->any())
            ->method('getProject')
            ->will($this->returnValue($this->mockProject));

        $this->mockBuild->expects($this->any())
            ->method('getCommitterEmail')
            ->will($this->returnValue('committer-email@example.com'));

        $this->mockCiBuilder = $this->getMock(
            '\PHPCI\Builder',
            array(
                'getSystemConfig',
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
    }

    protected function loadEmailPluginWithOptions($arrOptions = array(), $buildStatus = null, $mailDelivered = true)
    {
        $this->mailDelivered = $mailDelivered;

        if (is_null($buildStatus)) {
            $this->buildStatus = Build::STATUS_SUCCESS;
        } else {
            $this->buildStatus = $buildStatus;
        }

        // Reset current message.
        $this->message = array();

        $this->testedEmailPlugin = $this->getMock(
            '\PHPCI\Plugin\Email',
            array('sendEmail'),
            array(
                $this->mockCiBuilder,
                $this->mockBuild,
                $arrOptions
            )
        );

        $this->testedEmailPlugin->expects($this->any())
            ->method('sendEmail')
            ->will($this->returnCallback(function ($to, $cc, $subject, $body) {
                $this->message['to'][] = $to;
                $this->message['cc'] = $cc;
                $this->message['subject'] = $subject;
                $this->message['body'] = $body;

                return $this->mailDelivered;
            }));
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testReturnsFalseWithoutArgs()
    {
        $this->loadEmailPluginWithOptions();

        $returnValue = $this->testedEmailPlugin->execute();

        // As no addresses will have been mailed as non are configured.
        $expectedReturn = false;

        $this->assertEquals($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testBuildsBasicEmails()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com')
            ),
            Build::STATUS_SUCCESS
        );

        $this->testedEmailPlugin->execute();

        $this->assertContains('test-receiver@example.com', $this->message['to']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testBuildsDefaultEmails()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'default_mailto_address' => 'default-mailto-address@example.com'
            ),
            Build::STATUS_SUCCESS
        );

        $this->testedEmailPlugin->execute();

        $this->assertContains('default-mailto-address@example.com', $this->message['to']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_UniqueRecipientsFromWithCommitter()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com', 'test-receiver2@example.com')
            )
        );

        $returnValue = $this->testedEmailPlugin->execute();
        $this->assertTrue($returnValue);

        $this->assertCount(2, $this->message['to']);

        $this->assertContains('test-receiver@example.com', $this->message['to']);
        $this->assertContains('test-receiver2@example.com', $this->message['to']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testExecute_UniqueRecipientsWithCommiter()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'commiter'  => true,
                'addresses' => array('test-receiver@example.com', 'committer@test.com')
            )
        );

        $returnValue = $this->testedEmailPlugin->execute();
        $this->assertTrue($returnValue);

        $this->assertContains('test-receiver@example.com', $this->message['to']);
        $this->assertContains('committer@test.com', $this->message['to']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testCcDefaultEmails()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'default_mailto_address' => 'default-mailto-address@example.com',
                'cc' => array(
                    'cc-email-1@example.com',
                    'cc-email-2@example.com',
                    'cc-email-3@example.com',
                ),
            ),
            Build::STATUS_SUCCESS
        );

        $this->testedEmailPlugin->execute();

        $this->assertEquals(
            array(
                'cc-email-1@example.com',
                'cc-email-2@example.com',
                'cc-email-3@example.com',
            ),
            $this->message['cc']
        );
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testBuildsCommitterEmails()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'committer' => true
            ),
            Build::STATUS_SUCCESS
        );

        $this->testedEmailPlugin->execute();

        $this->assertContains('committer-email@example.com', $this->message['to']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testMailSuccessfulBuildHaveProjectName()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com')
            ),
            Build::STATUS_SUCCESS
        );

        $this->testedEmailPlugin->execute();

        $this->assertContains('Test-Project', $this->message['subject']);
        $this->assertContains('Test-Project', $this->message['body']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testMailFailingBuildHaveProjectName()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com')
            ),
            Build::STATUS_FAILED
        );

        $this->testedEmailPlugin->execute();

        $this->assertContains('Test-Project', $this->message['subject']);
        $this->assertContains('Test-Project', $this->message['body']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testMailSuccessfulBuildHaveStatus()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com')
            ),
            Build::STATUS_SUCCESS
        );

        $this->testedEmailPlugin->execute();

        $this->assertContains('Passing', $this->message['subject']);
        $this->assertContains('successfull', $this->message['body']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testMailFailingBuildHaveStatus()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com')
            ),
            Build::STATUS_FAILED
        );

        $this->testedEmailPlugin->execute();

        $this->assertContains('Failing', $this->message['subject']);
        $this->assertContains('failed', $this->message['body']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testMailDeliverySuccess()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com')
            ),
            Build::STATUS_FAILED,
            true
        );

        $returnValue = $this->testedEmailPlugin->execute();

        $this->assertEquals(true, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testMailDeliveryFail()
    {
        $this->loadEmailPluginWithOptions(
            array(
                'addresses' => array('test-receiver@example.com')
            ),
            Build::STATUS_FAILED,
            false
        );

        $returnValue = $this->testedEmailPlugin->execute();

        $this->assertEquals(false, $returnValue);
    }
}
