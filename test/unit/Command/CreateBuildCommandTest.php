<?php
/**
 * Kiboko CI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license        https://github.com/kiboko-labs/ci/blob/master/LICENSE.md
 * @link            http://www.phptesting.org/
 */

namespace Tests\Kiboko\Component\ContinuousIntegration\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateBuildCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Kiboko\Component\ContinuousIntegration\Command\CreateAdminCommand|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $command;

    /**
     * @var \Symfony\Component\Console\Application|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $application;

    public function setup()
    {
        parent::setup();

        $projectMock = $this->getMock('Kiboko\\Component\\ContinuousIntegration\\Model\\Project');

        $projectStoreMock = $this->getMockBuilder('Kiboko\\Component\\ContinuousIntegration\\Store\\ProjectStore')
            ->getMock();
        $projectStoreMock->method('getById')
            ->will($this->returnValueMap(array(
                array(1, 'read', $projectMock),
                array(2, 'read', null),
            )));

        $buildServiceMock = $this->getMockBuilder('Kiboko\\Component\\ContinuousIntegration\\Service\\BuildService')
            ->disableOriginalConstructor()
            ->getMock();
        $buildServiceMock->method('createBuild')
            ->withConsecutive(
                array($projectMock, null, null, null, null, null),
                array($projectMock, '92c8c6e', null, null, null, null),
                array($projectMock, null, 'master', null, null, null)
            );

        $this->command = $this->getMockBuilder('Kiboko\\Component\\ContinuousIntegration\\Command\\CreateBuildCommand')
            ->setConstructorArgs(array($projectStoreMock, $buildServiceMock))
            ->setMethods(array('reloadConfig'))
            ->getMock();

        $this->application = new Application();
    }

    protected function getCommandTester()
    {
        $this->application->add($this->command);

        $command = $this->application->find('phpci:create-build');
        $commandTester = new CommandTester($command);

        return $commandTester;
    }

    public function testExecute()
    {
        $commandTester = $this->getCommandTester();

        $commandTester->execute(array('projectId' => 1));
        $commandTester->execute(array('projectId' => 1, '--commit' => '92c8c6e'));
        $commandTester->execute(array('projectId' => 1, '--branch' => 'master'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testExecuteWithUnknownProjectId()
    {
        $commandTester = $this->getCommandTester();
        $commandTester->execute(array('projectId' => 2));
    }
}
