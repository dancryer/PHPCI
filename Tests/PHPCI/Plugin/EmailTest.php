<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2013, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 *
 * @link         https://www.phptesting.org/
 */
namespace Tests\PHPCI\Plugin;

use PHPCI\Model\Build;
use PHPCI\Plugin\Email as EmailPlugin;

/**
 * Unit test for the PHPUnit plugin.
 *
 * @author meadsteve
 */
class EmailTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @type EmailPlugin $testedPhpUnit
     */
    protected $testedEmailPlugin;

    /**
     * @type \PHPUnit_Framework_MockObject_MockObject $mockCiBuilder
     */
    protected $mockCiBuilder;

    /**
     * @type \PHPUnit_Framework_MockObject_MockObject $mockBuild
     */
    protected $mockBuild;

    /**
     * @type \PHPUnit_Framework_MockObject_MockObject $mockProject
     */
    protected $mockProject;

    /**
     * @type int buildStatus
     */
    public $buildStatus;

    /**
     * @type array $message;
     */
    public $message;

    /**
     * @type bool $mailDelivered
     */
    public $mailDelivered;

    public function setUp()
    {
        $this->message       = [];
        $this->mailDelivered = true;
        $self                = $this;

        $this->mockProject = $this->getMock(
            '\PHPCI\Model\Project',
            ['getTitle'],
            [],
            'mockProject',
            false
        );

        $this->mockProject->expects($this->any())
            ->method('getTitle')
            ->will($this->returnValue('Test-Project'));

        $this->mockBuild = $this->getMock(
            '\PHPCI\Model\Build',
            ['getLog', 'getStatus', 'getProject', 'getCommitterEmail'],
            [],
            'mockBuild',
            false
        );

        $this->mockBuild->expects($this->any())
            ->method('getLog')
            ->will($this->returnValue('Build Log'));

        $this->mockBuild->expects($this->any())
            ->method('getStatus')
            ->will($this->returnCallback(function () use ($self) {
                return $self->buildStatus;
            }));

        $this->mockBuild->expects($this->any())
            ->method('getProject')
            ->will($this->returnValue($this->mockProject));

        $this->mockBuild->expects($this->any())
            ->method('getCommitterEmail')
            ->will($this->returnValue('committer-email@example.com'));

        $this->mockCiBuilder = $this->getMock(
            '\PHPCI\Builder',
            [
                'getSystemConfig',
                'getBuild',
                'log',
            ],
            [],
            'mockBuilder_email',
            false
        );

        $this->mockCiBuilder->buildPath = '/';

        $this->mockCiBuilder->expects($this->any())
            ->method('getSystemConfig')
            ->with('phpci')
            ->will(
                $this->returnValue(
                    [
                        'email_settings' => [
                            'from_address' => 'test-from-address@example.com',
                        ],
                    ]
                )
            );
    }

    protected function loadEmailPluginWithOptions($arrOptions = [], $buildStatus = null, $mailDelivered = true)
    {
        $this->mailDelivered = $mailDelivered;

        if (is_null($buildStatus)) {
            $this->buildStatus = Build::STATUS_SUCCESS;
        } else {
            $this->buildStatus = $buildStatus;
        }

        // Reset current message.
        $this->message = [];

        $self = $this;

        $this->testedEmailPlugin = $this->getMock(
            '\PHPCI\Plugin\Email',
            ['sendEmail'],
            [
                $this->mockCiBuilder,
                $this->mockBuild,
                $arrOptions,
            ]
        );

        $this->testedEmailPlugin->expects($this->any())
            ->method('sendEmail')
            ->will($this->returnCallback(function ($to, $cc, $subject, $body) use ($self) {
                $self->message['to'][] = $to;
                $self->message['cc'] = $cc;
                $self->message['subject'] = $subject;
                $self->message['body'] = $body;

                return $self->mailDelivered;
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

        $this->assertSame($expectedReturn, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testBuildsBasicEmails()
    {
        $this->loadEmailPluginWithOptions(
            [
                'addresses' => ['test-receiver@example.com'],
            ],
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
            [
                'default_mailto_address' => 'default-mailto-address@example.com',
            ],
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
            [
                'addresses' => ['test-receiver@example.com', 'test-receiver2@example.com'],
            ]
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
    public function testExecute_UniqueRecipientsWithCommitter()
    {
        $this->loadEmailPluginWithOptions(
            [
                'committer'  => true,
                'addresses'  => ['test-receiver@example.com', 'committer@test.com'],
            ]
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
            [
                'default_mailto_address' => 'default-mailto-address@example.com',
                'cc'                     => [
                    'cc-email-1@example.com',
                    'cc-email-2@example.com',
                    'cc-email-3@example.com',
                ],
            ],
            Build::STATUS_SUCCESS
        );

        $this->testedEmailPlugin->execute();

        $this->assertSame(
            [
                'cc-email-1@example.com',
                'cc-email-2@example.com',
                'cc-email-3@example.com',
            ],
            $this->message['cc']
        );
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testBuildsCommitterEmails()
    {
        $this->loadEmailPluginWithOptions(
            [
                'committer' => true,
            ],
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
            [
                'addresses' => ['test-receiver@example.com'],
            ],
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
            [
                'addresses' => ['test-receiver@example.com'],
            ],
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
            [
                'addresses' => ['test-receiver@example.com'],
            ],
            Build::STATUS_SUCCESS
        );

        $this->testedEmailPlugin->execute();

        $this->assertContains('Passing', $this->message['subject']);
        $this->assertContains('successful', $this->message['body']);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testMailFailingBuildHaveStatus()
    {
        $this->loadEmailPluginWithOptions(
            [
                'addresses' => ['test-receiver@example.com'],
            ],
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
            [
                'addresses' => ['test-receiver@example.com'],
            ],
            Build::STATUS_FAILED,
            true
        );

        $returnValue = $this->testedEmailPlugin->execute();

        $this->assertSame(true, $returnValue);
    }

    /**
     * @covers PHPUnit::execute
     */
    public function testMailDeliveryFail()
    {
        $this->loadEmailPluginWithOptions(
            [
                'addresses' => ['test-receiver@example.com'],
            ],
            Build::STATUS_FAILED,
            false
        );

        $returnValue = $this->testedEmailPlugin->execute();

        $this->assertSame(false, $returnValue);
    }
}
