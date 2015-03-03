<?php

/**
 * PHPCI - Continuous Integration for PHP
 *
 * @copyright    Copyright 2014, Block 8 Limited.
 * @license      https://github.com/Block8/PHPCI/blob/master/LICENSE.md
 * @link         https://www.phptesting.org/
 */

namespace Tests\PHPCI\Plugin\Command;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class CreateAdminCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPCI\Command\CreateAdminCommand|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $command;

    /**
     * @var \Symfony\Component\Console\Application|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $application;

    /**
     * @var \Symfony\Component\Console\Helper\DialogHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $dialog;

    public function setup()
    {
        parent::setup();

        $this->command = $this->getMockBuilder('PHPCI\\Command\\CreateAdminCommand')
            ->setConstructorArgs(array($this->getMock('PHPCI\\Store\\UserStore')))
            ->setMethods(array('reloadConfig'))
            ->getMock()
        ;

        $this->dialog = $this->getMockBuilder('Symfony\\Component\\Console\\Helper\\DialogHelper')
            ->setMethods(array(
                'ask',
                'askAndValidate',
                'askHiddenResponse',
            ))
            ->getMock()
        ;

        $this->application = new Application();
    }

    /**
     * @return CommandTester
     */
    protected function getCommandTester()
    {
        $this->application->getHelperSet()->set($this->dialog, 'dialog');
        $this->application->add($this->command);
        $command = $this->application->find('phpci:create-admin');
        $commandTester = new CommandTester($command);

        return $commandTester;
    }

    public function testExecute()
    {
        $this->dialog->expects($this->at(0))->method('askAndValidate')->will($this->returnValue('test@example.com'));
        $this->dialog->expects($this->at(1))->method('ask')->will($this->returnValue('A name'));
        $this->dialog->expects($this->at(2))->method('askHiddenResponse')->will($this->returnValue('foobar123'));

        $commandTester = $this->getCommandTester();
        $commandTester->execute(array());

        $this->assertEquals('User account created!' . PHP_EOL, $commandTester->getDisplay());
    }
}
